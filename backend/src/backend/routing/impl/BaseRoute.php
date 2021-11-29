<?php

namespace TLT\Routing\Impl;

use TLT\Routing\Route;
use TLT\Util\Data\Map;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\Result;

class BaseRoute extends Route {
    public function __construct() {
        parent ::__construct("", [RequestMethod::GET, RequestMethod::POST, RequestMethod::PATCH, RequestMethod::PUT]);
    }

    public function handle($sess, $res) {
        $res -> sendJSON(Map::from([
            "ok" => true
        ]), StatusCode::OK);
    }

    public function validateRequest($sess, $res) {
        return Result::Ok();
    }
}
