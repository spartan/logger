<?php

namespace Spartan\Logger\Formatter;

use Monolog\Formatter\FormatterInterface;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class Whoops implements FormatterInterface
{
    /**
     * @param mixed[] $record
     *
     * @return mixed|string
     */
    public function format(array $record)
    {
        if (isset($record['context']['exception'])) {
            $whoops = new Run();
            $whoops->writeToOutput(false);
            $whoops->allowQuit(false);
            $handler = php_sapi_name() == 'cli' ? new PlainTextHandler() : new PrettyPageHandler();
            $whoops->pushHandler($handler);

            return $whoops->handleException($record['context']['exception']);
        }

        return $record['message'];
    }

    /**
     * Formats a set of log records.
     *
     * @param mixed[] $records A set of records to format
     *
     * @return mixed The formatted set of records
     */
    public function formatBatch(array $records)
    {
        $formatted = [];

        foreach ($records as $record) {
            $formatted[] = $this->format($record);
        }

        return $formatted;
    }
}
