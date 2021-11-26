<?php

require_once __DIR__ . '/../Route.php';
require_once __DIR__ . '/../../route/RouteValidationResult.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';
require_once __DIR__ . '/../../util/constant/StatusCode.php';
require_once __DIR__ . '/../../util/identifier.php';
require_once __DIR__ . '/../../util/Map.php';
require_once __DIR__ . '/../../model/ShowModel.php';

class ShowListRoute extends Route {
    public function __construct() {
        parent ::__construct("show/list", [RequestMethod::GET]);
    }

    public function handle($sess, $res) {
        $maps = map([]);

        for ($i = 0; $i < 4; $i++) {
            $maps -> push(new ShowModel(createIdentifier(), "Show title $i", 0, Constants::SHOW_IMAGE_URL_PREFIX()));
        }

        $res -> sendJSON($maps, StatusCode::OK);
    }

    public function validateRequest($sess, $res) {
        return Ok();
    }
}