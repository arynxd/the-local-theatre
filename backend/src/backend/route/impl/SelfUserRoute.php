<?php

require_once __DIR__ . "/../Route.php";
require_once __DIR__ . "/../../util/Map.php";
require_once __DIR__ . "/../../util/constant/RequestMethod.php";
require_once __DIR__ . "/../../util/identifier.php";
require_once __DIR__ . '/../../route/RouteValidationResult.php';
require_once __DIR__ . '/../../middleware/impl/AuthenticationMiddleware.php';


class SelfUserRoute extends Route {
    public function __construct() {
        parent ::__construct("user/@me", [RequestMethod::GET]);
    }

    public function handle($sess, $res) {
        $selfUser = $sess -> selfUser;

        if (!$selfUser) {
            throw new UnexpectedValueException("Self user was not set? The validation middleware must have failed..");
        }

        $res -> sendJSON($selfUser -> toMap(), StatusCode::OK);
    }

    public function validateRequest($sess, $res) {
        $sess -> applyMiddleware(new AuthenticationMiddleware());
        return Ok();
    }
}