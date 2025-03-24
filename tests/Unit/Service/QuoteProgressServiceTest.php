<?php

namespace Tests\Unit\Service;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Jauntin\SavingQuote\Interfaces\QuoteProgressAwareMailable;
use Jauntin\SavingQuote\Models\QuoteProgress;
use Jauntin\SavingQuote\Service\QuoteProgressService;
use Mockery\MockInterface;
use Tests\SavingQuoteTestCase;

class QuoteProgressServiceTest extends SavingQuoteTestCase
{
    private QuoteProgressService $service;

    private MockInterface&QuoteProgressAwareMailable $mailable;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mailable = $this->mock(QuoteProgressAwareMailable::class);
        $this->service = new QuoteProgressService('week', 1, $this->mailable);
    }

    public function test_create_quote_progress(): void
    {
        Mail::fake();
        Mail::shouldReceive('to')->once()->andReturnSelf();
        Mail::shouldReceive('queue')->once()->andReturnSelf();
        $data = ['email' => 'test@example.com', 'data' => ['key' => 'value']];
        $this->mailable->shouldReceive('setQuoteProgress')->once()->andReturnSelf();

        $quoteProgress = $this->service->create($data);

        $this->assertInstanceOf(QuoteProgress::class, $quoteProgress);
        $this->assertEquals($data['email'], $quoteProgress->email);
        $this->assertEquals($data['data'], $quoteProgress->data);
        $this->assertGreaterThan(Carbon::now(), $quoteProgress->expire_at);
    }

    public function test_create_quote_progress_without_mailable(): void
    {
        Mail::fake();

        $service = new QuoteProgressService('week', 1);
        $data = ['email' => 'test@example.com', 'data' => ['key' => 'value']];

        $quoteProgress = $service->create($data);

        $this->assertInstanceOf(QuoteProgress::class, $quoteProgress);
        $this->assertEquals($data['email'], $quoteProgress->email);
        $this->assertEquals($data['data'], $quoteProgress->data);
        $this->assertGreaterThan(Carbon::now(), $quoteProgress->expire_at);

        Mail::assertNotQueued(QuoteProgressAwareMailable::class);
    }

    public function test_mark_as_opened(): void
    {
        $quoteProgress = new QuoteProgress([
            'email' => 'test@example.com',
            'data' => ['key' => 'value'],
            'expire_at' => Carbon::now()->addWeek(),
        ]);
        $quoteProgress->save();

        $updatedQuote = $this->service->markAsOpened($quoteProgress);

        $this->assertNotNull($updatedQuote->opened_at);
        $this->assertInstanceOf(Carbon::class, $updatedQuote->opened_at);
        $this->assertEquals($quoteProgress->id, $updatedQuote->id);
    }
}
