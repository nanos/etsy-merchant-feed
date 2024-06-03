<?php

namespace App\Http\Controllers;

use App\Models\Feed;

class StoreController extends Controller
{
    public function __invoke(Feed $feed)
    {
        return view('feed', compact('feed'));
    }
}
