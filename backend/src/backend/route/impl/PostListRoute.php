<?php

// List all blogs within <start> and <limit> (start may be null for unknown)
// GET

require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../route/RouteValidationResult.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . '/../../model/PostModel.php';
require_once __DIR__ . '/../../util/identifier.php';
require_once __DIR__ . '/../../util/Map.php';
require_once __DIR__ . '/../../util/constant/Constants.php';

class PostListRoute extends Route {
    public function __construct() {
        parent ::__construct("post/list", [RequestMethod::GET]);
    }

    public function handle($sess, $res) {
        $user = new UserModel(createIdentifier(), 'john', 'doe', 0, 1, 1, 'jdoe', Constants ::AVATAR_URL_PREFIX());
        $posts = new Map();

        for ($i = 1; $i < 11; $i ++) {
            $model = new PostModel(createIdentifier(), $user, str_repeat("Lorem ipsum sit amet ", $i), 'Post title goes here', 1635762292 + ($i * 987));
            $posts -> push($model -> toMap());
        }

        $res -> sendJSON(map([
            $posts
        ]), StatusCode::OK);
    }

    public function validateRequest($sess, $res) {
        return Ok();
    }
}
