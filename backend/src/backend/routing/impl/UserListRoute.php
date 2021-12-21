<?php

namespace TLT\Routing\Impl;

use TLT\Middleware\Impl\DatabaseMiddleware;
use TLT\Model\Impl\UserModel;
use TLT\Routing\BaseRoute;
use TLT\Util\Data\Map;
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
            $res -> sendError("No users present", [StatusCode::NOT_FOUND]);
        }

        foreach ($dbRes -> raw() as $arr) {
            // convert to a model to get the right keys & validate
            $out -> push(
                UserModel ::fromJSON(Map ::from($arr)) -> toMap()
            );
        }

        $res -> sendJSON($out, [StatusCode::OK]);
    }

    public function validateRequest($sess, $res) {
        $sess -> applyMiddleware(new DatabaseMiddleware());

        return HttpResult ::Ok();
    }
}