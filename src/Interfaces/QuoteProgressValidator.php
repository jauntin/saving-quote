<?php

namespace Jauntin\SavingQuote\Interfaces;

interface QuoteProgressValidator
{
    /**
     * @param array<string, string|array|mixed> $data
     * @return array<int, array|mixed>
     */
    public function rules(array $data): array;

    /**
     * @param array<string, string|array|mixed> $data
     * @return array<int, array|mixed>|array{}
     */
    public function validate(array $data): array;
}
