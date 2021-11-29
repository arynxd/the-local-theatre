<?php

// Get or Set information about a specific blog with the specified <id>
// GET / POST

require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../route/Result.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';
require_once __DIR__ . '/../../util/Map.php';
require_once __DIR__ . '/../../util/identifier.php';
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . '/../../model/PostModel.php';
require_once __DIR__ . '/../../util/constant/Constants.php';

class PostRoute extends Route {
    public function __construct() {
        parent ::__construct("post", [RequestMethod::GET, RequestMethod::POST]);
    }

    public function handle($sess, $res) {
        $user = new UserModel(createIdentifier(), 'john', 'doe', 0, 1, 1, 'jdoe', Constants ::AVATAR_URL_PREFIX());
        $model = new PostModel(createIdentifier(), $user, str_repeat("Lorem ipsum sit amet ", 50), "Post title here, filler filler filler filler", 163761416);

        $res -> sendJSON($model -> toMap(), StatusCode::OK);
    }

    public function validateRequest($sess, $res) {
        return Ok();
    }
}