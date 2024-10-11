<?php

namespace Jauntin\SavingQuote\Service;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Jauntin\SavingQuote\Interfaces\QuoteProgressAwareMailable;
use Jauntin\SavingQuote\Interfaces\QuoteProgressValidationRules;
use Jauntin\SavingQuote\Models\QuoteProgress;

class QuoteProgressService
{
    /** @var array<string, string> */
    private array $data;

    private QuoteProgressAwareMailable $mailable;

    public function __construct(private readonly string $expireUnit, private readonly int $expireValue) {}

    /**
     * @param QuoteProgressValidationRules $validator
     * @return array<string, array<int, string>>
     */
    public static function rules(QuoteProgressValidationRules $validator): array
    {
        return [
            'email' => ['required', 'email'],
            'data' => ['required', 'array'],
            ...$validator->rules(),
        ];
    }

    /**
     * @param  array<string, string>  $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function setMailable(QuoteProgressAwareMailable $mailable): void
    {
        $this->mailable = $mailable;
    }

    public function execute(): QuoteProgress
    {
        $this->data['expire_at'] = Carbon::now()->add($this->expireUnit, $this->expireValue);

        $quoteProgress = new QuoteProgress($this->data);
        $quoteProgress->save();

        if (isset($this->mailable)) {
            $this->mailQuoteProgress($quoteProgress);
        }

        return $quoteProgress;
    }

    public function markAsOpened(QuoteProgress $quote): QuoteProgress
    {
        $quote->opened_at = Carbon::now();
        $quote->save();

        return $quote;
    }

    private function mailQuoteProgress(QuoteProgress $quoteProgress): void
    {
        Mail::to($this->data['email'])->queue($this->mailable->setQuoteProgress($quoteProgress));
    }
}
