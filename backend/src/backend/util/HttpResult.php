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
 * @see from
 */
class HttpResult {
    public $httpCode;
    public $error;

    /**
     * Constructs a new result
     *
     * @param int $httpCode the response code to use
     * @param string|null $error the error string to use, JSON format
     */
    function __construct($httpCode, $error) {
        $this -> httpCode = $httpCode;
        $this -> error = $error;
    }

    public static function Ok() {
        return new HttpResult(200, null);
    }

    public static function BadRequest($error) {
        return new HttpResult(400, $error);
    }

    public static function Unprocessable($error) {
        return new HttpResult(422, $error);
    }

    public static function from($code, $error) {
        return new HttpResult($code, $error);
    }

    /**
     * Determines if this result is erroneous
     *
     * @return bool true if this result is an error, false otherwise
     */
    function isError() {
        return isset($this -> error);
    }
}