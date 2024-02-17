<?php

namespace SyslogLogger;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class Logger implements LoggerInterface
{
    public function __construct(string $prefix)
    {
        openlog($prefix, LOG_PID | LOG_PERROR, LOG_LOCAL0);
    }

    public function emergency(string|\Stringable $message, array $context = []): void
    {
        syslog(LOG_EMERG, $this->getMessage($message, $context));
    }

    public function alert(string|\Stringable $message, array $context = []): void
    {
        syslog(LOG_ALERT, $this->getMessage($message, $context));
    }

    public function critical(string|\Stringable $message, array $context = []): void
    {
        syslog(LOG_CRIT, $this->getMessage($message, $context));
    }

    public function error(string|\Stringable $message, array $context = []): void
    {
        syslog(LOG_ERR, $this->getMessage($message, $context));
    }

    public function warning(string|\Stringable $message, array $context = []): void
    {
        syslog(LOG_WARNING, $this->getMessage($message, $context));
    }

    public function notice(string|\Stringable $message, array $context = []): void
    {
        syslog(LOG_NOTICE, $this->getMessage($message, $context));
    }

    public function info(string|\Stringable $message, array $context = []): void
    {
        syslog(LOG_INFO, $this->getMessage($message, $context));
    }

    public function debug(string|\Stringable $message, array $context = []): void
    {
        syslog(LOG_DEBUG, $this->getMessage($message, $context));
    }

    public function log($level, string|\Stringable $message, array $context = []): void
    {
        syslog(
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
            $this->getMessage($message, $context)
        );
    }

    private function getMessage(string|\Stringable $message, array $context): string
    {
        return empty($context) ? $message : $message . ' ' . json_encode($context, JSON_UNESCAPED_UNICODE);
    }
}
