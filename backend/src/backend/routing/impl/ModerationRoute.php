<?php

// Set a new moderation log entry
// POST

namespace TLT\Routing\Impl;


use TLT\Routing\Route;
use TLT\Util\Data\Map;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\Result;

class ModerationRoute extends Route {
    public function __construct() {
        parent ::__construct("moderation", [RequestMethod::POST]);
    }

    public function handle($sess, $res) {
        $res -> sendJSON(Map ::from([
            "id" => "0",
            "user_id" => "1",
            "reason" => "hello world"
        ]), StatusCode::OK);
    }

    public function validateRequest($sess, $res) {
        return Result ::Ok();
    }
}