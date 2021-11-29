<?php

namespace TLT\Routing\Impl;


use TLT\Middleware\Impl\AuthenticationMiddleware;
use TLT\Routing\BaseRoute;
use TLT\Util\Assert\Assertions;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\HttpResult;

class SelfUserRoute extends BaseRoute {
    public function __construct() {
        parent ::__construct("user/@me", [RequestMethod::GET]);
    }

    public function handle($sess, $res) {
        $selfUser = $sess -> cache -> user();

        Assertions ::assertSet($selfUser);

        $selfUser = $selfUser -> toMap();

        // properly type numbers
        $selfUser['permissions'] = (int)$selfUser['permissions'];
        $selfUser['joinDate'] = (int)$selfUser['joinDate'];
        $selfUser['dob'] = (int)$selfUser['dob'];

        $res -> sendJSON($selfUser, StatusCode::OK);
    }

    public function validateRequest($sess, $res) {
        $sess -> applyMiddleware(new AuthenticationMiddleware());
        return HttpResult ::Ok();
    }
}