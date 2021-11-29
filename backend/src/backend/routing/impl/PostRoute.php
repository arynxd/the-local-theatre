<?php

// Get or Set information about a specific blog with the specified <id>
// GET / POST

namespace TLT\Routing\Impl;


use TLT\Model\Impl\PostModel;
use TLT\Model\Impl\UserModel;
use TLT\Routing\Route;
use TLT\Util\Enum\Constants;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\Result;
use TLT\Util\StringUtil;

class PostRoute extends Route {
    public function __construct() {
        parent ::__construct("post", [RequestMethod::GET, RequestMethod::POST]);
    }

    public function handle($sess, $res) {
        $user = new UserModel(StringUtil::newID(), 'john', 'doe', 0, 1, 1, 'jdoe');
        $model = new PostModel(StringUtil::newID(), $user, str_repeat("Lorem ipsum sit amet ", 50), "Post title here, filler filler filler filler", 163761416);

        $res -> sendJSON($model -> toMap(), StatusCode::OK);
    }

    public function validateRequest($sess, $res) {
        return Result::Ok();
    }
}