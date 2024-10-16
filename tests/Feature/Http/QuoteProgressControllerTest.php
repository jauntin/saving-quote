<?php

namespace Tests\Feature\Http;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Jauntin\SavingQuote\Http\RouteNames;
use Jauntin\SavingQuote\Models\QuoteProgress;
use Tests\SavingQuoteTestCase;

class QuoteProgressControllerTest extends SavingQuoteTestCase
{
    public function testGetNotExpiredQuoteProgress(): void
    {
        $quoteProgress = $this->createQuoteProgress();

        $response = $this->getJson(route(RouteNames::GET_QUOTE_PROGRESS, ['hash' => $quoteProgress->id], false));
        $response->assertStatus(200);
        $response->assertJson([
            'email' => $quoteProgress->email,
        ]);
    }

    public function testGetExpiredQuoteProgress(): void
    {
        $quoteProgress = $this->createQuoteProgress();

        $quoteProgress->expire_at = Carbon::now()->subWeek();
        $quoteProgress->save();

        $this->getJson(route(RouteNames::GET_QUOTE_PROGRESS, ['hash' => $quoteProgress->id], false))
            ->assertStatus(404);
    }

    public function testGetNotExistQuoteProgress(): void
    {
        $this->getJson(route(RouteNames::GET_QUOTE_PROGRESS, ['hash' => 'test'], false))
            ->assertStatus(404);
    }

    public function testCreateQuoteProgress(): void
    {
        $body = [
            'email' => 'daryna@jauntin.com',
            'data' => [
                'excludedActivities' => true,
                'averageDailyAttendance' => 40,
            ],
        ];

        $this->postJson(route(RouteNames::CREATE_QUOTE_PROGRESS, $body, false), ['Accept' => 'application/json'])
            ->assertStatus(201);
    }

    public function testCreateQuoteProgressWithNotValidEmail(): void
    {
        $body = [
            'email' => 'daryna',
            'data' => [
                'key' => 'value',
            ],
        ];

        $this->postJson(route(RouteNames::CREATE_QUOTE_PROGRESS, $body, false), ['Accept' => 'application/json'])
            ->assertStatus(422);
    }

    public function testCreateQuoteProgressWithEmptyPayload(): void
    {
        $this->postJson(route(RouteNames::CREATE_QUOTE_PROGRESS, [], false), ['Accept' => 'application/json'])
            ->assertStatus(422);
    }

    public function testNotValidSavedData(): void
    {
        $quoteProgress = $this->createQuoteProgress();

        $quoteProgress->data = ['averageDailyAttendance' => '200'];
        $quoteProgress->save();

        $this->getJson(route(RouteNames::GET_QUOTE_PROGRESS, ['hash' => $quoteProgress->id], false))
            ->assertStatus(422);
    }

    private function createQuoteProgress(): QuoteProgress
    {
        $data = [
            'email' => 'daryna@jauntin.com',
            'data' => [
                'averageDailyAttendance' => '50',
                'excludedActivities' => true,
            ],
            'expire_at' => Carbon::now()->addWeek(),
            'hash' => Str::uuid()->toString(),
        ];

        $quoteProgress = new QuoteProgress($data);
        $quoteProgress->save();

        return $quoteProgress;
    }
}
