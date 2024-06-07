<?php

namespace Jauntin\SavingQuote\Interfaces;

interface QuoteProgressValidationRules
{
    /**
     * Provides validation rules
     *
     * @param  array<string, string|array|mixed>  $data  Input data to be validated. Rules may be "data aware"
     * @return array<string, mixed>
     */
    public function rules(array $data = []): array;

    /**
     * Takes data stored for Quote Progress validation and returns the data to be validated
     *
     * @param  array<string, array|mixed>  $data  Full data stored by quote progress
     * @return array<string, array|mixed>
     */
    public function transformData(array $data): array;
}
