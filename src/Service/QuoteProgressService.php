<?php

namespace Jauntin\SavingQuote\Service;

use Jauntin\SavingQuote\Models\QuoteProgress;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class QuoteProgressService
{
    private array $data;

    public static function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'data'  => ['required', 'array'],
        ];
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function execute(): QuoteProgress
    {
        $this->data['hash'] = Str::uuid()->toString();
        $this->data['expire_at'] = Carbon::now()->addWeek();

        $quoteProgress = new QuoteProgress($this->data);
        $quoteProgress->save();

        return $quoteProgress;
    }

    public function markAsOpened(QuoteProgress $quote): QuoteProgress
    {
        $quote->opened_at = Carbon::now();
        $quote->save();

        return $quote;
    }
}
