<?php

namespace TLT\Request;

use Exception;
use TLT\Middleware\BaseMiddleware;
use TLT\Request\Module\Impl\AuthModule;
use TLT\Request\Module\Impl\CacheModule;
use TLT\Request\Module\Impl\ConfigModule;
use TLT\Request\Module\Impl\DatabaseModule;
use TLT\Request\Module\Impl\DataModule;
use TLT\Request\Module\Impl\HttpModule;
use TLT\Request\Module\Impl\RoutingModule;
use TLT\Util\Assert\Assertions;
use TLT\Util\Data\Map;
use TLT\Util\Enum\ParamSource;
use TLT\Util\Log\Logger;

/**
 * Central object representing a single connection, a session.
 *
 * Holds various modules to interact with all aspects of the application
 *
 * This class should only be constructed once.
 */
class Session {
    /**
     * The response object for this session
     *
     * @var Response $res
     */
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
        Logger ::getInstance() -> info("Loading modules...");
        $this -> res = new Response();

        // init modules
        $this -> cfg = new ConfigModule($this);
        $this -> http = new HttpModule($this);
        $this -> routing = new RoutingModule($this);
        $this -> data = new DataModule($this);
        $this -> cache = new CacheModule($this);
        $this -> auth = new AuthModule($this);
        $this -> db = new DatabaseModule($this);
        Logger ::getInstance() -> info("Modules constructed without error, enabling");

        $all = [
            $this -> cfg,
            $this -> http,
            $this -> routing,
            $this -> data,
            $this -> cache,
            $this -> db,
            $this -> auth
        ];

        foreach ($all as $mod) {
            try {
                Logger ::getInstance() -> debug("\tEnabling module " . get_class($mod));
                $mod -> onEnable();
            }
            catch (Exception $ex) {
                Logger ::getInstance() -> error("Module " . get_class($mod) . " encountered an error whilst enabling");
                $this -> res -> sendInternalError($ex);
            }
        }
        Logger ::getInstance() -> info("Modules loaded");
    }

    public function jsonParams() {
        return $this -> parseParams(ParamSource::JSON);
    }

    private function parseParams($source) {
        Logger ::getInstance() -> info("Parsing params from source $source");
        if ($source == ParamSource::QUERY) {
            Logger ::getInstance() -> debug('Query params selected, returning $_GET');
            return Map ::from($_GET);
        }
        else if ($source == ParamSource::JSON) {
            Logger ::getInstance() -> debug("Attempting JSON parse..");
            $raw = file_get_contents('php://input');
            Assertions::assertNotFalse($raw);
            $result = json_decode($raw, true);

            if (!isset($result)) {
                Logger ::getInstance() -> warn("JSON parse failed for input, falling back to empty map");
                Logger ::getInstance() -> warn($raw);
                return Map ::none();
            }

            Logger ::getInstance() -> debug("JSON parse succeeded!");

            $result = Map ::from($result);

            return $result -> mapRecursive(function ($_, $value) {
                if (is_array($value)) {
                    return Map ::from($value);
                }
                return $value;
            });

        }

        Logger ::getInstance() -> fatal("Unexpected ParamSource $source");
    }

    public function queryParams() {
        return $this -> parseParams(ParamSource::QUERY);
    }

    /**
     * Applies a middleware to the session
     *
     * If the middleware fails, the connection is terminated with an error
     *
     * @param BaseMiddleware[] $middlewares The middlewares to apply
     */
    public function applyMiddleware(...$middlewares) {
        Logger ::getInstance() -> info("Applying middlewares..");

        foreach ($middlewares as $middleware) {
            $wareResult = null;
            Logger ::getInstance() -> debug("\tLoading middleware " . get_class($middleware));

            try {
                $wareResult = $middleware -> apply($this);
            }
            catch (Exception $ex) {
                Logger ::getInstance() -> error("An error occurred whilst applying middleware " . get_class($middleware));
                $this -> res -> sendInternalError($ex);
            }

            if ($wareResult -> isError()) {
                $this -> res -> sendError($wareResult -> error, $wareResult -> httpCode, ...$wareResult -> headers);
            }
        }

        Logger ::getInstance() -> info("Middlewares applied without error");
    }
}