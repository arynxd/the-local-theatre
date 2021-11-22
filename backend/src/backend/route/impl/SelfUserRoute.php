<?php

require_once __DIR__ . "/../Route.php";
require_once __DIR__ . "/../../util/Map.php";
require_once __DIR__ . "/../../util/constant/RequestMethod.php";
require_once __DIR__ . "/../../util/identifier.php";
require_once __DIR__ . '/../../route/RouteValidationResult.php';


class SelfUserRoute extends Route {
    public function __construct() {
        parent ::__construct("user/@me", [RequestMethod::GET]);
    }

    public function handle($conn, $res) {
        $m = new UserModel(
                createIdentifier(),
                'John Doe',
                0,
                0,
                0,
                'jdoe',
                Constants::AVATAR_URL_PREFIX()
        );
        $res -> sendJSON($m -> toMap());
    }

    public function validateRequest($conn, $res) {
        return Ok();
    }
}