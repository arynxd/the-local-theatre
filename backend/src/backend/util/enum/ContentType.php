<?php

namespace TLT\Util\Enum;

use TLT\Util\Data\Map;

class ContentType {
	const JSON = 'Content-Type: application/json';
	const PNG = 'Content-Type: image/png';
	const TEXT = 'Content-Type: text/plain';

	public static function MAP() {
		return Map::from([
			'json' => self::JSON,
			'png' => self::PNG,
			'text' => self::TEXT,
		]);
	}
}
