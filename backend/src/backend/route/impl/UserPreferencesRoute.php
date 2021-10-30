<?php

require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../route/RouteValidationResult.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';

class UserPreferencesRoute extends Route {
    public function __construct() {
        parent ::__construct("user/preferences", [RequestMethod::GET, RequestMethod::POST]);
    }

    public function handle($conn, $res) {
        $res -> sendJSON([
            "id" => "0",
            "theme" => "dark"
        ], StatusCode::OK);
    }

    public function validateRequest($conn, $res) {
        return Ok();
    }
}