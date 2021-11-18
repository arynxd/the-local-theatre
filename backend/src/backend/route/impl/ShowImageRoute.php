<?php
require_once __DIR__ . '/../../util/constant/StatusCode.php';
require_once __DIR__ . '/../../util/constant/CORS.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';
require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../route/RouteValidationResult.php';

class ShowImageRoute extends Route {
    public function __construct() {
        parent ::__construct("show/image", [RequestMethod::GET]);
    }

    public function handle($conn, $res) {
        header(ContentType::JPEG);
        header(CORS::ALL);
        header(StatusCode::OK);
        readfile(__DIR__ . '/../impl/ballet-2500.jpg');
    }

    public function validateRequest($conn, $res) {
        return Ok();
    }
}