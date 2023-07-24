<?php

namespace Jauntin\SavingQuote\Interfaces;

interface QuoteProgressValidator
{
    public function rules(): void;

    public function validate(array $data): array;
}
