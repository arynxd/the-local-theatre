<?php

namespace TLT\Routing\Impl;


use TLT\Middleware\Impl\AuthenticationMiddleware;
use TLT\Routing\Route;
use TLT\Util\Data\Map;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Result;
use UnexpectedValueException;

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
            $res -> sendJSON(Map ::from([
                'id' => $sess -> cache -> user() -> id,
                'theme' => 'dark'
            ]));
        }

        $res -> sendJSON(Map ::from([
            'id' => $prefs['userId'],
            'theme' => $prefs['theme']
        ]));
    }

    public function validateRequest($sess, $res) {
        $sess -> applyMiddleware(new AuthenticationMiddleware());
        return Result ::Ok();
    }
}