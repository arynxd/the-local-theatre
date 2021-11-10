<?php

// Get or Set information about a specific blog with the specified <id>
// GET / POST

require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../route/RouteValidationResult.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . '/../../model/PostModel.php';

class PostRoute extends Route {
    public function __construct() {
        parent ::__construct("post", [RequestMethod::GET, RequestMethod::POST]);
    }

    public function handle($conn, $res) {
        $user = new UserModel(1, 'john doe', 0, 1, 1, 'jdoe');
        $model = new PostModel(1, $user, "Lorem ipsum sit amet", 1);

        $res -> sendJSON($model -> toMap(), StatusCode::OK);
    }

    public function validateRequest($conn, $res) {
        return Ok();
    }
}