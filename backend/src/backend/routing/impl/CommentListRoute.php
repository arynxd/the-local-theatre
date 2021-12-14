<?php

// List all comments within <start> and <limit> for a blog with the matching <blog_id> (start may be null for unknown)
// GET

namespace TLT\Routing\Impl;


use TLT\Middleware\Impl\DatabaseMiddleware;
use TLT\Model\Impl\CommentModel;
use TLT\Model\Impl\PostModel;
use TLT\Model\Impl\UserModel;
use TLT\Routing\BaseRoute;
use TLT\Util\Data\Map;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\HttpResult;
use TLT\Util\StringUtil;

class CommentListRoute extends BaseRoute {
    public function __construct() {
        parent ::__construct("comment/list", [RequestMethod::GET]);
    }

    public function handle($sess, $res) {
        $st = $sess -> db -> query("SELECT * FROM comment");
        $dbRes = $st -> fetchAll();
        $items = Map ::none();

        foreach ($dbRes as $item) {
            $items -> push(CommentModel ::fromJSON(Map ::from($item)));
        }

        $res -> sendJSON(Map ::from(['comments' => $items, 'count' => $items -> length()]), StatusCode::OK);
    }

    public function validateRequest($sess, $res) {
        $sess -> applyMiddleware(new DatabaseMiddleware());
        return HttpResult ::Ok();
    }
}
