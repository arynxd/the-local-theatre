<?php

// Set a new moderation log entry
// POST

require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../route/RouteValidationResult.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';

class ModerationRoute extends Route {
    public function __construct() {
        parent ::__construct("moderation", [RequestMethod::POST]);
    }

    public function handle($conn, $res) {
        $res -> sendJSON(map([
            "id" => "0",
            "user_id" => "1",
            "reason" => "hello world"
        ]), StatusCode::OK);
    }

    public function validateRequest($conn, $res) {
        return Ok();
    }
}