<?php

namespace Jhonoryza\DatabaseLogger\Logging;

use Monolog\Logger;

class DatabaseLogger
{
    /**
     * Create a custom Monolog instance.
     */
    public function __invoke(array $config): Logger
    {
        $connection = $config['connection'] ?? config('database.default');

        $level = Logger::toMonologLevel($config['level'] ?? 'debug');

        $logger = new Logger('log_apps');

        $logger->pushHandler(new LogHandler($connection, $level));

        return $logger;
    }
}
