<?php

// Set a new signup entry
// POST

require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../route/RouteValidationResult.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';

class SignupRoute extends Route {
    public function __construct() {
        parent::__construct("signup", [RequestMethod::POST]);
    }

    public function handle($conn, $res) {
        $res -> sendJSON([
            "token" => "aaaaabbbbbbcccccddddddeeeeefffff",
        ], StatusCode::OK);
    }

    public function validateRequest($conn, $res) {
        return Ok();
    }
}

