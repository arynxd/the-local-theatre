<?php

namespace TLT\Request;

use TLT\Middleware\Middleware;
use TLT\Request\Module\Impl\AuthModule;
use TLT\Request\Module\Impl\CacheModule;
use TLT\Request\Module\Impl\ConfigModule;
use TLT\Request\Module\Impl\DatabaseModule;
use TLT\Request\Module\Impl\DataModule;
use TLT\Request\Module\Impl\HttpModule;
use TLT\Request\Module\Impl\RoutingModule;
use TLT\Util\Data\Map;
use TLT\Util\Enum\ParamSource;
use UnexpectedValueException;

/**
 * Central object representing a single connection, a session.
 *
 * Holds various modules to interact with all aspects of the application
 *
 * This class should only be constructed once.
 */
class Session {
    public $res;

    /**
     * The HTTP module for this session
     *
     * @var HttpModule $http
     */
    public $http;

    /**
     * The routing module for this session
     *
     * @var RoutingModule $routing
     */
    public $routing;

    /**
     * The data module for this session
     *
     * @var DataModule $data
     */
    public $data;

    /**
     * The cache module for this session
     *
     * @var CacheModule $cache
     */
    public $cache;


    /**
     * The config module for this session
     *
     * @var ConfigModule $cfg
     */
    public $cfg;

    /**
     * The auth module for this session
     *
     * @var AuthModule $auth
     */
    public $auth;

    /**
     * The database module for this session
     *
     *
     * @var DatabaseModule $db
     */
    public $db;


    public function __construct() {
        $this -> res = new Response();

        // init modules
        $this -> cfg = new ConfigModule($this);
        $this -> http = new HttpModule($this);
        $this -> routing = new RoutingModule($this);
        $this -> data = new DataModule($this);
        $this -> cache = new CacheModule($this);
        $this -> db = new DatabaseModule($this);

        $all = [
            $this -> cfg,
            $this -> http,
            $this -> routing,
            $this -> data,
            $this -> cache,
            $this -> db
        ];

        foreach ($all as $mod) {
            $mod -> onEnable();
        }
    }

    public function jsonParams() {
        return $this -> parseParams(ParamSource::JSON);
    }

    private function parseParams($source) {
        if ($source == ParamSource::QUERY) {
            return Map ::from($_GET);
        }
        else if ($source == ParamSource::JSON) {
            $result = json_decode(file_get_contents('php://input'), true);
            if (!isset($result)) {
                return Map ::none();
            }

            $result = Map ::from($result);

            return $result -> mapRecursive(function ($_, $value) {
                if (is_array($value)) {
                    return Map ::from($value);
                }
                return $value;
            });

        }

        throw new UnexpectedValueException("Unexpected ParamSource $source");
    }

    public function queryParams() {
        return $this -> parseParams(ParamSource::QUERY);
    }

    /**
     * Applies a middleware to the session
     *
     * If the middleware fails, the connection is terminated with an error
     *
     * @param Middleware $middleware The middleware to apply
     */
    public function applyMiddleware($middleware) {
        $wareResult = $middleware -> apply($this);
        if ($wareResult -> isError()) {
            $this -> res -> sendError($wareResult -> error, $wareResult -> httpCode, ...$wareResult -> headers);
        }
    }
}