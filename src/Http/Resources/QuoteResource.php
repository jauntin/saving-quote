<?php

namespace Jauntin\SavingQuote\Http\Resources;

use Jauntin\SavingQuote\Models\QuoteProgress;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin QuoteProgress
 */
class QuoteResource extends JsonResource
{
    /**
     * @param Request|null $request
     * @return array<string, mixed>
     */
    public function toArray($request = null): array
    {
        /** @var QuoteProgress $quoteProgress */
        $quoteProgress = $this->resource;

        return [
            'email' => $quoteProgress->email,
            'data'  => $quoteProgress->data,
        ];
    }
}
