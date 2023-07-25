<?php

return [
    'expire' => [
        'unit' => env('SAVING_QUOTE_EXPIRE_UNIT', 'day'),
        'value' => env('SAVING_QUOTE_EXPIRE_VALUE', 7),
    ],
    'mailable' => null, // Class name of the mailable to send after a quote is saved.
    'validator' => null,
];
