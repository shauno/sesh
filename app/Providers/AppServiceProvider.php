<?php

namespace App\Providers;

use DB;
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
        if (DB::connection() instanceof \Illuminate\Database\SQLiteConnection) {
            DB::statement(DB::raw('PRAGMA foreign_keys=1'));
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app->bind('Sesh\Lib\HttpClient', function ($app) {
            return new GuzzleHttpClient();
        });

        $this->app->bind('Sesh\Msw\MswClient', function() {
            return new MswClient(new GuzzleHttpClient(), getenv('MSW_API_KEY'));
        });
    }
}
