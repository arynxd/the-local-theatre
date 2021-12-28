<?php

namespace TLT\Util;

use TLT\Util\Assert\Assertions;
use TLT\Util\Log\Logger;

class HttpUtil {
	/**
	 * Sends the $header
	 *
	 * @param string $header The header to send
	 */
	public static function applyHeader($header) {
		Logger::getInstance()->debug("Applying header $header");
		header($header);
	}

	/**
	 * Sends all the $headers
	 *
	 * @param string[] The headers to send
	 */
	public static function applyHeaders($headers) {
		foreach ($headers as $h) {
			self::applyHeader($h);
		}
	}
}
