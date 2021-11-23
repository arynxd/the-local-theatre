<?php

// Get or Set a comment from the specified <user_id> on a given <blog_id>, passing in the current <timestamp> as an epoch
// GET / PUT

require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../route/RouteValidationResult.php';
require_once __DIR__ . '/../../util/Map.php';

class CommentRoute extends Route {
    public function __construct() {
        parent ::__construct("comment", [RequestMethod::GET, RequestMethod::POST, RequestMethod::DELETE]);
    }

    public function handle($conn, $res) {
        $res -> sendJSON(map([
            "id" => "0",
            "user_id" => "1",
            "reason" => "hello world"
        ]), StatusCode::OK);
    }

    public function validateRequest($conn, $res) {
        return Ok();
    }
}