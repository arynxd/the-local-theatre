<?php

namespace TLT\Request\Module;

use TLT\Request\Module\Impl\HttpModule;
use TLT\Routing\Router;
use TLT\Util\Enum\StatusCode;

class RoutingModule extends HttpModule {
    public $router;
    public $route;

    protected function onEnable() {
        $this -> router = new Router();
        $this -> route = $this -> parseRoute();
    }

    private function parseRoute() {

        $result = $this -> router -> getRouteForPath($this -> uri);

        if (!$result) {
            $this -> sess -> res -> sendError("Route " . $this -> rawUri . " not found", StatusCode::NOT_FOUND);
            exit;
        }

        return $result;
    }
}