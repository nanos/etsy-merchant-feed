<?php

namespace App\Http\Controllers;

use App\Models\Feed;

class FeedItemRedirectController extends Controller
{
    public function __invoke(Feed $feed, string $listingId)
    {
        $feedItem = $feed->items()->where('listing_id', $listingId)->firstOrFail();
        $url = $feedItem->url;
        if($feed->utm_tags) {
            $url .= str_contains($url, '?') ? '&' : '?';
            $url .= ltrim($feed->utm_tags, '?');
        }
        return redirect($url);
    }
}
