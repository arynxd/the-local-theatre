<?php

namespace TLT\Util\Log;

use TLT\Util\Log\DefaultLoggerImpl;

class Logger {
    private static $INSTANCE = null;

    /**
     * Gets the current logger instance
     *
     * @return ILogger The logger instance
     */
    public static function getInstance() {
        if (!isset(self::$INSTANCE)) {
            self::$INSTANCE = new DefaultLoggerImpl();
        }

        return self::$INSTANCE;
    }
}
