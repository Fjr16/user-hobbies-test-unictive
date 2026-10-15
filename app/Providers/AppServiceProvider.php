<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

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
        RateLimiter::for('login', function(Request $req) {
            return [
                Limit::perMinute(20)->by($req->ip()),
                Limit::perMinute(3)->by(Str::lower($req->input('email')) . '|' . $req->ip)->after(fn (Response $res) => $res->status() === 401)
            ];
        });

        Paginator::useBootstrapFive();
    }
}
