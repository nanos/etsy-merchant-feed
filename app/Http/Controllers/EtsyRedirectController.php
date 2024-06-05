<?php

namespace App\Http\Controllers;

use App\Models\FeedItem;

/** @deprecated
 * @see FeedItemRedirectController::class  
 */
class EtsyRedirectController extends Controller
{
    public function __invoke(FeedItem $feedItem)
    {
        return redirect($feedItem->url);
    }
}
