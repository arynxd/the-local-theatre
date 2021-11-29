<?php

namespace TLT\Routing;

use TLT\Routing\Impl\AvatarRoute;
use TLT\Routing\Impl\BaseRoute;
use TLT\Routing\Impl\CommentListRoute;
use TLT\Routing\Impl\CommentRoute;
use TLT\Routing\Impl\LoginRoute;
use TLT\Routing\Impl\ModerationRoute;
use TLT\Routing\Impl\PostListRoute;
use TLT\Routing\Impl\PostRoute;
use TLT\Routing\Impl\SelfUserRoute;
use TLT\Routing\Impl\ShowImageRoute;
use TLT\Routing\Impl\ShowListRoute;
use TLT\Routing\Impl\SignupRoute;
use TLT\Routing\Impl\UserListRoute;
use TLT\Routing\Impl\UserPreferencesRoute;
use TLT\Routing\Impl\UserRoute;

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
     * Gets the routing for a given path.
     *
     * @param string[] $parts the path
     * @return  Route|false       returns false if the routing is not found
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
}