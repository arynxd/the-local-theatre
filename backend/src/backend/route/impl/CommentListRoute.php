<?php

// List all comments within <start> and <limit> for a blog with the matching <blog_id> (start may be null for unknown)
// GET

require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../route/RouteValidationResult.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';

class CommentListRoute extends Route {
    public function __construct() {
        parent::__construct("comment/list", [RequestMethod::GET]);
    }

    public function handle($conn, $res) {
        $res -> sendJSON([
            "id" => "0",
            "user_id" => "1",
            "reason" => "hello world"
        ], StatusCode::OK);
    }

    public function validateRequest($conn, $res) {
        return Ok();
    }
}