<?php

namespace TLT\Util\Log;

use Exception;
use TLT\Request\Response;
use TLT\Util\ArrayUtil;
use TLT\Util\Assert\AssertionException;
use TLT\Util\Assert\Assertions;
use TLT\Util\Data\Map;
use TLT\Util\Enum\LogLevel;

class Logger {
    private static $INSTANCE = null;
    private $level;
    private $includeLoc;

    private function __construct() {
        // Private constructor, this is a singleton object
        $this -> level = LogLevel::WARN;
        $this -> includeLoc = $this -> level <= LogLevel::DEBUG;
    }

    public static function getInstance() {
        if (!isset(self::$INSTANCE)) {
            self::$INSTANCE = new Logger();
        }

        return self::$INSTANCE;
    }

    /**
     * Sets a LogLevel for this logger
     *
     * @param int $newLevel
     * @see LogLevel
     */
    public function setLevel($newLevel) {
        $this -> level = $newLevel;
    }

    /**
     * Sets the log file path for this application
     *
     * @param string $filePath The file path
     */
    public function setLogFile($filePath) {
        ini_set("error_log", $filePath);
    }

    /**
     * Returns the current log file path
     *
     * @return string
     * @throws AssertionException If the path is not set
     *
     */
    public function getLogFile() {
        $path = ini_get("error_log");
        Assertions::assertNotFalse($path);
        return $path;
    }

    /**
     * Whether we should include log call locations in the logs
     * By default, this will enable when the level is DEBUG, disabled otherwise
     *
     * @param bool $includeLoc
     */
    public function setIncludeLoc($includeLoc) {
        $this -> includeLoc = $includeLoc;
    }

    /**
     * Enables PHP error reporting
     */
    public function enablePHPErrors() {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    /**
     * Logs a fatal error, then closes the application
     *
     * @param string|Exception $message
     * @return no-return
     */
    public function fatal($message) {
        if (is_a(Exception::class, $message)) {
            $message = "An error has occurred " . $message -> getMessage();
        }

        $this -> doLog(LogLevel::FATAL, "FATAL", "The application has encountered a fatal error..");
        $this -> doLog(LogLevel::FATAL, "FATAL", $message);
        (new Response()) -> sendInternalError();
    }

    private function doLog($level, $levelString, $message) {
        if (!$this -> shouldLog($level)) {
            return;
        }

        $m = "[$levelString] ";

        if ($this -> includeLoc) {
            // walk the stack to find where the log was called from
            // walk 2 levels, since doLog is called by this class internally
            $stack = Map ::from(
                debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2)
            ) -> toMapRecursive();

            if ($stack -> length() < 2) {
                $this -> error("Could not get location for log output, stack was empty");
            }


            $stack = $stack[1];

            $file = join("/", // take the last section of the path
                ArrayUtil ::arraySliceBackward(
                    explode("/", $stack['file']), 3
                )
            );

            $m .= "@ $file {{$stack['line']}} ";

        }

        $m .= ":: $message";

        error_log($m);
    }

    private function shouldLog($atLevel) {
        return $this -> level >= $atLevel;
    }

    /**
     * Logs an error message
     *
     * @param string|Exception $message The message / exception to log
     */
    public function error($message) {
        if (is_a(Exception::class, $message)) {
            $message = "An error has occurred " . $message -> getMessage();
        }
        $this -> doLog(LogLevel::ERROR, "ERROR", $message);
    }

    /**
     * Logs a warning
     *
     * @param string $message
     */
    public function warn($message) {
        $this -> doLog(LogLevel::WARN, "WARN", $message);
    }

    /**
     * Logs an info message
     *
     * @param string $message
     */
    public function info($message) {
        $this -> doLog(LogLevel::INFO, "INFO", $message);
    }

    /**
     * Logs a debug message
     *
     * @param string $message
     */
    public function debug($message) {
        $this -> doLog(LogLevel::DEBUG, "DEBUG", $message);
    }
}