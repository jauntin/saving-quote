<?php

namespace Jauntin\SavingQuote;

use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use Jauntin\SavingQuote\Interfaces\QuoteProgressValidationRules;
use Jauntin\SavingQuote\Service\QuoteProgressService;

final class SavingQuoteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('saving-quote.php'),
        ]);
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'saving-quote');

        $this->mergeConfigFrom(__DIR__.'/../config/database.php', 'database');

        $this->app->singleton(QuoteProgressService::class, function (Container $container) {
            $mailableClass = config('saving-quote.mailable');
            $service = new QuoteProgressService(
                expireUnit: (string) config('saving-quote.expire.unit'),
                expireValue: (int) config('saving-quote.expire.value') + (int) config('saving-quote.expire.grace_period'),
                mailable: $mailableClass && class_exists($mailableClass) ? $container->make($mailableClass) : null
            );

            return $service;
        });

        $this->app->bind(QuoteProgressValidationRules::class, config('saving-quote.validator'));
    }
}
