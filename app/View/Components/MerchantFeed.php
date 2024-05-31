<?php

namespace App\View\Components;

use App\Models\Feed;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MerchantFeed extends Component
{
    public function __construct(
        public readonly Feed $feed,
    )
    {
    }

    public function render(): View
    {
        return view('components.merchant-feed');
    }
}
