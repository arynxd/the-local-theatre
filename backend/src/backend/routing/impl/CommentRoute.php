<?php

// Get or Set a comment from the specified <user_id> on a given <blog_id>, passing in the current <timestamp> as an epoch
// GET / PUT

namespace TLT\Routing\Impl;

use TLT\Routing\BaseRoute;
use TLT\Util\Assert\AssertionException;
use TLT\Util\Data\Map;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\HttpResult;

class CommentRoute extends BaseRoute {
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
        $method = $sess -> http -> method;
        $query = $sess -> queryParams();
        $body = $sess -> queryParams();

        if ($method === RequestMethod::GET) {
            if (!isset($query['id'])) {
                return HttpResult ::BadRequest("No ID provided");
            }
        }
        else if ($method === RequestMethod::POST) {
            $res -> sendError("Unimplemented method " . $method);
        }
        else if ($method === RequestMethod::DELETE) {
            $res -> sendError("Unimplemented method " . $method);
        }
        else {
            throw new AssertionException("Unknown method " . $method);
        }
        return HttpResult ::Ok();
    }
}