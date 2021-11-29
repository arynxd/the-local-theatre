<?php

namespace TLT\Middleware\Impl;

use TLT\Middleware\Middleware;
use TLT\Util\Enum\StatusCode;
use TLT\Util\HttpResult;

class AuthenticationMiddleware extends Middleware {
    public function apply($sess) {
        if (!$sess -> auth -> isAuthenticated()) {
            return HttpResult ::from(StatusCode::FORBIDDEN, "You are not permitted to perform this action.");
        }
        return HttpResult ::Ok();
    }
}