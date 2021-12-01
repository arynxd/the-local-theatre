<?php

namespace TLT\Util\Log;

use Exception;
use TLT\Util\Enum\LogLevel;

class Logger {
    private function __construct() {
        // Private constructor, this is a singleton object
    }

    private static $INSTANCE = null;

    public static function getInstance() {
        if (!isset(self::$INSTANCE)) {
            self::$INSTANCE = new Logger();
        }

        return self::$INSTANCE;
    }

    private $level = LogLevel::WARN;

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
     * Enables PHP error reporting
     */
    public function enableErrors() {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    private function shouldLog($atLevel) {
        return $this -> level >= $atLevel;
    }

    private function doLog($level, $levelString, $message) {
        if (!$this -> shouldLog($level))
            return;

        error_log("[$levelString] :: $message");
    }

    /**
     * Logs a fatal error, then closes the application
     *
     * @param $message
     * @param $exitCode
     * @return no-return
     */
    public function fatal($message, $exitCode = 1) {
        $this -> doLog(LogLevel::FATAL, "FATAL", "The application has encountered a fatal error..");
        $this -> doLog(LogLevel::FATAL, "FATAL", $message);
        die($exitCode);
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