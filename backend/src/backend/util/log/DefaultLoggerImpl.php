<?php

namespace TLT\Util\Log;

use Exception;
use RuntimeException;
use TLT\Request\Response;
use TLT\Util\ArrayUtil;
use TLT\Util\Assert\AssertionException;
use TLT\Util\Assert\Assertions;
use TLT\Util\Data\Map;
use TLT\Util\Enum\LogLevel;

class DefaultLoggerImpl {
	private $level;
	private $includeLoc;

	public function __construct() {
		$this->level = LogLevel::INFO;
		$this->includeLoc = false;
		ini_set('date.timezone', 'UTC');
	}

	/**
	 * @inheritDoc
	 */
	public function setLevel($newLevel) {
		$this->level = $newLevel;
	}

	/**
	 * @inheritDoc
	 */
	public function setLogFile($filePath) {
		ini_set('error_log', $filePath);
	}

	/**
	 * @inheritDoc
	 */
	public function getLogFile() {
		$path = ini_get('error_log');
		Assertions::assertNotFalse($path);
		return $path;
	}

	/**
	 * @inheritDoc
	 */
	public function setIncludeLoc($includeLoc) {
		$this->includeLoc = $includeLoc;
	}

	/**
	 * @inheritDoc
	 */
	public function enablePHPErrors() {
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);

		$errorHandler = function ($errno, $errstr, $errfile, $errline) {
			(new Response())->internal($errstr);
		};

		$exceptionHandler = function ($throwable) {
			$this->fatal($throwable);
		};

		set_error_handler($errorHandler);
		set_exception_handler($exceptionHandler);
	}

	/**
	 * @inheritDoc
	 */
	public function insertNewLine() {
		// if logging is enabled
		if ($this->shouldLog(LogLevel::DEBUG)) {
			$f = fopen($this->getLogFile(), 'a');
			Assertions::assertNotFalse($f);
			fwrite($f, PHP_EOL);
			fclose($f);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function fatal($message) {
		if (is_a('Throwable', $message)) {
			$message = 'An error has occurred ' . $message->getMessage();
		}

		$this->doLog(
			LogLevel::FATAL,
			'The application has encountered a fatal error..'
		);
		
		$this -> doLog(LogLevel::FATAL, new RuntimeException($message))
		(new Response())->internal();
	}

	private function doLog($level, $message) {
		if (!$this->shouldLog($level)) {
			return;
		}

		$displayString = LogLevel::asDisplay($level);
		$m = "[$displayString] ";

		if ($this->includeLoc) {
			// walk the stack to find where the log was called from
			// walk 2 levels, since doLog is called by this class internally
			$stack = Map::from(
				debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2)
			)->toMapRecursive();

			if ($stack->length() < 2) {
				$this->error(
					'Could not get location for log output, stack was empty'
				);
			}

			$stack = $stack[1];

			$file = join(
				'/', // take the last section of the path
				ArrayUtil::arraySliceBackward(explode('/', $stack['file']), 3)
			);

			$m .= "@ $file {{$stack['line']}} ";
		}

		$m .= ":: $message";

		error_log($m);
	}

	private function shouldLog($atLevel) {
		return $this->level >= $atLevel;
	}

	/**
	 * @inheritDoc
	 */
	public function error($message) {
		if (is_a('Exception', $message)) {
			$message = 'An error has occurred ' . $message->getMessage();
		}
		$this->doLog(LogLevel::ERROR, $message);
	}

	/**
	 * @inheritDoc
	 */
	public function warn($message) {
		$this->doLog(LogLevel::WARN, $message);
	}

	/**
	 * @inheritDoc
	 */
	public function info($message) {
		$this->doLog(LogLevel::INFO, $message);
	}

	/**
	 * @inheritDoc
	 */
	public function debug($message) {
		$this->doLog(LogLevel::DEBUG, $message);
	}
}
