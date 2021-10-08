<?php

class Router {
    private $routes;

    public function __construct($routes) {
        $this -> routes = $routes;
    }

    public function getRouteForPath($parts) {
        $match = join("/", $parts);

        foreach ($this -> routes as $route) {
            if ($route -> path == $match) {
                return $route;
            }
        }
        return false;
    }

    /**
     * Handles CORS OPTIONS requests and responds appropriately
     */
    function handleCors() {
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            exit(0);
        }
}
}