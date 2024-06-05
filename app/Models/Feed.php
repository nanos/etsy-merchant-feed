<?php

namespace App\Models;

use App\UpdateFrequencyEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Feed extends Model
{
    protected $guarded = [];
    protected function casts(): array
    {
        return [
            'auth_token' => 'array',
            'user_id' => 'integer',
            'status' => 'integer',
            'last_update' => 'datetime',
            'token_expires_at' => 'datetime',
            'update_scheduled' => 'bool',
            'update_frequency' => UpdateFrequencyEnum::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(FeedItem::class);
    }

    public function scopeNeedsUpdate(Builder $query): Builder
    {
        return $query->where(fn(Builder $query) => $query
            ->whereNull('last_update')
            ->orWhereRaw("datetime (last_update, '+' || update_frequency ||' seconds') < ?", [now()])
            ->orWhere('update_scheduled', 1)
        );
    }

    protected function brandName(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => $value ?? $attributes['shop_name'],
            set: fn($value) => $value,
        );
    }
}
