<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Jauntin\SavingQuote\Interfaces\QuoteProgressValidationRules;
use Jauntin\SavingQuote\SavingQuoteServiceProvider;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

abstract class SavingQuoteTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh');

        $this->mock(QuoteProgressValidationRules::class, function (MockInterface $mock) {
            $mock->shouldReceive('rules')->andReturn([
                'averageDailyAttendance' => ['required', 'integer', 'min:1', 'max:100']
            ]);
            $mock->shouldReceive('transformData')->andReturnArg(0);
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
