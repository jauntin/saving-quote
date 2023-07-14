<?php

namespace Jauntin\SavingQuote;

use Illuminate\Support\ServiceProvider;

final class SavingQuoteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('saving-quote.php'),
        ]);
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'saving-quote');
    }
}
