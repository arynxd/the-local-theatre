<?php

require_once __DIR__ . '/../../util/constant/StatusCode.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';
require_once __DIR__ . '/../../util/constant/ParamSource.php';
require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../route/RouteValidationResult.php';
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . "/../../util/RequestValidator.php";
require_once __DIR__ . "/../../middleware/impl/AuthenticationMiddleware.php";
require_once __DIR__ . "/../../middleware/impl/ModelValidatorMiddleware.php";
require_once __DIR__ . '/../../util/constant/Constants.php';
require_once __DIR__ . '/../../util/Map.php';

class UserRoute extends Route {
    public function __construct() {
        parent ::__construct("user", [RequestMethod::GET, RequestMethod::POST]);
    }

    private function getUserById($conn, $res) {
        $query = "SELECT * from sql20006203.user WHERE sql20006203.user.id = :id";

        $targetId = $conn -> queryParams()['id'];
        $st = $conn -> database -> query($query, [
            'id' => $targetId
        ]);

        if (!$st) {
            $res -> exitWithInternalError();
        }

        $dbRes = map($st -> fetchAll());

        if ($dbRes -> length() == 0) {
            $res -> sendError("User not found", StatusCode::NOT_FOUND);
        }

        $dbRes = map($dbRes -> first()); // we get arrays back from the db, convert it to a map
        $dbRes['avatar'] = Constants ::AVATAR_URL_PREFIX() . "?id=" . $dbRes['id'];

        return UserModel ::fromJSON($dbRes);
    }

    public function handle($conn, $res) {
        $method = $conn -> method;
        $model = null;

        if ($method == RequestMethod::GET) {
            $res -> sendJSON($this -> getUserById($conn, $res) -> toMap());
        }

        if ($method == RequestMethod::POST) {
            $conn -> applyMiddleware(new AuthenticationMiddleware());
            $data = $conn -> jsonParams()['data'];


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

    public function validateRequest($conn, $res) {
        if ($conn -> method == RequestMethod::GET && !isset($conn -> queryParams()["id"])) {
            return BadRequest("No ID Provided");
        }

        if ($conn -> method == RequestMethod::POST) {
            $data = $conn -> jsonParams()['data'];

            if (!isset($data)) {
                return BadRequest("No Data Provided");
            }

            $validator = new ModelValidatorMiddleware(Keys::USER_MODEL, $data, "Invalid Data Provided");
            $conn -> applyMiddleware($validator);
        }

        return Ok();
    }
}