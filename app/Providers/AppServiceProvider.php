<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Sesh\Lib\GuzzleHttpClient;

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
    }
}
