<?php

require_once __DIR__ . '/../../util/constant/StatusCode.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';
require_once __DIR__ . '/../../util/constant/ParamSource.php';
require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . "/../../util/RequestValidator.php";
require_once __DIR__ . "/../../util/Map.php";
require_once __DIR__ . "/../../middleware/impl/ModelValidatorMiddleware.php";
require_once __DIR__ . '/../../route/RouteValidationResult.php';

class BaseRoute extends Route {
    public function __construct() {
        parent ::__construct("", [RequestMethod::GET, RequestMethod::POST, RequestMethod::PATCH, RequestMethod::PUT]);
    }

    public function handle($conn, $res) {
        $res -> sendJSON(map([
            "ok" => true
        ]), StatusCode::OK);
    }

    public function validateRequest($conn, $res) {
        return Ok();
    }
}
