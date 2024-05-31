<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
            ->orWhere('last_update', '<', now()->subDay())
        );
    }
}
