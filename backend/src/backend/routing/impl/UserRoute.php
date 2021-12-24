<?php

namespace TLT\Routing\Impl;

use TLT\Middleware\Impl\AuthenticationMiddleware;
use TLT\Middleware\Impl\DatabaseMiddleware;
use TLT\Middleware\Impl\ModelValidatorMiddleware;
use TLT\Model\Impl\UserModel;
use TLT\Model\ModelKeys;
use TLT\Routing\BaseRoute;
use TLT\Util\Assert\Assertions;
use TLT\Util\Data\Map;
use TLT\Util\Enum\Constants;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\HttpResult;

class UserRoute extends BaseRoute {
    public function __construct() {
        parent ::__construct("user", [RequestMethod::GET, RequestMethod::POST]);
    }

    public function handle($sess, $res) {
        $method = $sess -> http -> method;

        if ($method == RequestMethod::GET) {
            $res -> sendJSON($this -> getUserById($sess, $res, $sess -> queryParams()['id']) -> toMap());
        }
        else if ($method == RequestMethod::POST) {
            $data = $sess -> jsonParams();

            $selfUser = $sess -> cache -> user();

            Assertions ::assertSet($selfUser);

            $isModifyingSelf = $selfUser -> id == $data['id'];
            $isSelfAdmin = $selfUser -> permissions == 2;
            $isEditingPerms = $selfUser -> permissions != $data['permissions'];

            if (!$isModifyingSelf && !$isSelfAdmin) {
                $res -> status(401)
                     -> cors("all")
                     -> error("You may only modify your own user account");
            }

            if ($isModifyingSelf && $isEditingPerms) {
                $res -> status(401)
                     -> cors("all")
                     -> error("You cannot change your own permissions");
            }

            $this -> updateUser($sess, $data);

            $selfUser = $selfUser -> toMap();

            foreach ($data -> raw() as $k => $v) {
                $selfUser[$k] = $v;
            }

            $res -> status(200) 
                 -> cors("all")
                 -> json($selfUser);
        }
    }

    private function getUserById($sess, $res, $id) {
        $query = "SELECT * FROM user WHERE id = :id";

        $st = $sess -> db -> query($query, [
            'id' => $id
        ]);

        $dbRes = Map ::from($st -> fetchAll());

        if ($dbRes -> length() == 0) {
            $res -> status(404)
                 -> cors("all")
                 -> error("User not found");
        }

        $dbRes = Map ::from($dbRes -> first()); // we get arrays back from the db, convert it to a map
        $dbRes['avatar'] = Constants ::AVATAR_URL_PREFIX() . "?id=" . $dbRes['id'];

        return UserModel ::fromJSON($dbRes);
    }

    private function updateUser($sess, $data) {
        $query = "UPDATE user SET
                    firstName = :firstName,
                    lastName = :lastName,
                    username = :username,
                    dob = :dob,
                    permissions = :permissions
                WHERE id = :id
        ";

        $sess -> db -> query($query, [
            'firstName' => $data['firstName'],
            'lastName' => $data['lastName'],
            'username' => $data['username'],
            'dob' => $data['dob'],
            'permissions' => $data['permissions'],
            'id' => $data['id']
        ]);
    }

    public function validateRequest($sess, $res) {
        $sess -> applyMiddleware(new DatabaseMiddleware());

        if ($sess -> http -> method == RequestMethod::GET && !isset($sess -> queryParams()["id"])) {
            return HttpResult ::BadRequest("No ID provided");
        }

        if ($sess -> http -> method == RequestMethod::POST) {
            $data = $sess -> jsonParams();

            if (!isset($data)) {
                return HttpResult ::BadRequest("No data provided");
            }

            $sess -> applyMiddleware(new AuthenticationMiddleware());
            $sess -> applyMiddleware(new ModelValidatorMiddleware(ModelKeys::USER_UPDATE_MODEL(), $data, "Invalid data provided"));
        }

        return HttpResult ::Ok();
    }
}