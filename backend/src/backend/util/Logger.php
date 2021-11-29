<?php

namespace TLT\Util;

class Logger {
    public static function enableErrors() { // this is static because we always want to turn this on, regardless of other application state
        // not ideal, but better than unlogged errors.
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
}