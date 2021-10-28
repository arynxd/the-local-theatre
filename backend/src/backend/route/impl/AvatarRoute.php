<?php
require_once __DIR__ . '/../../util/constant/StatusCode.php';
require_once __DIR__ . '/../../util/constant/CORS.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';
require_once __DIR__ . '/../../util/constant/ParamSource.php';
require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . "/../../util/RequestValidator.php";
require_once __DIR__ . "/../../middleware/impl/ModelValidatorMiddleware.php";
require_once __DIR__ . '/../../route/RouteValidationResult.php';

class AvatarRoute extends Route {
    public function __construct() {
        parent ::__construct("avatar", [RequestMethod::GET]);
    }

    public function handle($conn, $res) {
        header(ContentType::PNG);
        header(CORS::ALL);
        readfile(__DIR__ . '/../impl/avatar.png');
    }

    public function validateRequest($conn, $res) {
        return Ok();
    }
}