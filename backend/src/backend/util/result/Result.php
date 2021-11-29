<?php

namespace TLT\Util;

use TLT\Util\Enum\StatusCode;

/**
 * A generic result class
 *
 * The following factory methods should be used to construct this class
 * @see Ok
 * @see BadRequest
 * @see Unprocessable
 * @see Result
 */
class Result {
    public $httpCode;
    public $error;
    public $headers;

    /**
     * Constructs a new result
     *
     * @param int $httpCode the response code to use
     * @param string|null $error the error string to use, JSON format
     * @param string[] $headers the headers to use
     */
    function __construct($httpCode, $error, $headers) {
        $this -> httpCode = $httpCode;
        $this -> error = $error;
        $this -> headers = $headers;
    }

    /**
     * Determines if this result is erroneous
     *
     * @return bool true if this result is an error, false otherwise
     */
    function isError() {
        return isset($this -> error);
    }


    public static function Ok(...$headers) {
        return new Result(StatusCode::OK, null, $headers);
    }

    public static function BadRequest($error, ...$headers) {
        return new Result(StatusCode::BAD_REQUEST, $error, $headers);
    }

    public static function Unprocessable($error, ...$headers) {
        return new Result(StatusCode::UNPROCESSABLE_ENTITY, $error, $headers);
    }

    public static function from($code, $error, ...$headers) {
        return new Result($code, $error, $headers);
    }
}