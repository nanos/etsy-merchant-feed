<?php

namespace App\Policies;

use App\Models\Feed;
use App\Models\User;

class FeedPolicy
{
    public function delete(User $user, Feed $feed): bool
    {
        return $this->update($user, $feed);
    }
    
    public function update(User $user, Feed $feed): bool
    {
        return $user->id === $feed->user_id;
    }
}
