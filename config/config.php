<?php

return [
    'expire' => [
        'unit' => env('SAVING_QUOTE_EXPIRE_UNIT', 'day'),
        'value' => env('SAVING_QUOTE_EXPIRE_VALUE', 7),
        'grace_period' => env('SAVING_QUOTE_EXPIRE_GRACE_PERIOD', 0),
    ],
    'max_additional_emails' => env('SAVING_QUOTE_MAX_ADDITIONAL_EMAILS', 4),
    'mailable' => null, // Class name implementing QuoteProgressAwareMailable to send after a quote is saved.
    'validator' => null, // Class name implementing QuoteProgressValidationRules.
];
