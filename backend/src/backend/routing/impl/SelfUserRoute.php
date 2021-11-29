<?php

namespace TLT\Routing\Impl;


use TLT\Middleware\Impl\AuthenticationMiddleware;
use TLT\Routing\Route;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\Result;
use UnexpectedValueException;

class SelfUserRoute extends Route {
    public function __construct() {
        parent ::__construct("user/@me", [RequestMethod::GET]);
    }

    public function handle($sess, $res) {
        $selfUser = $sess -> cache -> user();

        if (!$selfUser) {
            throw new UnexpectedValueException("Self user was not set? The validation middleware must have failed..");
        }

        $selfUser = $selfUser -> toMap();

        // properly type numbers
        $selfUser['permissions'] = (int)$selfUser['permissions'];
        $selfUser['joinDate'] = (int)$selfUser['joinDate'];
        $selfUser['dob'] = (int)$selfUser['dob'];

        $res -> sendJSON($selfUser, StatusCode::OK);
    }

    public function validateRequest($sess, $res) {
        $sess -> applyMiddleware(new AuthenticationMiddleware());
        return Result ::Ok();
    }
}