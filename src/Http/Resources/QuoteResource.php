<?php

namespace Jauntin\SavingQuote\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Jauntin\SavingQuote\Models\QuoteProgress;

/**
 * @mixin QuoteProgress
 */
class QuoteResource extends JsonResource
{
    /**
     * @param  Request|null  $request
     * @return array<string, mixed>
     */
    public function toArray($request = null): array
    {
        return [
            'email' => $this->email,
            'data' => $this->data,
        ];
    }
}
