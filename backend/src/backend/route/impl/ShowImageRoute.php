<?php
require_once __DIR__ . '/../../util/constant/StatusCode.php';
require_once __DIR__ . '/../../util/constant/CORS.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';
require_once __DIR__ . '/../../util/data.php';
require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../route/RouteValidationResult.php';

class ShowImageRoute extends Route {
    public function __construct() {
        parent ::__construct("show/image", [RequestMethod::GET]);
    }

    public function handle($sess, $res) {
        readData("show.jpg", ContentType::JPEG, CORS::ALL, StatusCode::OK);
    }

    public function validateRequest($sess, $res) {
        return Ok();
    }
}