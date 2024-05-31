<?php

namespace App\Http\Controllers;

use App\Models\FeedItem;

class EtsyRedirectController extends Controller
{
    public function __invoke(FeedItem $feedItem)
    {
        return redirect($feedItem->url);
    }
}
