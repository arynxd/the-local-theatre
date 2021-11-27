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


        $maps -> push(new ShowModel("8072d705-b547-4388-9358-59cf8e4192e2", "Grease", 0));
        $maps -> push(new ShowModel("e30e4be3-75da-4f17-a3c2-cdfb5e27b679", "Gansta granny", 0));
        $maps -> push(new ShowModel("29130dee-bc0e-4cf2-bb72-76d17928e58a", "The play that goes wrong", 0));
        $maps -> push(new ShowModel("a1a5b299-8a27-49b6-bf8f-c6314a8ce7f9", "To kill a mocking bird", 0));


        $res -> sendJSON($maps, StatusCode::OK);
    }

    public function validateRequest($sess, $res) {
        return Ok();
    }
}