<?php

namespace App\Http\Controllers;

class RedirectController extends Controller
{
    public function __invoke()
    {
        return redirect('https://mylittlechaletshop.etsy.com/');
    }
}
