<?php

namespace TLT\Routing\Impl;


use TLT\Model\Impl\ShowModel;
use TLT\Routing\BaseRoute;
use TLT\Util\Data\Map;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\HttpResult;

class ShowListRoute extends BaseRoute {
    public function __construct() {
        parent ::__construct("show/list", [RequestMethod::GET]);
    }

    public function handle($sess, $res) {
        $maps = Map ::none();


        $maps -> push(new ShowModel("8072d705-b547-4388-9358-59cf8e4192e2", "Grease", 0));
        $maps -> push(new ShowModel("e30e4be3-75da-4f17-a3c2-cdfb5e27b679", "Gansta granny", 0));
        $maps -> push(new ShowModel("29130dee-bc0e-4cf2-bb72-76d17928e58a", "The play that goes wrong", 0));
        $maps -> push(new ShowModel("a1a5b299-8a27-49b6-bf8f-c6314a8ce7f9", "To kill a mocking bird", 0));

        $maps = $maps -> map(function ($_, $item) {
            return $item -> toMap();
        });


        $res -> sendJSON($maps, [StatusCode::OK]);
    }

    public function validateRequest($sess, $res) {
        return HttpResult ::Ok();
    }
}