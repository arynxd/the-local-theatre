<?php

namespace TLT\Util\Enum;

use TLT\Util\Data\Map;

class StatusCode {
	const NOT_FOUND = 'HTTP/1.1 404 Not Found';
	const BAD_REQUEST = 'HTTP/1.1 400 Bad Request';
	const INTERNAL_ERROR = 'HTTP/1.1 500 Internal Server Error';
	const OK = 'HTTP/1.1 200 OK';
	const UNPROCESSABLE_ENTITY = 'HTTP/1.1 422 Unprocessable Entity';
	const FORBIDDEN = 'HTTP/1.1 403 Forbidden';
	const UNAUTHORIZED = 'HTTP/1.1 401 Unauthorized';
	const CONFLICT = 'HTTP/1.1 409 Conflict';

	public static function MAP() {
		return Map::from([
			'404' => self::NOT_FOUND,
			'400' => self::BAD_REQUEST,
			'500' => self::INTERNAL_ERROR,
			'200' => self::OK,
			'422' => self::UNPROCESSABLE_ENTITY,
			'403' => self::FORBIDDEN,
			'401' => self::UNPROCESSABLE_ENTITY,
			'409' => self::CONFLICT,
		]);
	}
}
