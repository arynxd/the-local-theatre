<?php

// List all blogs within <start> and <limit> (start may be null for unknown)
// GET

require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../route/RouteValidationResult.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . '/../../model/PostModel.php';
require_once __DIR__ . '/../../util/identifier.php';

class PostListRoute extends Route {
    public function __construct() {
        parent ::__construct("post/list", [RequestMethod::GET]);
    }

    public function handle($conn, $res) {
        $user = new UserModel(createIdentifier(), 'john doe', 0, 1, 1, 'jdoe', "http://$_SERVER[HTTP_HOST]/avatar");
        $posts = [];

        for ($i = 1; $i < 11; $i++) {
            $model = new PostModel(createIdentifier(), $user, str_repeat("Lorem ipsum sit amet", $i), 'Latest Latest Latest', 1635762292);
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
