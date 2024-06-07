<?php

namespace App\Models;

use App\Dto\EtsyListingDto;
use App\Dto\ListingStateEnum;
use App\Dto\PriceDto;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property EtsyListingDto $data
 */
class FeedItem extends Model
{
    protected $guarded = [];

    protected $casts = [
        'feed_id' => 'int',
        'listing_id' => 'int',
        'quantity' => 'int',
        'price_amount' => 'int',
        'price_divisor' => 'int',
        'state' => ListingStateEnum::class,
        'created_timestamp' => 'timestamp',
        'ending_timestamp' => 'timestamp',
        'images' => 'array',
    ];

    protected function data(): Attribute
    {
        return Attribute::make(
            get: fn($value) => EtsyListingDto::make(json_decode($value, true)),
            set: fn($value) => json_encode($value),
        );
    }

    public function feed(): BelongsTo
    {
        return $this->belongsTo(Feed::class);
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => new PriceDto(
                $attributes['price_amount'],
                $attributes['price_divisor'],
                $attributes['price_currency_code'],
            ),
            set: fn(PriceDto $value) => [
                'price_amount' => $value->amount,
                'price_divisor' => $value->divisor,
                'price_currency_code' => $value->currency_code,
            ],
        );
    }
}
