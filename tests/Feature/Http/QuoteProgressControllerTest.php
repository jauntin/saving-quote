<?php

namespace Tests\Feature\Http;

use Jauntin\SavingQuote\Http\RouteNames;
use Jauntin\SavingQuote\Models\QuoteProgress;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

class QuoteProgressControllerTest extends TestCase
{
    public function test_get_not_expired_quote_progress()
    {
        $quoteProgress = $this->createQuoteProgress();

        $response = $this->getJson(route(RouteNames::GET_QUOTE_PROGRESS, ['hash' => $quoteProgress->hash], false));
        $response->assertStatus(200);
        $response->assertJson([
            'email' => $quoteProgress->email,
        ]);
    }

    public function test_get_expired_quote_progress()
    {
        $quoteProgress = $this->createQuoteProgress();

        $quoteProgress->expire_at = Carbon::now()->subWeek();
        $quoteProgress->save();

        $this->getJson(route(RouteNames::GET_QUOTE_PROGRESS, ['hash' => $quoteProgress->hash], false))
            ->assertStatus(404);
    }

    public function test_get_not_exist_quote_progress()
    {
        $this->getJson(route(RouteNames::GET_QUOTE_PROGRESS, ['hash' => 'test'], false))
            ->assertStatus(404);
    }

    public function test_create_quote_progress()
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

    public function test_create_quote_progress_with_not_valid_email()
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

    public function test_create_quote_progress_with_empty_payload()
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
