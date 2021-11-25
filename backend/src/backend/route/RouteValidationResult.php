<?php

require_once __DIR__ . "/../util/constant/StatusCode.php";

/**
 * The result of a validation within a route
 * @see Route
 *
 * The following factory methods should be used to construct this class
 * @see Ok
 * @see BadRequest
 * @see Unprocessable
 * @see Result
 */
class RouteValidationResult {
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
}

function Ok(...$headers) {
    return new RouteValidationResult(StatusCode::OK, null, $headers);
}

function BadRequest($error, ...$headers) {
    return new RouteValidationResult(StatusCode::BAD_REQUEST, $error, $headers);
}

function Unprocessable($error, ...$headers) {
    return new RouteValidationResult(StatusCode::UNPROCESSABLE_ENTITY, $error, $headers);
}

function Result($code, $error, ...$headers) {
    return new RouteValidationResult($code, $error, $headers);
}