<?php

namespace TLT\Routing\Impl;


use TLT\Middleware\Impl\AuthenticationMiddleware;
use TLT\Routing\BaseRoute;
use TLT\Util\Assert\Assertions;
use TLT\Util\Data\Map;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\HttpResult;

class UserPreferencesRoute extends BaseRoute {
    public function __construct() {
        parent ::__construct("user/preferences", [RequestMethod::GET, RequestMethod::POST]);
    }

    public function handle($sess, $res) {
        $token = $sess -> auth -> token;
        $selfUser = $sess -> cache -> user();

        Assertions ::assertSet($token);
        Assertions ::assertSet($selfUser);

        $query = "SELECT * FROM user_prefs WHERE userId = :userId";

        $prefs = $sess -> db -> query($query, ['userId' => $selfUser -> id]) -> fetch();

        if (!$prefs) {
            $res -> sendJSON(Map ::from([
                'id' => $selfUser -> id,
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
        return HttpResult ::Ok();
    }
}