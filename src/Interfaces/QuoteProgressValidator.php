<?php

namespace Jauntin\SavingQuote\Interfaces;

interface QuoteProgressValidator
{
    public function rules(array $data): array;

    public function validate(): bool;
}
