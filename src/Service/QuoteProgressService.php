<?php

namespace Jauntin\SavingQuote\Service;

use Jauntin\SavingQuote\Models\QuoteProgress;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class QuoteProgressService
{
    /** @var array<string, string> $data */
    private array $data;

    /**
     * @return array<string, array<int, string>>
     */
    public static function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'data'  => ['required', 'array'],
        ];
    }

    /**
     * @param array<string, string> $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function execute(): QuoteProgress
    {
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
