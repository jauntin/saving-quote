<?php

namespace Jauntin\SavingQuote\Interfaces;

interface QuoteProgressValidator
{
    public function setRules(array $rules): void;

    public function validate(array $data): array;
}
