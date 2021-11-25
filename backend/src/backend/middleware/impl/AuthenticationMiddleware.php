<?php
require_once __DIR__ . "/../Middleware.php";
require_once __DIR__ . "/../../route/RouteValidationResult.php";

class AuthenticationMiddleware extends Middleware {
    public function apply($sess) {
        $token = $sess -> headers['Authorisation'];

        if (!isset($token)) {
            return Result(StatusCode::FORBIDDEN, "You are not permitted to perform this action.");
        }

        $exists = $sess -> database -> query("SELECT COUNT(*) FROM credential WHERE token = :token", ['token' => $token]) -> fetch()[0];

        if ($exists == 0) {
            return Result(StatusCode::FORBIDDEN, "Invalid or expired token provided.");
        }

        return Ok();
    }
}