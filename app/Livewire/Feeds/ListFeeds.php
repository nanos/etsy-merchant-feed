<?php

namespace App\Livewire\Feeds;

use App\EtsyService;
use App\Models\Feed;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;

class ListFeeds extends Component
{
    /** @var Collection<Feed> */
    public Collection $feeds;

    public function connect(): \Illuminate\Foundation\Application|Redirector|Application|RedirectResponse
    {
        Session::put('etsy-state', $state = Str::random(40));
        Session::put('etsy-code-verifier', $codeVerifier = Str::random(128));
        return redirect(app(EtsyService::class)->getConnectUrl($state, $codeVerifier), 302);
    }

    public function deleteFeed(Feed $feed): void
    {
        if($feed->user_id === Auth::id()) {
            $feed->delete();
        }
        $this->feeds = Auth::user()->feeds;
    }

    public function render(): Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|View|Application
    {
        return view('livewire.feeds.list-feeds');
    }
}
