<?php

namespace Jauntin\SavingQuote\Service;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Jauntin\SavingQuote\Interfaces\QuoteProgressAwareMailable;
use Jauntin\SavingQuote\Models\QuoteProgress;

class QuoteProgressService
{
    public function __construct(private readonly string $expireUnit, private readonly int $expireValue, private ?QuoteProgressAwareMailable $mailable = null) {}

    /**
     * @param  array<array-key,string>  $data
     */
    public function create(array $data): QuoteProgress
    {
        $data['expire_at'] = Carbon::now()->add($this->expireUnit, $this->expireValue);

        $quoteProgress = new QuoteProgress($data);
        $quoteProgress->save();

        if (isset($this->mailable)) {
            Mail::to($data['email'])->queue($this->mailable->setQuoteProgress($quoteProgress));
        }

        return $quoteProgress;
    }

    public function markAsOpened(QuoteProgress $quote): QuoteProgress
    {
        $quote->opened_at = Carbon::now();
        $quote->save();

        return $quote;
    }
}
