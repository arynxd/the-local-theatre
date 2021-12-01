<?php

namespace TLT\Routing\Impl;

use TLT\Middleware\Impl\DatabaseMiddleware;
use TLT\Model\Impl\UserModel;
use TLT\Routing\BaseRoute;
use TLT\Util\Data\Map;
use TLT\Util\Enum\Constants;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\HttpResult;

class UserListRoute extends BaseRoute {
    public function __construct() {
        parent ::__construct("user/list", [RequestMethod::GET]);
    }

    public function handle($sess, $res) {
        $out = Map ::none();

        $st = $sess -> db -> query("SELECT * FROM user");
        $dbRes = Map ::from($st -> fetchAll());

        if ($dbRes -> length() == 0) {
            $res -> sendError("No users present", StatusCode::NOT_FOUND);
        }

        foreach ($dbRes -> raw() as $arr) {
            $map = Map ::from($arr);
            $map['avatar'] = Constants ::AVATAR_URL_PREFIX() . "?id=" . $arr['id'];
            $m = UserModel ::fromJSON($map);
            $out -> push($m -> toMap());
        }

        $res -> sendJSON($out, StatusCode::OK);
    }

    public function validateRequest($sess, $res) {
        $sess -> applyMiddleware(new DatabaseMiddleware());

        return HttpResult ::Ok();
    }
}