<?php

// Post the login details for a user with the given <email> and <password> combo (hashed)
// POST

namespace TLT\Routing\Impl;


use TLT\Routing\Route;
use TLT\Util\Auth\AuthUtil;
use TLT\Util\Data\Map;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\Result;
use UnexpectedValueException;

class LoginRoute extends Route {
    public function __construct() {
        parent ::__construct("login", [RequestMethod::POST]);
    }

    public function handle($sess, $res) {
        $ERR = "Account does not exist, or email / password was incorrect.";

        $data = $sess -> jsonParams()['data'];

        if (!isset($data)) {
            throw new UnexpectedValueException("Data did not exist, validation must have failed");
        }

        $email = $data['email'];
        $password = $data['password'];

        if (!isset($email) || !isset($password)) {
            throw new UnexpectedValueException("Email or password did not exist, validation must have failed");
        }

        $accountDetails = $sess -> db -> query("SELECT * FROM credential WHERE email = :email", [
            'email' => $email
        ]);

        $accountDetails = $accountDetails -> fetch();

        if (!$accountDetails) {
            $res -> sendError($ERR, StatusCode::UNAUTHORIZED);
        }

        $accountDetails = Map::from($accountDetails);
        if ($accountDetails -> length() == 0) {
            $res -> sendError($ERR, StatusCode::UNAUTHORIZED);
        }

        if (!AuthUtil::verifyPassword($password, $accountDetails['password'])) {
            $res -> sendError($ERR, StatusCode::UNAUTHORIZED);
        }

        $res -> sendJSON(Map::from([
            "token" => $accountDetails['token']
        ]), StatusCode::OK);
    }

    public function validateRequest($sess, $res) {
        $data = $sess -> jsonParams()['data'];

        if (!isset($data)) {
            return Result::Unprocessable("No data was passed.");
        }

        $email = $data['email'];
        $pwd = $data['password'];

        if (!isset($email)) {
            return Result::Unprocessable("No email was passed");
        }

        if (!isset($pwd)) {
            return Result::Unprocessable("No password was passed");
        }

        return Result::Ok();
    }
}