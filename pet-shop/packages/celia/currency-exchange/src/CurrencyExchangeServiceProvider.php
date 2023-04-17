<?php

namespace Christine\CurrencyExchange;

use Illuminate\Support\ServiceProvider;

class CurrencyExchangeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->make('Christine\CurrencyExchange\CurrencyExchangeController');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        include __DIR__.'/routes.php';
    }
}
