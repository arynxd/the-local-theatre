<?php

namespace TLT\Util\Enum;

use TLT\Util\Data\Map;

class StatusCode {
    const NOT_FOUND = "404 Not Found";
    const BAD_REQUEST = '400 Bad Request';
    const INTERNAL_ERROR = '500 Internal Server Error';
    const OK = '200 OK';
    const UNPROCESSABLE_ENTITY = '422 Unprocessable Entity';
    const FORBIDDEN = '403 Forbidden';
    const UNAUTHORIZED = '401 Unauthorized';
    const CONFLICT = '409 Conflict';

    public static function MAP() {
        return Map::from([
            "404" => self::NOT_FOUND,
            "400" => self::BAD_REQUEST,
            "500" => self::INTERNAL_ERROR,
            "200" => self::OK,
            "422" => self::UNPROCESSABLE_ENTITY,
            "403" => self::FORBIDDEN,
            "401" => self::UNPROCESSABLE_ENTITY,
            "409" => self::CONFLICT
        ]);
    }
}