<?php

namespace TLT\Request\Module\Impl;

use TLT\Request\Module\Module;
use TLT\Routing\Router;
use TLT\Util\Enum\StatusCode;

class RoutingModule extends Module {
    public $router;
    public $route;

    public function onEnable() {
        $this -> router = new Router();
        $this -> route = $this -> parseRoute();
    }

    private function parseRoute() {
        $uri = $this -> sess -> http -> uri;
        $rawUri = $this -> sess -> http -> rawUri;

        $result = $this -> router -> getRouteForPath($uri);

        if (!$result) {
            $this -> sess -> res -> sendError("Route " . $rawUri . " not found", StatusCode::NOT_FOUND);
            exit;
        }

        return $result;
    }
}