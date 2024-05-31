<?php

use App\Http\Controllers\EtsyController;
use App\Http\Controllers\EtsyFeedController;
use App\Http\Controllers\EtsyRedirectController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'pages.auth.login');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('etsy/callback', [EtsyController::class, 'callback'])
    ->middleware(['auth'])
    ->name('etsy.callback');

Route::get('etsy/feed/{feed}', EtsyFeedController::class)
    ->name('etsy.feed');

Route::get('feed_item/{feedItem}', EtsyRedirectController::class)
    ->name('feed_item.redirect');

require __DIR__.'/auth.php';
