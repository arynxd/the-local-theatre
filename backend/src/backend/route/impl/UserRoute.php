<?php

require_once __DIR__ . '/../../util/constant/StatusCode.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';
require_once __DIR__ . '/../../util/constant/ParamSource.php';
require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../route/RouteValidationResult.php';
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . "/../../util/ModelModelKeys.php";
require_once __DIR__ . "/../../middleware/impl/AuthenticationMiddleware.php";
require_once __DIR__ . "/../../middleware/impl/ModelValidatorMiddleware.php";
require_once __DIR__ . '/../../util/constant/Constants.php';
require_once __DIR__ . '/../../util/Map.php';

class UserRoute extends Route {
    public function __construct() {
        parent ::__construct("user", [RequestMethod::GET, RequestMethod::POST]);
    }

    private function getUserById($conn, $res) {
        $query = "SELECT * FROM user WHERE user.id = :id";

        $targetId = $conn -> queryParams()['id'];
        $st = $conn -> database -> query($query, [
            'id' => $targetId
        ]);

        if (!$st) {
            $res -> sendInternalError();
        }

        $dbRes = map($st -> fetchAll());

        if ($dbRes -> length() == 0) {
            $res -> sendError("User not found", StatusCode::NOT_FOUND);
        }

        $dbRes = map($dbRes -> first()); // we get arrays back from the db, convert it to a map
        $dbRes['avatar'] = Constants ::AVATAR_URL_PREFIX() . "?id=" . $dbRes['id'];

        return UserModel ::fromJSON($dbRes);
    }

    public function handle($sess, $res) {
        $method = $sess -> method;
        $model = null;

        if ($method == RequestMethod::GET) {
            $res -> sendJSON($this -> getUserById($sess, $res) -> toMap());
        }

        if ($method == RequestMethod::POST) {
            $sess -> applyMiddleware(new AuthenticationMiddleware());
            $data = $sess -> jsonParams()['data'];


            $model = new UserModel(
                $data['id'],
                $data['firstName'],
                $data['lastName'],
                $data['permissions'],
                $data['dob'],
                $data['joinDate'],
                $data['username'],
                $data['avatar']
            );
        }

        $res -> sendJSON($model -> toMap(), StatusCode::OK);
    }

    public function validateRequest($sess, $res) {
        if ($sess -> method == RequestMethod::GET && !isset($sess -> queryParams()["id"])) {
            return BadRequest("No ID Provided");
        }

        if ($sess -> method == RequestMethod::POST) {
            $data = $sess -> jsonParams()['data'];

            if (!isset($data)) {
                return BadRequest("No Data Provided");
            }

            $validator = new ModelValidatorMiddleware(ModelKeys::USER_MODEL, $data, "Invalid Data Provided");
            $sess -> applyMiddleware($validator);
        }

        return Ok();
    }
}