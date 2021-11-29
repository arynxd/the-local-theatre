<?php

namespace TLT\Routing\Impl;


use TLT\Routing\Route;
use TLT\Util\Data\DataUtil;
use TLT\Util\Enum\ContentType;
use TLT\Util\Enum\CORS;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\Result;
use UnexpectedValueException;

class ShowImageRoute extends Route {
    public function __construct() {
        parent ::__construct("show/image", [RequestMethod::GET]);
    }

    public function handle($sess, $res) {
        $id = $sess -> queryParams()['id'];

        if (!isset($id)) {
            throw new UnexpectedValueException("ID was not set.");
        }

        DataUtil ::readOrDefault(
            "shows/$id.png",
            "shows/show.png",
            ContentType::PNG, CORS::ALL, StatusCode::OK
        );
    }

    public function validateRequest($sess, $res) {
        if (!isset($sess -> queryParams()['id'])) {
            return Result ::BadRequest("No ID provided.");
        }
        return Result ::Ok();
    }
}