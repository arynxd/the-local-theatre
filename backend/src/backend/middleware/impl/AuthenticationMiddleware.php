<?php
require_once __DIR__ . "/../Middleware.php";
require_once __DIR__ . "/../../route/RouteValidationResult.php";

class AuthenticationMiddleware extends Middleware {
    public function apply($conn) {
        return Ok();
    }
}