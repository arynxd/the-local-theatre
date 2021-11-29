<?php

// Get or Set a comment from the specified <user_id> on a given <blog_id>, passing in the current <timestamp> as an epoch
// GET / PUT

namespace TLT\Routing\Impl;

use TLT\Routing\Route;
use TLT\Util\Data\Map;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\Result;

class CommentRoute extends Route {
    public function __construct() {
        parent ::__construct("comment", [RequestMethod::GET, RequestMethod::POST, RequestMethod::DELETE]);
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