<?php
require_once __DIR__ . "/../Middleware.php";

class AuthenticationMiddleware extends Middleware {
    public function apply($conn) {
        return [true];
    }
}