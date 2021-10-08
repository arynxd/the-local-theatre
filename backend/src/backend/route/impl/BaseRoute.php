<?php

require_once __DIR__ . '/../../util/constant/StatusCode.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';
require_once __DIR__ . '/../../util/constant/ParamSource.php';
require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../model/UserModel.php';
require_once __DIR__ . "/../../util/RequestValidator.php";

class BaseRoute extends Route {
    public function __construct($database) {
        parent::__construct($database, "", [RequestMethod::GET, RequestMethod::POST, RequestMethod::PATCH, RequestMethod::PUT]);
    }

    public function handle($conn, $res) {
        $res -> sendJSON([
            "ok" => true
        ], StatusCode::OK);
    }

    public function validateRequest($conn, $res) {
        return [true];
    }
}