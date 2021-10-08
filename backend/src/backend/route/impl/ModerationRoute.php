<?php

// Set a new moderation log entry
// PUT

require_once __DIR__ . '/../../route/Route.php';

class ModerationRoute extends Route {
    public function __construct() {
        parent::__construct("signup", [RequestMethod::PUT], []);
    }

    public function handle($conn, $res) {
        $res -> sendJSON([
            "id" => "0",
            "user_id" => "1",
            "reason" => "hello world"
        ], StatusCode::OK);
    }

    public function validateRequest($conn, $res) {
        return [true];
    }
}