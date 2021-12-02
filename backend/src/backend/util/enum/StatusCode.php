<?php

namespace TLT\Util\Enum;

class StatusCode {
    const HTTP_PREFIX = "HTTP/1.1 ";

    const NOT_FOUND = self::HTTP_PREFIX . "404 Not Found";
    const BAD_REQUEST = self::HTTP_PREFIX . '400 Bad Request';
    const INTERNAL_ERROR = self::HTTP_PREFIX . '500 Internal Server Error';
    const OK = self::HTTP_PREFIX . '200 OK';
    const UNPROCESSABLE_ENTITY = self::HTTP_PREFIX . '422 Unprocessable Entity';
    const FORBIDDEN = self::HTTP_PREFIX . '403 Forbidden';
    const UNAUTHORIZED = self::HTTP_PREFIX . '401 Unauthorized';
    const CONFLICT = self::HTTP_PREFIX . '409 Conflict';
}