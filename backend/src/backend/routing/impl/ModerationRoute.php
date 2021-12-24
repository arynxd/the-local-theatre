<?php

// Set a new moderation log entry
// POST

namespace TLT\Routing\Impl;

use TLT\Middleware\Impl\DatabaseMiddleware;
use TLT\Routing\BaseRoute;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\HttpResult;

class ModerationRoute extends BaseRoute {
    public function __construct() {
        parent::__construct('moderation', [RequestMethod::POST]);
    }

    public function handle($sess, $res) {
        $res->status(400)
            ->cors('all')
            ->error('Not implemented');
    }

    public function validateRequest($sess, $res) {
        $sess->applyMiddleware(new DatabaseMiddleware());
        return HttpResult::Ok();
    }
}
