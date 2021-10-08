<?php

require_once __DIR__ . '/../../util/constant/StatusCode.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';
require_once __DIR__ . '/../../util/constant/ParamSource.php';
require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . "/../../util/RequestValidator.php";

class UserRoute extends Route {
    public function __construct($database) {
        parent::__construct($database, "user", [RequestMethod::GET, RequestMethod::POST]);
    }

    public function handle($conn, $res) {
        $method = $conn -> method;
        $model = null;

        if ($method == RequestMethod::GET) {
            $model = new UserModel($conn -> queryParams()['id'], 'John Doe');
        }

        if ($method == RequestMethod::PUT) {
            $data = $conn -> jsonParams()['data'];
            $model = new UserModel($data['id'], $data['name']);
        }

        $res -> sendJSON($model -> toJSON(), StatusCode::OK);
    }

    public function validateRequest($conn, $res) {
        if ($conn -> method == RequestMethod::GET && !isset($conn -> queryParams()["id"])) {
            return [false, "No ID Specified", StatusCode::BAD_REQUEST];
        }

        if ($conn -> method == RequestMethod::POST) {
            $data = $conn -> jsonParams()['data'];

            if (!isset($data)) {
                return [false, "No Data Provided", StatusCode::BAD_REQUEST];
            }

            $validator = new RequestValidator(UserModel::keys());
            
            if (!$validator -> validate($data) || $conn -> jsonParams()['id'] !== $data['id']) {
                return [false, "Invalid User Data Provided", StatusCode::UNPROCESSABLE_ENTITY];
            }
        }

        
        return [true];
    }
}