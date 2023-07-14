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
        return [
            'email' => $this->email,
            'data'  => $this->data,
            'state' => $this->state, // TODO: consider removing
        ];
    }
}
