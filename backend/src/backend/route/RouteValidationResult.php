<?php

require_once __DIR__ . "/../util/constant/StatusCode.php";

class RouteValidationResult {
    public $httpCode;
    public $error;
    public $headers;

    function __construct($httpCode, $error, $headers) {
        $this -> httpCode = $httpCode;
        $this -> error = $error;
        $this -> headers = $headers;
    }

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

function Result($code, $error, $headers) {
    return new RouteValidationResult($code, $error, $headers);
}