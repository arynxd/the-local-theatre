<?php

// Post the login details for a user with the given <email> and <password> combo (hashed)
// POST

require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . "/../../middleware/impl/ModelValidatorMiddleware.php";
require_once __DIR__ . '/../../util/constant/RequestMethod.php';

class LoginRoute extends Route {
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