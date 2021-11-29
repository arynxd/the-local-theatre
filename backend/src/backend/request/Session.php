<?php

require_once __DIR__ . "/../util/constant/Constants.php";
require_once __DIR__ . "/../util/constant/ParamSource.php";
require_once __DIR__ . "/../util/Logger.php";
require_once __DIR__ . "/../util/Map.php";
require_once __DIR__ . "/../util/JSONLoader.php";
require_once __DIR__ . "/../util/string.php";
require_once __DIR__ . "/../route/Router.php";
require_once __DIR__ . "/Response.php";

require_once __DIR__ . 'module/AuthModule.php';
require_once __DIR__ . 'module/CacheModule.php';
require_once __DIR__ . 'module/ConfigModule.php';
require_once __DIR__ . 'module/DatabaseModule.php';
require_once __DIR__ . 'module/DataModule.php';
require_once __DIR__ . 'module/HttpModule.php';
require_once __DIR__ . 'module/RoutingModule.php';

/**
 * Central object representing a single connection, a session.
 *
 * Holds various modules to interact with all aspects of the application
 *
 * This class should only be constructed once.
 */
class Session {
    public $res;
    public $logger;


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
        $this -> logger = new Logger($this);

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
            return new Map($_GET);
        }
        else if ($source == ParamSource::JSON) {
            $result = json_decode(file_get_contents('php://input'), true);
            if (!isset($result)) {
                return new Map([]);
            }

            $result = new Map($result);

            return $result -> mapRecursive(function ($_, $value) {
                if (is_array($value)) {
                    return new Map($value);
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