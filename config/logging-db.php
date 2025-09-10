<?php

use Jhonoryza\DatabaseLogger\Logging\DatabaseLogger;

return [
    'channels' => [
        'database' => [
            'driver' => 'custom',
            'via' => DatabaseLogger::class,
            'connection' => env('DB_CONNECTION_LOGGER', 'mysql-logger'),
            'level' => 'error',
        ],
    ],
];
