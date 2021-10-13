<?php

// Set a new signup entry
// POST

require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . '/../../route/RouteValidationResult.php';
require_once __DIR__ . '/../../context/SignupContext.php';
require_once __DIR__ . '/../../model/SignupUser.php';

class SignupRoute extends Route {
    public function __construct() {
        parent ::__construct("signup", [RequestMethod::POST]);
    }

    public function handle($conn, $res) {
        $model = SignupUser ::fromJSON($conn -> jsonParams()['data']);
        $ctx = new SignupContext($conn, $model);

        if ($ctx -> hasAccount()) {
            $res -> sendJSON([
                "token" => $ctx -> login()
            ], StatusCode::OK);
        }
    }

    public function validateRequest($conn, $res) {
        $data = $conn -> jsonParams()['data'];

        if (!isset($data)) {
            return BadRequest("No Data Provided");
        }

        $validator = new ModelValidatorMiddleware(Keys::SIGNUP_MODEL, $data, "Invalid Data Provided");
        $conn -> applyMiddleware($validator);

        return Ok();
    }
}

