<?php

namespace Jauntin\SavingQuote\Interfaces;

interface QuoteProgressValidator
{
    public function rules(array $data): void;

    public function validate(array $data): bool;
}
