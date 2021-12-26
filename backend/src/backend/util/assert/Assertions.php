<?php

namespace TLT\Util\Assert;

use TLT\Util\Log\Logger;

class Assertions {
	/**
	 * Asserts that $value is non-null
	 */
	public static function assertSet($value) {
		if (!isset($value)) {
			Logger::getInstance()->fatal(
				new AssertionException('Assertion failed, value was not set.')
			);
		}
		return $value;
	}

	/**
	 * Asserts that $value is of type $type
	 *
	 * @param mixed $value The value to check
	 * @param string $type The type to check
	 */
	public static function assertType($value, $type) {
		$err = function () use ($value, $type) {
			Logger::getInstance()->fatal(
				new AssertionException(
					"Assertion failed, value was not of type $type got " .
						get_class($value)
				)
			);
		};

		if ($type == 'array' && !is_array($value)) {
			$err();
		} elseif (!is_a($type, $value)) {
			$err();
		}
	}

	/**
	 * Asserts that $value is of type $type or null
	 *
	 * @param mixed $value The value to check
	 * @param string $type The type to check
	 */
	public static function assertTypeOrNull($value, $type) {
		if (!isset($value)) {
			return;
		}

		self::assertType($value, $type);
	}

	/**
	 * Asserts that $value is not falsy
	 * @param mixed $value
	 * @param string|null an error message
	 */
	public static function assertNotFalse($value) {
		if (!$value) {
			Logger::getInstance()->fatal(
				new AssertionException('Assertion failed, value was falsy')
			);
		}
		return $value;
	}

	/**
	 * Asserts that $value is true
	 * @param bool $bool
	 */
	public static function assert($bool) {
		if ($bool !== true) {
			Logger::getInstance()->fatal(
				new AssertionException('Assertion failed, value was false')
			);
		}
	}
}
