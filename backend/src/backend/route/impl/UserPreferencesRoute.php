<?php

require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../route/RouteValidationResult.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';
require_once __DIR__ . '/../../util/Map.php';
require_once __DIR__ . '/../../middleware/impl/AuthenticationMiddleware.php';

class UserPreferencesRoute extends Route {
    public function __construct() {
        parent ::__construct("user/preferences", [RequestMethod::GET, RequestMethod::POST]);
    }

    public function handle($sess, $res) {
        $token = $sess -> headers['Authorisation'];

        if (!$token) {
            throw new UnexpectedValueException("Token was not set? The validation middleware must have failed..");
        }

        $query = "SELECT u.* FROM credential c
            LEFT JOIN user_prefs u on u.userId = c.userId
        WHERE c.token = :token";

        $prefs = $sess -> database -> query($query, ['token' => $token]) -> fetch();

        if (!$prefs) {
            throw new UnexpectedValueException("User prefs did not exist? The validation middleware must have failed");
        }

        $res -> sendJSON(map([
            'id' => $prefs['userId'],
            'theme' => $prefs['theme']
        ]));
    }

    public function validateRequest($sess, $res) {
        $sess -> applyMiddleware(new AuthenticationMiddleware());
        return Ok();
    }
}