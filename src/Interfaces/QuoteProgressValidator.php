<?php

namespace Jauntin\SavingQuote\Interfaces;

interface QuoteProgressValidator
{
    /**
     * @param array<string, string|array|mixed> $data
     * @return array<string, mixed>
     */
    public function rules(array $data): array;
}
