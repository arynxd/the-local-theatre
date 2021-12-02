<?php

// Post the login details for a user with the given <email> and <password> combo (hashed)
// POST

namespace TLT\Routing\Impl;


use TLT\Middleware\Impl\DatabaseMiddleware;
use TLT\Routing\BaseRoute;
use TLT\Util\Assert\Assertions;
use TLT\Util\AuthUtil;
use TLT\Util\Data\Map;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\HttpResult;

class LoginRoute extends BaseRoute {
    public function __construct() {
        parent ::__construct("login", [RequestMethod::POST]);
    }

    public function handle($sess, $res) {
        $ERR = "Account does not exist, or email / password was incorrect.";

        $data = $sess -> jsonParams()['data'];

        Assertions ::assertSet($data);

        $email = $data['email'];
        $password = $data['password'];

        Assertions ::assertSet($email);
        Assertions ::assertSet($password);

        $accountDetails = $sess -> db -> query("SELECT * FROM credential WHERE email = :email", [
            'email' => $email
        ]);

        $accountDetails = $accountDetails -> fetch();

        if (!$accountDetails) {
            $res -> sendError($ERR, StatusCode::UNAUTHORIZED);
        }

        $accountDetails = Map ::from($accountDetails);
        if ($accountDetails -> length() == 0) {
            $res -> sendError($ERR, StatusCode::UNAUTHORIZED);
        }

        if (!AuthUtil ::verifyPassword($password, $accountDetails['password'])) {
            $res -> sendError($ERR, StatusCode::UNAUTHORIZED);
        }

        $res -> sendJSON(Map ::from([
            "token" => $accountDetails['token']
        ]), StatusCode::OK);
    }

    public function validateRequest($sess, $res) {
        $sess -> applyMiddleware(new DatabaseMiddleware());

        $data = $sess -> jsonParams()['data'];

        if (!isset($data)) {
            return HttpResult ::Unprocessable("No data was passed.");
        }

        $email = $data['email'];
        $pwd = $data['password'];

        if (!isset($email)) {
            return HttpResult ::Unprocessable("No email was passed");
        }

        if (!isset($pwd)) {
            return HttpResult ::Unprocessable("No password was passed");
        }

        return HttpResult ::Ok();
    }
}