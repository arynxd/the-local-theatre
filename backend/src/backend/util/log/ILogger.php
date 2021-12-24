<?php

namespace TLT\Util\Log;

interface ILogger {
    /**
     * Sets a LogLevel for this logger
     *
     * @param int $newLevel
     * @see LogLevel
     */
    public function setLevel($newLevel);

    /**
     * Sets the log file path for this application
     *
     * @param string $filePath The file path
     */
    public function setLogFile($filePath);
    /**
     * Returns the current log file path
     *
     * @return string
     * @throws AssertionException If the path is not set
     *
     */
    public function getLogFile();

    /**
     * Whether we should include log call locations in the logs
     * By default, this will enable when the level is DEBUG, disabled otherwise
     *
     * @param bool $includeLoc
     */
    public function setIncludeLoc($includeLoc);

    /**
     * Enables PHP error reporting
     */
    public function enablePHPErrors();

    /**
     * Inserts a new line into the logs
     *
     * @return void
     */
    public function insertNewLine();

    /**
     * Logs a fatal error, then close the application
     *
     * @param string|Exception $message
     * @return no-return
     */
    public function fatal($message);

    /**
     * Logs an error message
     *
     * @param string|Exception $message The message / exception to log
     */
    public function error($message);

    /**
     * Logs a warning
     *
     * @param string $message
     */
    public function warn($message);

    /**
     * Logs an info message
     *
     * @param string $message
     */
    public function info($message);

    /**
     * Logs a debug message
     *
     * @param string $message
     */
    public function debug($message);
}
