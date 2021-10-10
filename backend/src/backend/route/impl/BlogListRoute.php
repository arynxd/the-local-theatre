<?php

// List all blogs within <start> and <limit> (start may be null for unknown)
// GET

require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../route/RouteValidationResult.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';

class BlogListRoute extends Route {
    public function __construct() {
        parent ::__construct("blog/list", [RequestMethod::GET]);
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