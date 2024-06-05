<?php

namespace App\Http\Controllers;

use App\Models\Feed;

class FeedItemRedirectController extends Controller
{
    public function __invoke(Feed $feed, string $listingId)
    {
        $feedItem = $feed->items()->where('listing_id', $listingId)->firstOrFail();
        return redirect($feedItem->url);
    }
}
