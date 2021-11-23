<?php

require_once __DIR__ . '/../Route.php';
require_once __DIR__ . '/../../route/RouteValidationResult.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';
require_once __DIR__ . '/../../util/constant/StatusCode.php';
require_once __DIR__ . '/../../util/identifier.php';
require_once __DIR__ . '/../../util/Map.php';

class ShowListRoute extends Route {
    public function __construct() {
        parent ::__construct("show/list", [RequestMethod::GET]);
    }

    public function handle($conn, $res) {
        $res -> sendJSON(map([
            "shows" => [
                [
                    "id" => createIdentifier(),
                    "title" => "Show Title 1",
                    "img" => Constants ::SHOW_IMAGE__URL_PREFIX()
                ],
                [
                    "id" => createIdentifier(),
                    "title" => "Show Title 2",
                    "img" => Constants ::SHOW_IMAGE__URL_PREFIX()
                ],
                [
                    "id" => createIdentifier(),
                    "title" => "Show Title 3",
                    "img" => Constants ::SHOW_IMAGE__URL_PREFIX()
                ],
                [
                    "id" => createIdentifier(),
                    "title" => "Show Title 4",
                    "img" => Constants ::SHOW_IMAGE__URL_PREFIX()
                ]
            ]
        ]), StatusCode::OK);
    }

    public function validateRequest($conn, $res) {
        return Ok();
    }
}