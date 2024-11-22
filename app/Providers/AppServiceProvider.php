<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(maxAttempts: 10)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(20)->response(function (Request $request, array $headers) {
                return response('Request exceeded...', 429, $headers);
            });
        });

        RateLimiter::for('uploads', function (Request $request) {
            return $request->user()
                ? Limit::perMinute(20)->by($request->user()->id)
                : Limit::perMinute(10)->by($request->ip());
        });
    }
}
