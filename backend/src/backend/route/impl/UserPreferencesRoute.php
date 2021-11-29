<?php

require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../route/Result.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';
require_once __DIR__ . '/../../util/Map.php';
require_once __DIR__ . '/../../middleware/impl/AuthenticationMiddleware.php';

class UserPreferencesRoute extends Route {
    public function __construct() {
        parent ::__construct("user/preferences", [RequestMethod::GET, RequestMethod::POST]);
    }

    public function handle($sess, $res) {
        $token = $sess -> auth -> token;

        if (!$token) {
            throw new UnexpectedValueException("Token was not set? The validation middleware must have failed..");
        }

        $query = "SELECT u.* FROM credential c
            LEFT JOIN user_prefs u on u.userId = c.userId
        WHERE c.token = :token";

        $prefs = $sess -> db -> query($query, ['token' => $token]) -> fetch();

        if (!$prefs) {
            $res -> sendJSON(map([
                'id' => $sess -> cache -> user() -> id,
                'theme' => 'dark'
            ]));
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