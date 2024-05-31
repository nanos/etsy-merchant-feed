<?php

namespace App\Http\Controllers;

use App\EtsyService;
use App\Models\Feed;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EtsyController extends Controller
{
    public function __construct(
        private readonly EtsyService $etsyService
    )
    {
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function callback(Request $request)
    {
        $state = Session::get('etsy-state');
        $codeVerifier = Session::get('etsy-code-verifier');
        if($state === null || $codeVerifier === $state) {
            return redirect(route('dashboard'))->with('error', 'An error occurred. Please try again.');
        }
        if($state !== $request->input('state')) {
            return redirect(route('dashboard'))->with('error', 'An error occurred. Please try again.');
        }
        $token = $this->etsyService->getAccessToken($request->input('code'), $codeVerifier);
        $shop = $this->etsyService->getShop($token);
        Auth::user()->feeds()->create([
            'shop_id' => $shop->shop_id,
            'shop_name' => $shop->shop_name,
            'auth_token' => $token,
        ]);
        Session::remove('etsy-state');
        Session::remove('etsy-code-verifier');
        return  redirect()->route('dashboard')->with('success', 'Your feed has been succesfully connected.');
    }
    public function view(Feed $feed)
    {

    }
}
