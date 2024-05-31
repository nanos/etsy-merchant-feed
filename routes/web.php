<?php

use App\Http\Controllers\EtsyController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('etsy/callback', [EtsyController::class, 'callback'])->name('etsy.callback');

require __DIR__.'/auth.php';
