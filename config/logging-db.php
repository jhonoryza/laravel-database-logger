<?php

use Jhonoryza\DatabaseLogger\Logging\DatabaseLogger;

return [
    'driver' => 'custom',
    'via' => DatabaseLogger::class,
    'connection' => env('DB_CONNECTION_LOGGER', 'pgsql'),
    'level' => 'error',
];
