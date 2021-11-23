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

class UserRoute extends Route {
    public function __construct() {
        parent ::__construct("user", [RequestMethod::GET, RequestMethod::POST]);
    }

    public function handle($conn, $res) {
        $method = $conn -> method;
        $model = null;

        if ($method == RequestMethod::GET) {
            $model = new UserModel(
                $conn -> queryParams()['id'],
                'John',
                'Doe',
                0,
                0,
                0,
                'jdoe',
                Constants ::AVATAR_URL_PREFIX()
            );
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

            if ($conn -> jsonParams()['id'] !== $data['id']) {
                return Unprocessable("Malformed or Invalid Data Provided");
            }
        }

        return Ok();
    }
}