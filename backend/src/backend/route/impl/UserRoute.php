<?php

require_once __DIR__ . '/../../util/constant/StatusCode.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';
require_once __DIR__ . '/../../util/constant/ParamSource.php';
require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../route/Result.php';
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . "/../../util/model.php";
require_once __DIR__ . "/../../middleware/impl/AuthenticationMiddleware.php";
require_once __DIR__ . "/../../middleware/impl/ModelValidatorMiddleware.php";
require_once __DIR__ . '/../../util/constant/Constants.php';
require_once __DIR__ . '/../../util/Map.php';

class UserRoute extends Route {
    public function __construct() {
        parent ::__construct("user", [RequestMethod::GET, RequestMethod::POST]);
    }

    private function getUserById($sess, $res, $id) {
        $query = "SELECT * FROM user WHERE id = :id";

        $st = $sess -> database -> query($query, [
            'id' => $id
        ]);

        $dbRes = map($st -> fetchAll());

        if ($dbRes -> length() == 0) {
            $res -> sendError("User not found", StatusCode::NOT_FOUND);
        }

        $dbRes = map($dbRes -> first()); // we get arrays back from the db, convert it to a map
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
        ";

        $sess -> database -> query($query, [
            'firstName' => $data['firstName'],
            'lastName' => $data['lastName'],
            'username' => $data['username'],
            'dob' => $data['dob'],
            'permissions' => $data['permissions']
        ]);
    }

    public function handle($sess, $res) {
        $method = $sess -> http -> method;

        if ($method == RequestMethod::GET) {
            $res -> sendJSON($this -> getUserById($sess, $res, $sess -> queryParams()['id']) -> toMap());
        }

        if ($method == RequestMethod::POST) {
            $data = $sess -> jsonParams()['data'];

            $selfUser = $sess -> cache -> user();

            if (!isset($selfUser)) {
                throw new UnexpectedValueException("Self user was not set");
            }

            $isModifyingSelf = $selfUser -> id == $data['id'];
            $isSelfAdmin = $selfUser -> permissions == 2;
            $isEditingPerms = $selfUser -> permissions != $data['permissions'];

            if (!$isModifyingSelf && !$isSelfAdmin) {
                $res -> sendError("You may only modify your own user account", StatusCode::UNAUTHORIZED);
            }

            if ($isModifyingSelf && $isEditingPerms) {
                $res -> sendError("You cannot change your own permissions", StatusCode::UNAUTHORIZED);
            }

            $this -> updateUser($sess, $data);

            $selfUser = $selfUser -> toMap();

            foreach ($data as $k => $v) {
                $selfUser[$k] = $v;
            }

            $res -> sendJSON($selfUser);
        }
    }

    public function validateRequest($sess, $res) {
        if ($sess -> http -> method == RequestMethod::GET && !isset($sess -> queryParams()["id"])) {
            return BadRequest("No ID provided");
        }

        if ($sess -> http -> method == RequestMethod::POST) {
            $data = $sess -> jsonParams()['data'];

            if (!isset($data)) {
                return BadRequest("No data provided");
            }

            $sess -> applyMiddleware(new AuthenticationMiddleware());

            $validator = new ModelValidatorMiddleware(ModelKeys::USER_UPDATE_MODEL, $data, "Invalid data provided");
            $sess -> applyMiddleware($validator);
        }

        return Ok();
    }
}