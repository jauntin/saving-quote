<?php

namespace Tests\Feature\Http;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Jauntin\SavingQuote\Http\RouteNames;
use Jauntin\SavingQuote\Models\QuoteProgress;
use Tests\SavingQuoteTestCase;

class QuoteProgressControllerTest extends SavingQuoteTestCase
{
    public function test_get_not_expired_quote_progress(): void
    {
        $quoteProgress = $this->createQuoteProgress();
        $this->getJson(route(RouteNames::GET_QUOTE_PROGRESS, ['hash' => $quoteProgress->id], false))
            ->assertStatus(200)
            ->assertJson([
                'email' => $quoteProgress->email,
            ]);
    }

    public function test_get_expired_quote_progress(): void
    {
        $quoteProgress = $this->createQuoteProgress(['expire_at' => Carbon::now()->subWeek()]);
        $this->getJson(route(RouteNames::GET_QUOTE_PROGRESS, ['hash' => $quoteProgress->id], false))
            ->assertStatus(404);
    }

    public function test_get_not_exist_quote_progress(): void
    {
        $this->getJson(route(RouteNames::GET_QUOTE_PROGRESS, ['hash' => 'test'], false))
            ->assertStatus(404);
    }

    public function test_throw_exception_when_saving_quote_progress(): void
    {
        Validator::shouldReceive('make')->andThrow(new \Exception('test'));
        $quoteProgress = $this->createQuoteProgress();
        $this->getJson(route(RouteNames::GET_QUOTE_PROGRESS, ['hash' => $quoteProgress->id], false))
            ->assertStatus(400);
    }

    public function test_not_valid_saved_data(): void
    {
        $quoteProgress = $this->createQuoteProgress(['data' => ['averageDailyAttendance' => '200']]);
        $this->getJson(route(RouteNames::GET_QUOTE_PROGRESS, ['hash' => $quoteProgress->id], false))
            ->assertStatus(422);
    }

    public function test_create_quote_progress(): void
    {
        $this->postJson(route(RouteNames::CREATE_QUOTE_PROGRESS, [
            'email' => 'daryna@jauntin.com',
            'data' => ['excludedActivities' => true, 'averageDailyAttendance' => 40],
        ], false), ['Accept' => 'application/json'])
            ->assertStatus(201);
    }

    public function test_create_quote_progress_with_not_valid_email(): void
    {
        $this->postJson(route(RouteNames::CREATE_QUOTE_PROGRESS, [
            'email' => 'daryna',
            'data' => ['key' => 'value'],
        ], false), ['Accept' => 'application/json'])
            ->assertStatus(422);
    }

    public function test_create_quote_progress_with_empty_payload(): void
    {
        $this->postJson(route(RouteNames::CREATE_QUOTE_PROGRESS, [], false), ['Accept' => 'application/json'])
            ->assertStatus(422);
    }

    private function createQuoteProgress($data = []): QuoteProgress
    {
        $data = [
            ...[
                'email' => 'daryna@jauntin.com',
                'data' => ['averageDailyAttendance' => '50', 'excludedActivities' => true],
                'expire_at' => Carbon::now()->addWeek(),
                'hash' => Str::uuid()->toString(),
            ],
            ...$data,
        ];

        $quoteProgress = new QuoteProgress($data);
        $quoteProgress->save();

        return $quoteProgress;
    }
}
