<?php

namespace SyslogLogger;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class Logger implements LoggerInterface
{
    public function __construct(string $prefix)
    {
        openlog($prefix, LOG_PID | LOG_ODELAY, LOG_USER);
    }

    public function emergency(string|\Stringable $message, array $context = []): void
    {
        $this->syslog(LOG_EMERG, $message, $context);
    }

    public function alert(string|\Stringable $message, array $context = []): void
    {
        $this->syslog(LOG_ALERT, $message, $context);
    }

    public function critical(string|\Stringable $message, array $context = []): void
    {
        $this->syslog(LOG_CRIT, $message, $context);
    }

    public function error(string|\Stringable $message, array $context = []): void
    {
        $this->syslog(LOG_ERR, $message, $context);
    }

    public function warning(string|\Stringable $message, array $context = []): void
    {
        $this->syslog(LOG_WARNING, $message, $context);
    }

    public function notice(string|\Stringable $message, array $context = []): void
    {
        $this->syslog(LOG_NOTICE, $message, $context);
    }

    public function info(string|\Stringable $message, array $context = []): void
    {
        $this->syslog(LOG_INFO, $message, $context);
    }

    public function debug(string|\Stringable $message, array $context = []): void
    {
        $this->syslog(LOG_DEBUG, $message, $context);
    }

    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $this->syslog(
            match ($level) {
                LogLevel::EMERGENCY => LOG_EMERG,
                LogLevel::ALERT => LOG_ALERT,
                LogLevel::CRITICAL => LOG_CRIT,
                LogLevel::ERROR => LOG_ERR,
                LogLevel::ALERT => LOG_ALERT,
                LogLevel::NOTICE => LOG_NOTICE,
                LogLevel::INFO => LOG_INFO,
                LogLevel::DEBUG => LOG_DEBUG
            },
            $message,
            $context
        );
    }

    private function syslog(int $priority, string $message, array $context): void
    {
        syslog($priority, empty($context) ? $message : $message . ' ' . json_encode($context, JSON_UNESCAPED_UNICODE));
    }
}
