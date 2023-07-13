<?php

namespace Tests\Feature\Http;

use Jauntin\SavingQuote\Http\RouteNames;
use Jauntin\SavingQuote\Models\QuoteProgress;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Tests\SavingQuoteTestCase;

class QuoteProgressControllerTest extends SavingQuoteTestCase
{
    public function testGetNotExpiredQuoteProgress()
    {
        $quoteProgress = $this->createQuoteProgress();

        $response = $this->getJson(route(RouteNames::GET_QUOTE_PROGRESS, ['hash' => $quoteProgress->hash], false));
        $response->assertStatus(200);
        $response->assertJson([
            'email' => $quoteProgress->email,
        ]);
    }

    public function testGetExpiredQuoteProgress()
    {
        $quoteProgress = $this->createQuoteProgress();

        $quoteProgress->expire_at = Carbon::now()->subWeek();
        $quoteProgress->save();

        $this->getJson(route(RouteNames::GET_QUOTE_PROGRESS, ['hash' => $quoteProgress->hash], false))
            ->assertStatus(404);
    }

    public function testGetNotExistQuoteProgress()
    {
        $this->getJson(route(RouteNames::GET_QUOTE_PROGRESS, ['hash' => 'test'], false))
            ->assertStatus(404);
    }

    public function testCreateQuoteProgress()
    {
        $body = [
            'email' => 'daryna@jauntin.com',
            'data' => [
                'key' => 'value'
            ]
        ];

        $this->postJson(route(RouteNames::CREATE_QUOTE_PROGRESS, $body, false), ['Accept' => 'application/json'])
            ->assertStatus(201);
    }

    public function testCreateQuoteProgressWithNotValidEmail()
    {
        $body = [
            'email' => 'daryna',
            'data' => [
                'key' => 'value'
            ]
        ];

        $this->postJson(route(RouteNames::CREATE_QUOTE_PROGRESS, $body, false), ['Accept' => 'application/json'])
            ->assertStatus(422);
    }

    public function testCreateQuoteProgressWithEmptyPayload()
    {
        $this->postJson(route(RouteNames::CREATE_QUOTE_PROGRESS, [], false), ['Accept' => 'application/json'])
            ->assertStatus(422);
    }

    private function createQuoteProgress(): QuoteProgress
    {
        $data = [
            'email' => 'daryna@jauntin.com',
            'data' => [
                'key' => 'value'
            ],
            'expire_at' => Carbon::now()->addWeek(),
            'hash' => Str::uuid()->toString(),
        ];

        $quoteProgress = new QuoteProgress($data);
        $quoteProgress->save();

        return $quoteProgress;
    }
}
