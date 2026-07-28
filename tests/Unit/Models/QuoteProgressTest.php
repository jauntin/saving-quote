<?php

namespace Tests\Unit\Models;

use Illuminate\Support\Carbon;
use Jauntin\SavingQuote\Models\QuoteProgress;
use Tests\SavingQuoteTestCase;

class QuoteProgressTest extends SavingQuoteTestCase
{
    public function test_additional_emails_is_cast_to_array_and_nullable(): void
    {
        $quoteProgress = new QuoteProgress([
            'email' => 'primary@example.com',
            'data' => ['key' => 'value'],
            'expire_at' => Carbon::now()->addWeek(),
            'additional_emails' => ['second@example.com', 'third@example.com'],
        ]);
        $quoteProgress->save();

        $fresh = QuoteProgress::find($quoteProgress->id);

        $this->assertSame(['second@example.com', 'third@example.com'], $fresh->additional_emails);
    }

    public function test_additional_emails_defaults_to_null_when_omitted(): void
    {
        $quoteProgress = new QuoteProgress([
            'email' => 'primary@example.com',
            'data' => ['key' => 'value'],
            'expire_at' => Carbon::now()->addWeek(),
        ]);
        $quoteProgress->save();

        $fresh = QuoteProgress::find($quoteProgress->id);

        $this->assertNull($fresh->additional_emails);
    }
}
