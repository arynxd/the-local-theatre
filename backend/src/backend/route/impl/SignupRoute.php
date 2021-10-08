<?php

// Set a new signup entry
// PUT

require_once __DIR__ . '/../../route/Route.php';

class SignupRoute extends Route {
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

