<?php

require_once 'HttpModule.php';

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