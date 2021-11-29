<?php

namespace TLT\Request\Module\Impl;

use TLT\Request\Module\Module;
use TLT\Util\Enum\Constants;
use TLT\Util\StringUtil;

class HttpModule extends Module {
    /**
     *
     * @var string $rawUri
     */
    public $rawUri;

    /**
     * @var array $uri
     */
    public $uri;

    /**
     * The HTTP method, GET POST PUT PATCH DELETE
     *
     * @var string $method
     */
    public $method;

    public function onEnable() {
        $this -> handleCors();
        $this -> method = $_SERVER["REQUEST_METHOD"];
        $this -> rawUri = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this -> uri = $this -> parseURI();
    }

    /**
     * Handle CORS OPTIONS request and respond appropriately
     */
    private function handleCors() {
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header("Access-Control-Allow-Methods: *");
            header("Access-Control-Allow-Headers: *");
            header("Access-Control-Allow-Origin: *");
            exit(0);
        }
    }

    /**
     * Parses the raw uri into its components
     * This function strips the start of the URL, leaving just the routing
     *
     * @return array The parsed URI
     */
    private function parseURI() {
        $map = parse_url($this -> rawUri, PHP_URL_PATH); // get the URI from the request
        $map = explode('/', $map); // split it into an array
        $map = array_slice($map, 1); // remove weird empty argument at the start

        if (isset($map[0]) && StringUtil ::startsWith($map[0], Constants::URI_PREFIX)) {
            $map = array_slice($map, 1);
        }

        if (isset($map[0]) && StringUtil ::startsWith($map[0], Constants::API_PREFIX)) {
            $map = array_slice($map, 1);
        }

        return $map;
    }
}