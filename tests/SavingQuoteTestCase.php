<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Jauntin\SavingQuote\SavingQuoteServiceProvider;
use Orchestra\Testbench\TestCase;

abstract class SavingQuoteTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh');
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function getPackageProviders($app): array
    {
        return [SavingQuoteServiceProvider::class];
    }
}
