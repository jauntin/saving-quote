<?php

namespace Tests\Unit\Service;

use Carbon\Carbon;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Jauntin\SavingQuote\Interfaces\QuoteProgressAwareMailable;
use Jauntin\SavingQuote\Models\QuoteProgress;
use Jauntin\SavingQuote\Service\QuoteProgressService;
use Mockery;
use Tests\SavingQuoteTestCase;

class QuoteProgressServiceTest extends SavingQuoteTestCase
{
    /** @var Mailable&QuoteProgressAwareMailable */
    private $mailable;

    private QuoteProgressService $service;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        $this->mailable = Mockery::mock(Mailable::class, QuoteProgressAwareMailable::class)->makePartial();
        $this->mailable->shouldReceive('setQuoteProgress')->andReturnSelf();
        $this->service = new QuoteProgressService('week', 1, $this->mailable);
    }

    public function test_create_saves_a_quote_progress_that_expires_in_the_future(): void
    {
        $data = ['email' => 'test@example.com', 'data' => ['key' => 'value']];

        $quoteProgress = $this->service->create($data);

        $this->assertInstanceOf(QuoteProgress::class, $quoteProgress);
        $this->assertTrue($quoteProgress->exists);
        $this->assertEquals('test@example.com', $quoteProgress->email);
        $this->assertEquals(['key' => 'value'], $quoteProgress->data);
        $this->assertNull($quoteProgress->additional_emails);
        $this->assertGreaterThan(Carbon::now(), $quoteProgress->expire_at);
    }

    public function test_create_queues_mail_to_the_primary_email_only(): void
    {
        $data = ['email' => 'test@example.com', 'data' => ['key' => 'value']];

        $this->service->create($data);

        Mail::assertQueued(fn (Mailable $mail) => $mail->hasTo('test@example.com')
            && count($mail->to) === 1
            && $mail->cc === []
            && $mail->bcc === []);
        Mail::assertQueuedCount(1);
    }

    public function test_create_queues_mail_to_the_primary_and_additional_emails(): void
    {
        $data = [
            'email' => 'test@example.com',
            'additionalEmails' => ['second@example.com', 'third@example.com'],
            'data' => ['key' => 'value'],
        ];

        $quoteProgress = $this->service->create($data);

        $this->assertEquals(['second@example.com', 'third@example.com'], $quoteProgress->additional_emails);
        Mail::assertQueued(fn (Mailable $mail) => $mail->hasTo('test@example.com')
            && $mail->hasTo('second@example.com')
            && $mail->hasTo('third@example.com')
            && count($mail->to) === 3
            && $mail->cc === []
            && $mail->bcc === []);
        Mail::assertQueuedCount(1);
    }

    public function test_create_without_a_mailable_does_not_send_mail(): void
    {
        $service = new QuoteProgressService('week', 1);
        $data = ['email' => 'test@example.com', 'data' => ['key' => 'value']];

        $quoteProgress = $service->create($data);

        $this->assertInstanceOf(QuoteProgress::class, $quoteProgress);
        Mail::assertNothingQueued();
    }

    public function test_mark_as_opened_sets_the_opened_at_timestamp(): void
    {
        $quoteProgress = new QuoteProgress([
            'email' => 'test@example.com',
            'data' => ['key' => 'value'],
            'expire_at' => Carbon::now()->addWeek(),
        ]);
        $quoteProgress->save();

        $updatedQuote = $this->service->markAsOpened($quoteProgress);

        $this->assertEquals($quoteProgress->id, $updatedQuote->id);
        $this->assertInstanceOf(Carbon::class, $updatedQuote->opened_at);
        $this->assertNotNull($updatedQuote->fresh()->opened_at);
    }
}
