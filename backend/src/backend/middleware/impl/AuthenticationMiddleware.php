<?php

namespace TLT\Middleware\Impl;

use TLT\Middleware\BaseMiddleware;
use TLT\Util\HttpResult;

class AuthenticationMiddleware extends BaseMiddleware {
    public function apply($sess) {
        if (!$sess->auth->isAuthenticated()) {
            return HttpResult::from(
                403,
                'You are not permitted to perform this action.'
            );
        }
        return HttpResult::Ok();
    }
}
