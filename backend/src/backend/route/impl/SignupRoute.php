<?php

// Set a new signup entry
// POST

require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../route/RouteValidationResult.php';
require_once __DIR__ . '/../../model/SignupUser.php';
require_once __DIR__ . '/../../util/Map.php';

class SignupRoute extends Route {
    public function __construct() {
        parent ::__construct("signup", [RequestMethod::POST]);
    }

    public function handle($sess, $res) {
        $data = $sess -> jsonParams()['data'];

        if (!isset($data)) {
            throw new UnexpectedValueException("Data did not exist? Initial validation failed.");
        }

        foreach (ModelKeys::SIGNUP_MODEL as $key) {
            if (!isset($data[$key])) {
                throw new UnexpectedValueException("Data key $key did not exit? Initial validation failed.");
            }
        }



        $res -> sendJSON(map(["token" => 'aaaaaabbbbddddd']));
    }

    public function validateRequest($sess, $res) {
        $data = $sess -> jsonParams()['data'];

        if (!isset($data)) {
            return BadRequest("No Data Provided");
        }

        $validator = new ModelValidatorMiddleware(ModelKeys::SIGNUP_MODEL, $data, "Invalid data provided");
        $sess -> applyMiddleware($validator);

        return Ok();
    }
}

