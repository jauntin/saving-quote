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
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): QuoteProgress
    {
        $additionalEmails = $data['additionalEmails'] ?? null;
        if (! is_array($additionalEmails)) {
            $additionalEmails = null;
        }

        $data['expire_at'] = Carbon::now()->add($this->expireUnit, $this->expireValue);
        $data['additional_emails'] = $additionalEmails;

        $quoteProgress = new QuoteProgress($data);
        $quoteProgress->save();

        if (isset($this->mailable)) {
            $recipients = array_merge([$data['email']], $additionalEmails ?? []);
            Mail::to($recipients)->queue($this->mailable->setQuoteProgress($quoteProgress));
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
