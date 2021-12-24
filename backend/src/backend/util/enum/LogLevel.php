<?php

namespace TLT\Util\Enum;

class LogLevel {
    const DISABLED = 0;
    const FATAL = 1;
    const ERROR = 2;
    const WARN = 3;
    const INFO = 4;
    const DEBUG = 5;
    const ALL = 6;

    public static function LEVEL_TO_DISPLAY_NAME() {
        return [
            self::DISABLED => 'DISABLED',
            self::FATAL => 'FATAL',
            self::ERROR => 'ERROR',
            self::WARN => 'WARN',
            self::INFO => 'INFO',
            self::DEBUG => 'DEBUG',
            self::ALL => 'ALL',
        ];
    }

    public static function asDisplay($level) {
        return self::LEVEL_TO_DISPLAY_NAME()[$level];
    }
}
