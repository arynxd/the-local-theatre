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
            $res -> status(404)
                 -> cors("all")
                 -> error("No users found");
        }

        foreach ($dbRes -> raw() as $arr) {
            // convert to a model to get the right keys & validate
            $out -> push(
                UserModel ::fromJSON(Map ::from($arr)) -> toMap()
            );
        }

        $res -> status(200)
             -> cors("all")
             -> json($out);
    }

    public function validateRequest($sess, $res) {
        $sess -> applyMiddleware(new DatabaseMiddleware());

        return HttpResult ::Ok();
    }
}