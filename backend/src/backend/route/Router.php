<?php

require_once __DIR__ . '/../route/impl/BaseRoute.php';
require_once __DIR__ . '/../route/impl/UserRoute.php';
require_once __DIR__ . '/../route/impl/UserListRoute.php';
require_once __DIR__ . '/../route/impl/PostListRoute.php';

/**
 * A router for all of the API Routes
 * @see Route
 */
class Router {
    private $routes;

    public function __construct() {
        $this -> routes = [
            new BaseRoute(),
            new UserRoute(),
            new UserListRoute(),
            new PostListRoute()
        ];
    }

    /**
     * Gets the route for a given path.
     * 
     * @param   string[]  $parts  the path
     * @return  Route|false       returns false if the route is not found
     */
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
     * Handle CORS OPTIONS request and respond appropriately
     */
    function handleCors() {
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            exit(0);
        }
    }
}