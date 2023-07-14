<?php

return [
    'expire' => [
        'unit' => env('SAVING_QUOTE_EXPIRE_UNIT', 'day'),
        'value' => env('SAVING_QUOTE_EXPIRE_VALUE', 7),
    ],
    'mailable' => env('SAVING_QUOTE_MAILABLE_NAME'), // Class name of the mailable to send after a quote is saved.
];
