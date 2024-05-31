<?php

namespace App\Console\Commands;

use App\Dto\EtsyListingDto;
use App\EtsyService;
use App\Models\Feed;
use App\Models\FeedItem;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;

class UpdateFeedsCommand extends Command
{
    protected $signature = 'feeds:update';

    protected $description = 'Command description';

    public function __construct(
        private readonly EtsyService $etsyService
    )
    {
        parent::__construct();
    }

    public function handle(): void
    {
        Feed::query()
            ->needsUpdate()
            ->get()
            ->each(/**
             * @throws RequestException
             * @throws ConnectionException
             */ fn(Feed $feed) => $this->updateFeed($feed));
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    private function updateFeed(Feed $feed): void
    {
        $listings = $this->etsyService->listings($feed);

        $listings->each(fn(EtsyListingDto $listingDto)  => FeedItem::updateOrCreate([
            'feed_id' => $feed->id,
            'listing_id' => $listingDto->listing_id,
        ],[
            'title' => $listingDto->title,
            'description' => $listingDto->description,
            'state' => $listingDto->state,
            'created_timestamp' => $listingDto->created_timestamp,
            'ending_timestamp' => $listingDto->ending_timestamp,
            'quantity' => $listingDto->quantity,
            'url' => $listingDto->url,
            'image_url' => $listingDto->images[0]['url_fullxfull'],
            'images' => $listingDto->images,
            'data' => $listingDto,
            'price' => $listingDto->price,
        ]));

        $feed->items()
            ->whereNotIn('listing_id', $listings->map(fn(EtsyListingDto $item) => $item->listing_id)->toArray())
            ->delete();

        $feed->update([
            'last_update' => now(),
        ]);
    }
}
