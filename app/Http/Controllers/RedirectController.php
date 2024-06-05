<?php

namespace App\Http\Controllers;

class RedirectController extends Controller
{
    public function __invoke()
    {
        if(config('services.etsy.redirect_url')) {
            return redirect(config('services.etsy.redirect_url'));
        }
        return redirect(route('dashboard'));
    }
}
