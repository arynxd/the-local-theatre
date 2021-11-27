<?php
require_once __DIR__ . '/../../util/constant/StatusCode.php';
require_once __DIR__ . '/../../util/constant/CORS.php';
require_once __DIR__ . '/../../util/constant/RequestMethod.php';
require_once __DIR__ . '/../../util/data.php';
require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../route/RouteValidationResult.php';

class ShowImageRoute extends Route {
    public function __construct() {
        parent ::__construct("show/image", [RequestMethod::GET]);
    }

    public function handle($sess, $res) {
        $id = $sess -> queryParams()['id'];

        if (!isset($id)) {
            throw new UnexpectedValueException("ID was not set.");
        }

        readDataOrDefault("shows/$id.png", "shows/show.png", ContentType::PNG, CORS::ALL, StatusCode::OK);
    }

    public function validateRequest($sess, $res) {
        if (!isset($sess -> queryParams()['id'])) {
            return BadRequest("No ID provided.");
        }
        return Ok();
    }
}