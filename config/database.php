<?php

use Illuminate\Database\DBAL\TimestampType;

return [
    'dbal' => [
        'types' => [
            'timestamp' => TimestampType::class,
        ],
    ],
];
