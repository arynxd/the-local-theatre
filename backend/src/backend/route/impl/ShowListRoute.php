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
        $models = map([
            new ShowModel(createIdentifier(), 'Show title 1', 0, Constants::SHOW_IMAGE_URL_PREFIX()),
            new ShowModel(createIdentifier(), 'Show title 2', 0, Constants::SHOW_IMAGE_URL_PREFIX()),
            new ShowModel(createIdentifier(), 'Show title 3', 0, Constants::SHOW_IMAGE_URL_PREFIX()),
            new ShowModel(createIdentifier(), 'Show title 4', 0, Constants::SHOW_IMAGE_URL_PREFIX())
        ]);

        $mapper =  function ($item) {
            return $item -> toMap();
        };

        $res -> sendJSON(map($models -> map($mapper)), StatusCode::OK);
    }

    public function validateRequest($sess, $res) {
        return Ok();
    }
}