<?php

namespace Jauntin\SavingQuote;

use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use Jauntin\SavingQuote\Http\Controllers\QuoteProgressController;
use Jauntin\SavingQuote\Service\QuoteProgressService;

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

        $this->app->singleton(QuoteProgressService::class, function (Container $container) {
            $service = new QuoteProgressService(
                (string) config('saving-quote.expire.unit'),
                (int) config('saving-quote.expire.value'),
            );

            $mailableClass = config('saving-quote.mailable');

            if ($mailableClass && class_exists($mailableClass)) {
                $service->setMailable($container->make($mailableClass));
            }

            return $service;
        });

        $this->app->singleton(QuoteProgressController::class, function (Container $container) {
            $controller = new QuoteProgressController();

            $validatorClass = config('saving-quote.validator');

            if ($validatorClass && class_exists($validatorClass)) {
                $controller->setValidator($container->make($validatorClass));
            }

            return $controller;
        });
    }
}
