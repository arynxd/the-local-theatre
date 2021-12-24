<?php

namespace TLT\Routing\Impl;

use TLT\Routing\BaseRoute;
use TLT\Util\Data\Map;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\HttpResult;
use TLT\Util\Log\Logger;

class DefaultRoute extends BaseRoute {
    public function __construct() {
        parent ::__construct("", [RequestMethod::GET, RequestMethod::POST, RequestMethod::PATCH, RequestMethod::PUT]);
    }

    public function handle($sess, $res) {
        $res -> status(200)
             -> cors("all")
             -> json([
                "ok" => true,
                "log_path" => Logger ::getInstance() -> getLogFile()
             ]);
    }

    public function validateRequest($sess, $res) {
        return HttpResult ::Ok();
    }
}
