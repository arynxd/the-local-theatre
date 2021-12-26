<?php

namespace TLT\Util;

use TLT\Util\Assert\Assertions;

class StringUtil {
	/**
	 * Creates a v4 UUID
	 *
	 * @return string the generated UUID
	 */
	public static function newID() {
		return sprintf(
			'%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

			// 32 bits for "time_low"
			mt_rand(0, 0xffff),
			mt_rand(0, 0xffff),

			// 16 bits for "time_mid"
			mt_rand(0, 0xffff),

			// 16 bits for "time_hi_and_version",
			// four most significant bits holds version number 4
			mt_rand(0, 0x0fff) | 0x4000,

			// 16 bits, 8 bits for "clk_seq_hi_res",
			// 8 bits for "clk_seq_low",
			// two most significant bits holds zero and one for variant DCE1.1
			mt_rand(0, 0x3fff) | 0x8000,

			// 48 bits for "node"
			mt_rand(0, 0xffff),
			mt_rand(0, 0xffff),
			mt_rand(0, 0xffff)
		);
	}

	/**
	 * Checks whether src starts with target
	 * @param string $src
	 * @param string $target
	 * @return bool
	 */
	public static function startsWith($src, $target) {
		Assertions::assertType($src, 'string');
		Assertions::assertType($target, 'string');

		$length = strlen($target);
		return substr($src, 0, $length) === $target;
	}
}
