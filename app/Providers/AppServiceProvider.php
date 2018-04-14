<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Sesh\Lib\GuzzleHttpClient;
use Sesh\Msw\MswClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Sesh\Lib\HttpClient', function ($app) {
            return new GuzzleHttpClient();
        });

        $this->app->bind('Sesh\Msw\MswClient', function() {
            return new MswClient(new GuzzleHttpClient(), getenv('MSW_API_KEY'));
        });
    }
}
