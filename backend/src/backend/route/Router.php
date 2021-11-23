<?php

require_once __DIR__ . '/../route/impl/AvatarRoute.php';
require_once __DIR__ . '/../route/impl/BaseRoute.php';
require_once __DIR__ . '/../route/impl/CommentListRoute.php';
require_once __DIR__ . '/../route/impl/CommentRoute.php';
require_once __DIR__ . '/../route/impl/LoginRoute.php';
require_once __DIR__ . '/../route/impl/ModerationRoute.php';
require_once __DIR__ . '/../route/impl/PostListRoute.php';
require_once __DIR__ . '/../route/impl/PostRoute.php';
require_once __DIR__ . '/../route/impl/SelfUserRoute.php';
require_once __DIR__ . '/../route/impl/ShowImageRoute.php';
require_once __DIR__ . '/../route/impl/ShowListRoute.php';
require_once __DIR__ . '/../route/impl/SignupRoute.php';
require_once __DIR__ . '/../route/impl/UserListRoute.php';
require_once __DIR__ . '/../route/impl/UserPreferencesRoute.php';
require_once __DIR__ . '/../route/impl/UserRoute.php';

/**
 * A router for all the API Routes
 * @see Route
 */
class Router {
    private $routes;

    public function __construct() {
        $this -> routes = [
            new AvatarRoute(),
            new BaseRoute(),
            new CommentListRoute(),
            new CommentRoute(),
            new LoginRoute(),
            new ModerationRoute(),
            new PostListRoute(),
            new PostRoute(),
            new SelfUserRoute(),
            new ShowImageRoute(),
            new ShowListRoute(),
            new SignupRoute(),
            new UserListRoute(),
            new UserPreferencesRoute(),
            new UserRoute(),
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
            header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            header("Access-Control-Allow-Origin: *");
            exit(0);
        }
    }
}