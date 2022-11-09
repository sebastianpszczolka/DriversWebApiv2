<?php

declare(strict_types=1);

namespace App\Loggers;

use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;


class DefaultLogger implements LoggerInterface
{
    public function emergency($message, array $context = []): void
    {
        Log::emergency($message, $context);
    }

    public function alert($message, array $context = []): void
    {
        Log::alert($message, $context);
    }

    public function critical($message, array $context = []): void
    {
        Log::critical($message, $context);
    }

    public function error($message, array $context = []): void
    {
        Log::error($message, $context);
    }

    public function warning($message, array $context = []): void
    {
        Log::warning($message, $context);
    }

    public function notice($message, array $context = []): void
    {
        Log::notice($message, $context);
    }

    public function info($message, array $context = []): void
    {
        Log::info($message, $context);
    }

    public function debug($message, array $context = []): void
    {
        Log::debug($message, $context);
    }

    public function log($level, $message, array $context = []): void
    {
        Log::log($message, $level, $context);
    }
}
