<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Jauntin\SavingQuote\Interfaces\QuoteProgressValidator;
use Jauntin\SavingQuote\SavingQuoteServiceProvider;
use Mockery;
use Orchestra\Testbench\TestCase;

abstract class SavingQuoteTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh');

        App::bind(QuoteProgressValidator::class, function () {
            $validator = Mockery::mock(QuoteProgressValidator::class);
            $validator->shouldReceive('rules')->andReturn([]);
            return $validator;
        });
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
