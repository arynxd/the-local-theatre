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
        $token = $sess -> headers['Authorisation'];

        if (!$token) {
            throw new UnexpectedValueException("Token was not set? The validation middleware must have failed..");
        }

        $query = "SELECT u.* FROM credential c
                    LEFT JOIN user u on u.id = c.userId
                  WHERE token = :token";

        $selfUser = $sess -> database -> query($query, ['token' => $token]) -> fetch();

        if (!$selfUser) {
            throw new UnexpectedValueException("Self user did not exist? The validation middleware must have failed");
        }

        $m = UserModel::fromJSON(map($selfUser));
        $res -> sendJSON($m -> toMap());
    }

    public function validateRequest($sess, $res) {
        $sess -> applyMiddleware(new AuthenticationMiddleware());
        return Ok();
    }
}