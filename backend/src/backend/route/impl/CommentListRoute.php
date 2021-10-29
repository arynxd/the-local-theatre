<?php

// List all comments within <start> and <limit> for a blog with the matching <blog_id> (start may be null for unknown)
// GET

require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../route/RouteValidationResult.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . '/../../model/PostModel.php';

class CommentListRoute extends Route {
    public function __construct() {
        parent ::__construct("comment/list", [RequestMethod::GET]);
    }

    public function handle($conn, $res) {
        $user =  new UserModel(createIdentifier(), 'john doe', 1, 1, 1, 'jdoe', "http://$_SERVER[HTTP_HOST]/avatar");

        $result = [];

        for ($_ = 0; $_ < 10; $_++) {
            $post = new PostModel(createIdentifier(), $user, 'Lorem ipsum sit damet', 1);
            array_push($result, $post -> toJSON());
        }

        $res -> sendJSON(['comments' => $result], StatusCode::OK);
    }

    public function validateRequest($conn, $res) {
        return Ok();
    }
}