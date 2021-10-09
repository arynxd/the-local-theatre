<?php

// Get or Set information about a specific blog with the specified <id>
// GET / POST

require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../route/RouteValidationResult.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';

class BlogRoute extends Route {
    public function __construct() {
        parent::__construct("blog", [RequestMethod::GET, RequestMethod::POST]);
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