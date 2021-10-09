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

class UserRoute extends Route {
    public function __construct() {
        parent::__construct("user", [RequestMethod::GET, RequestMethod::POST]);
    }

    public function handle($conn, $res) {
        $method = $conn -> method;
        $model = null;

        if ($method == RequestMethod::GET) {
            $model = new UserModel(
                $conn -> queryParams()['id'],
                'John Doe',
                0,
                0,
                0,
                'jdoe'
            );
        }

        if ($method == RequestMethod::POST) {
            $conn -> applyMiddleware(new AuthenticationMiddleware());
            $data = $conn -> jsonParams()['data'];
            $model = new UserModel(
                $data['id'],
                $data['name'],
                $data['permissions'],
                $data['dob'],
                $data['joinDate'],
                $data['username']
            );
        }

        $res -> sendJSON($model -> toJSON(), StatusCode::OK);
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

            $conn -> applyMiddleware(new ModelValidatorMiddleware(new RequestValidator(Keys::USER_MODEL), 'data', "Malformed / Invalid Data Provided"));
            
            if ($conn -> jsonParams()['id'] !== $data['id']) {
                return Unprocessable("Malformed / Invalid Data Provided");
            }
        }

        
        return Ok();
    }
}