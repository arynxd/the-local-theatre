<?php

require_once __DIR__ . "/../util/constant/Constants.php";
require_once __DIR__ . "/../util/constant/ParamSource.php";
require_once __DIR__ . "/../util/ErrorHandler.php";
require_once __DIR__ . "/../util/JSONLoader.php";
require_once __DIR__ . "/../util/string.php";
require_once __DIR__ . "/../db/Database.php";
require_once __DIR__ . "/../route/Router.php";
require_once __DIR__ . "/Response.php";

class Connection {
    public $res;
    public $config;
    public $database;
    public $rawUri;
    public $uri;
    public $router;
    public $route;
    public $method;
    public $errorHandler;

    public function __construct() {
        $this -> res          = $this -> generateResponse();
        $this -> config       = $this -> loadConfig();
        $this -> database     = $this -> loadDatabase();
        $this -> router       = $this -> loadRouter();
        $this -> rawUri       = $this -> parseRawURI();
        $this -> uri          = $this -> parseURI();
        $this -> router       = $this -> loadRouter();
        $this -> route        = $this -> parseRoute();
        $this -> method       = $this -> parseMethod();
        $this -> errorHandler = $this -> loadErrorHandler();

        $this -> router -> handleCors();
        $this -> errorHandler -> addInterceptor();
    }

    private function loadConfig() {
        $loader = new JSONLoader("./config.json");
        $loader -> load();
        return $loader -> data();
    }

    private function loadRouter() {
        $db = $this -> database;

        if (!isset($db)) {
            throw new UnexpectedValueException("Database was not initialised before loading router.");
        }

        return new Router();
    }

    private function loadDatabase() {
        $cfg = $this -> config;

        if (!isset($cfg)) {
            throw new UnexpectedValueException("Config was not initialised before loading database.");
        }


        return new Database($cfg['db_url'], $cfg['db_username'], $cfg['db_password']);
    }

    private function parseRawURI() {
        return "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    private function parseURI() {
        $result = parse_url($this -> rawUri, PHP_URL_PATH); // get the URI from the request
        $result = explode('/', $result); // split it into an array

        if (isset($result[1]) && strStartsWith($result[1], Constants::API_PREFIX)) {
            $result = array_slice($result, 2);
        }

        if (isset($result[0]) && strStartsWith($result[0], Constants::URI_PREFIX)) {
            $result = array_slice($result, 1);
        }

        return $result;
    }

    private function parseParams($source) {
        if ($source == ParamSource::QUERY) {
            return $_GET;
        }
        else if ($source == ParamSource::JSON) {
            $result = json_decode(file_get_contents('php://input'));
            if (!isset($result)) {
                return [];
            }
            return $result; // read the body
        }
        throw new UnexpectedValueException("Unexpected ParamSource $source");
    }

    private function parseMethod() {
        return $_SERVER["REQUEST_METHOD"];
    }

    private function parseRoute() {
        $result = $this -> router -> getRouteForPath($this -> uri);

        if (!$result) {
            $this -> res -> sendError("Route not found", StatusCode::NOT_FOUND);
            exit;
        }

        return $result;
    }

    private function generateResponse() {
        return new Response();
    }

    public function jsonParams() {
        return $this -> parseParams(ParamSource::JSON);
    }

    public function queryParams() {
        return $this -> parseParams(ParamSource::QUERY);
    }

    public function applyMiddleware($middleware) {
        $wareResult = $middleware -> apply($this);
        if ($wareResult -> isError()) {
            $this -> res -> sendError($wareResult -> error, $wareResult -> code, ...$wareResult -> headers);
        }
    }

    private function loadErrorHandler() {
        return new ErrorHandler($this -> res);
    }
}