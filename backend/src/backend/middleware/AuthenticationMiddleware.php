<?php
require_once __DIR__ . "Middleware.php";
require_once __DIR__ . '/../model/UserModel.php';
require_once __DIR__ . "../util/Result.php";

class AuthenticationMiddleware extends Middleware {
    public function apply($sess) {
        if (!$sess -> auth -> isAuthenticated()) {
            return Result(StatusCode::FORBIDDEN, "You are not permitted to perform this action.");
        }
        return Ok();
    }
}