<?php

namespace TLT\Util;

use TLT\Util\Data\Map;
use TLT\Util\Enum\ErrorStrings;
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

	private static function TYPE_MAP() {
		return Map::from([
			'string' => function ($data) {
				return is_string($data);
			},
			'int' => function ($data) {
				return is_integer($data);
			},
			'boolean' => function ($data) {
				return is_bool($data);
			},
		]);
	}

	/**
	 * Validates an incoming request body against a map of types
	 *
	 * @param Map $body The request body
	 * @param array $types The types to validate with
	 * @return HttpResult The result of this validation
	 */
	public static function validateBody($body, $types) {
		$typeMap = self::TYPE_MAP();
		$types = Map::from($types);

		foreach ($body as $k => $v) {
			$type = $types[$k];

			if (!isset($type)) {
				return HttpResult::BadRequest(
					"Key $k not provided in body data"
				);
			}

			$validateFn = $typeMap[$type];

			if (!isset($validateFn)) {
				Logger::getInstance()->error(
					"Unknown type $type in validation function"
				);
				return HttpResult::from(500, ErrorStrings::INTERNAL_ERROR);
			}

			$validateRes = call_user_func($validateFn, $v);

			if (!$validateRes) {
				return HttpResult::BadRequest(
					"Expected type $type for key $k within body data"
				);
			}
		}
		return HttpResult::Ok();
	}
}
