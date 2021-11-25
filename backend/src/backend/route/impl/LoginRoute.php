<?php

// Post the login details for a user with the given <email> and <password> combo (hashed)
// POST

require_once __DIR__ . '/../../route/Route.php';
require_once __DIR__ . "/../../middleware/impl/ModelValidatorMiddleware.php";
require_once __DIR__ . '/../../util/constant/RequestMethod.php';
require_once __DIR__ . '/../../util/Map.php';
require_once __DIR__ . '/../../util/auth.php';


class LoginRoute extends Route {
    public function __construct() {
        parent ::__construct("login", [RequestMethod::POST]);
    }

    public function handle($sess, $res) {
        $data = $sess -> jsonParams()['data'];

        if (!isset($data)) {
            throw new UnexpectedValueException("Data did not exist, validation must have failed");
        }

        $email = $data['email'];
        $password = $data['password'];

        if (!isset($email) || !isset($password)) {
            throw new UnexpectedValueException("Email or password did not exist, validation must have failed");
        }

        $accountDetails = $sess -> database -> query("SELECT * FROM credential WHERE email = :email", [
            'email' => $email
        ]);

        $accountDetails = $accountDetails -> fetch();
        $accountDetails = map($accountDetails);

        if ($accountDetails -> length() == 0) {
            $res -> sendError("Account does not exist, or email / password was incorrect.", StatusCode::FORBIDDEN);
        }

        if (!verifyPassword($password, $accountDetails['password'])) {
            $res -> sendError("Account does not exist, or email / password was incorrect.", StatusCode::FORBIDDEN);
        }

        $res -> sendJSON(map([
            "token" => $accountDetails['token']
        ]), StatusCode::OK);
    }

    public function validateRequest($sess, $res) {
        $data = $sess -> jsonParams()['data'];

        if (!isset($data)) {
            return Unprocessable("No data was passed.");
        }

        $email = $data['email'];
        $pwd = $data['password'];

        if (!isset($email)) {
            return Unprocessable("No email was passed");
        }

        if (!isset($pwd)) {
            return Unprocessable("No password was passed");
        }

        return Ok();
    }
}