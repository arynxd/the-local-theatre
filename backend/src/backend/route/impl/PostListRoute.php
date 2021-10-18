<?php

// List all blogs within <start> and <limit> (start may be null for unknown)
// GET

require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../route/RouteValidationResult.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . '/../../model/PostModel.php';
require_once __DIR__ . '/../../util/guid.php';

class PostListRoute extends Route {
    public function __construct() {
        parent ::__construct("post/list", [RequestMethod::GET]);
    }

    public function handle($conn, $res) {
        $user = new UserModel(createGuid(), 'john doe', 0, 1, 1, 'jdoe');
        $posts = [];

        for ($i = 0; $i < 10; $i++) {
            $model = new PostModel(createGuid(), $user, "Lorem ipsum sit amet", 1);
            array_push($posts, $model -> toJSON());
        }

        $res -> sendJSON([
            'posts' => $posts
        ], StatusCode::OK);
    }

    public function validateRequest($conn, $res) {
        return Ok();
    }
}