<?php

namespace Jauntin\SavingQuote\Interfaces;

use Illuminate\Contracts\Mail\Mailable;
use Jauntin\SavingQuote\Models\QuoteProgress;

interface QuoteProgressAwareMailable extends Mailable
{
    public function setQuoteProgress(QuoteProgress $quoteProgress): self;
}
