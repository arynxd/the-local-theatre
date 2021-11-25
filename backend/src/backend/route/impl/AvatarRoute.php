<?php
require_once __DIR__ . '/../../util/constant/StatusCode.php';
require_once __DIR__ . '/../../util/constant/CORS.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';
require_once __DIR__ . '/../../util/constant/ParamSource.php';
require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . "/../../util/ModelModelKeys.php";
require_once __DIR__ . "/../../middleware/impl/ModelValidatorMiddleware.php";
require_once __DIR__ . '/../../route/RouteValidationResult.php';

class AvatarRoute extends Route {
    public function __construct() {
        parent ::__construct("avatar", [RequestMethod::GET]);
    }

    public function handle($sess, $res) {
        readData("avatar.png", ContentType::PNG, CORS::ALL, StatusCode::OK);
    }

    public function validateRequest($sess, $res) {
        return Ok();
    }
}