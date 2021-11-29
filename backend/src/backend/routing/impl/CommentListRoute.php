<?php

// List all comments within <start> and <limit> for a blog with the matching <blog_id> (start may be null for unknown)
// GET

namespace TLT\Routing\Impl;


use TLT\Model\Impl\PostModel;
use TLT\Model\Impl\UserModel;
use TLT\Routing\Route;
use TLT\Util\Data\Map;
use TLT\Util\Enum\Constants;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\Result;
use TLT\Util\StringUtil;

class CommentListRoute extends Route {
    public function __construct() {
        parent ::__construct("comment/list", [RequestMethod::GET]);
    }

    public function handle($sess, $res) {
        $user = new UserModel(StringUtil::newID(), 'john', 'doe', 1, 1, 1, 'jdoe');

        $result = Map::none();

        for ($_ = 0; $_ < 10; $_ ++) {
            $post = new PostModel(StringUtil::newID(), $user, 'Lorem ipsum sit damet', 'Latest Latest Latest', 1);
            $result -> push($post -> toMap());
        }

        $res -> sendJSON(Map::from(['comments' => $result, 'count' => 10]), StatusCode::OK);
    }

    public function validateRequest($sess, $res) {
        return Result::Ok();
    }
}
