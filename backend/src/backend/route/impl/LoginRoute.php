<?php

// Get the login details for a user with the given <email> and <password> combo (hashed)
// GET

require_once __DIR__ . '/../../route/Route.php';

class LoginRoute extends Route {
    public function __construct() {
        parent::__construct("signup", [RequestMethod::PUT], []);
    }

    public function handle($conn, $res) {
        $res -> sendJSON([
            "token" => "aaaaabbbbbbcccccddddddeeeeefffff",
        ], StatusCode::OK);
    }

    public function validateRequest($conn, $res) {
        return [true];
    }
}