<?php

namespace App\Providers;

use App\EtsyService;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(EtsyService::class, function ($app) {
            return new EtsyService(
                config('services.etsy.keyString'),
                config('services.etsy.url'),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Password::defaults(fn() => Password
            ::min(8)
            ->letters()
            ->numbers()
            ->uncompromised()
        );

        Carbon::macro(
            'toUserTimezone',
            fn (): Carbon => $this->setTimezone(auth()->user()?->timezone ?? config('app.timezone')),
        );

        Carbon::macro(
            'toDefaultDateTimeString',
            fn (bool $inUserTimeZone = true): string => ($inUserTimeZone ? $this->toUserTimezone() : $this)
                ->format('d/m/y H:i'),
        );
    }
}
