<?php

namespace App\Http\Controllers;

use App\EtsyService;
use App\Models\Feed;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Masmerise\Toaster\Toaster;

class EtsyController extends Controller
{
    public function __construct(
        private readonly EtsyService $etsyService
    )
    {
    }

    public function callback(Request $request)
    {
        $state = Session::get('etsy-state');
        $codeVerifier = Session::get('etsy-code-verifier');
        if($state === null || $codeVerifier === $state) {
            Toaster::error('An error occurred. Please try again.');
            return redirect(route('dashboard'));
        }
        if($state !== $request->input('state')) {
            Toaster::error('An error occurred. Please try again.');
            return redirect(route('dashboard'));
        }
        if(!$request->input('code')) {
            Toaster::error('An error occurred. Please try again.');
            return redirect(route('dashboard'));
        }
        try {
            $token = $this->etsyService->getAccessToken($request->input('code'), $codeVerifier);
        } catch (RequestException $e) {
            Toaster::error('An error occurred. Please try again.');
            return redirect(route('dashboard'));
        }
        try {
            $shop = $this->etsyService
                ->authenticate($token)
                ->setShopId(explode('.', $token['access_token'])[0])
                ->getShop();
        } catch (ConnectionException|RequestException $e) {
            Toaster::error('An error occurred. Please try again.');
            return redirect(route('dashboard'));
        }
        $feed = Auth::user()->feeds()->create([
            'shop_id' => $shop->shop_id,
            'shop_name' => $shop->shop_name,
            'auth_token' => $token,
            'token_expires_at' => now()->addSeconds($token['expires_in']),
        ]);
        Session::remove('etsy-state');
        Session::remove('etsy-code-verifier');
        Toaster::success('Your feed has been succesfully connected, and will be updated shortly.');
        return  redirect()->route('store', ['feed' => $feed]);
    }
    public function view(Feed $feed)
    {

    }
}
