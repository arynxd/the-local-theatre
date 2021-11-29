<?php

// Set a new moderation log entry
// POST

namespace TLT\Routing\Impl;


use TLT\Routing\Route;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\HttpResult;

class ModerationRoute extends Route {
    public function __construct() {
        parent ::__construct("moderation", [RequestMethod::POST]);
    }

    public function handle($sess, $res) {
        $res -> sendError("Not implemented", StatusCode::BAD_REQUEST);
    }

    public function validateRequest($sess, $res) {
        return HttpResult ::Ok();
    }
}