<?php

namespace Jhonoryza\DatabaseLogger\Logging;

use Jhonoryza\DatabaseLogger\Models\LogApp;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;

class LogHandler extends AbstractProcessingHandler
{
    private $connection;

    public function __construct($connection = null, $level = Level::Debug, $bubble = true)
    {
        $this->connection = $connection ?: config('database.default');
        parent::__construct($level, $bubble);
    }

    protected function write(LogRecord $record): void
    {
        if ($record) {
            $log = new LogApp;
            $log->setConnection($this->connection);
            $log->create([
                'message' => $record->message,
                'context' => ! empty($record->context) ? json_encode($record->context, JSON_FORCE_OBJECT) : null,
                'level' => $record->level->value,
                'level_name' => $record->level->getName(),
                'channel' => $record->channel,
                'record_datetime' => $record->datetime ? $record->datetime : null,
                'extra' => ! empty($record->extra) ? json_encode($record['extra']) : null,
                'formatted' => $record->formatted,
            ]);
        }

    }
}
