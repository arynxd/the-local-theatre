<?php

require_once __DIR__ . "/../util/constant/Constants.php";
require_once __DIR__ . "/../util/constant/ParamSource.php";

class Connection {
    /**
     * @var Database the database to use
     */
    public $database;
    public $rawUri;
    public $uri;
    public $router;
    public $route;
    public $method;

    public function __construct($database, $router) {
        $this -> database = $database;
        $this -> rawUri   = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this -> uri      = $this -> parseURI();
        $this -> router   = $router;
        $this -> route    = $this -> parseRoute();
        $this -> method   = $this -> parseMethod();
    }

    private function parseURI() {
        $res = parse_url($this -> rawUri, PHP_URL_PATH); // get the URI from the request
        $res = explode('/', $res);                       // split it into an array

        if (isset($res[1]) && $res[1] == Constants::URI_PREFIX) {
            $res = array_slice($res, 2);
        }

        if (isset($res[0]) && $res[0] == Constants::API_PREFIX) {
            $res = array_slice($res, 1);
        }

        return $res;
    }

    public function jsonParams() {
        return $this -> parseParams(ParamSource::JSON);
    }

    public function queryParams() {
        return $this -> parseParams(ParamSource::QUERY);
    }

    private function parseParams($source) {
        if ($source == ParamSource::QUERY) {
            return $_GET;
        }
        else if ($source == ParamSource::JSON) {
            return json_decode(file_get_contents('php://input')); // read the body
        }
    }

    private function parseMethod() {
        return $_SERVER["REQUEST_METHOD"];
    }

    private function parseRoute() {
        $result = $this -> router -> getRouteForPath($this -> uri);

        if (!$result) {
            header(StatusCode::NOT_FOUND);
            echo "Could not find route";
            exit;
        }

        return $result;
    }
}