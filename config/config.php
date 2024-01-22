<?php

return [
    'expire' => [
        'unit' => env('SAVING_QUOTE_EXPIRE_UNIT', 'day'),
        'value' => env('SAVING_QUOTE_EXPIRE_VALUE', 7),
        'grace_period' => env('SAVING_QUOTE_EXPIRE_GRACE_PERIOD', 0),
    ],
    'mailable' => null, // Class name of the mailable to send after a quote is saved.
    'validator' => null,
];
