<?php

// Set a new signup entry
// POST

namespace TLT\Routing\Impl;

use TLT\Middleware\Impl\ModelValidatorMiddleware;
use TLT\Model\ModelKeys;
use TLT\Routing\Route;
use TLT\Util\AuthUtil;
use TLT\Util\Assert\Assertions;
use TLT\Util\Data\Map;
use TLT\Util\Enum\RequestMethod;
use TLT\Util\Enum\StatusCode;
use TLT\Util\HttpResult;
use TLT\Util\StringUtil;
use UnexpectedValueException;

class SignupRoute extends Route {
    public function __construct() {
        parent ::__construct("signup", [RequestMethod::POST]);
    }

    public function handle($sess, $res) {
        $data = $sess -> jsonParams()['data'];

        Assertions::assertSet($data);

        foreach (ModelKeys::SIGNUP_MODEL as $key) {
            Assertions::assertSet($data[$key]);
        }

        $userHasAccount = $this -> userHasAccount($sess, $data['email']);

        if ($userHasAccount) {
            $res -> sendError("Account already exists", StatusCode::CONFLICT);
        }
        else {
            $newToken = $this -> createAccount($sess, $data);

            $res -> sendJSON(Map ::from([
                'token' => $newToken
            ]));
        }
    }

    private function userHasAccount($sess, $email) {
        $query = "SELECT COUNT(*) FROM credential WHERE email = :email";
        $res = $sess -> db -> query($query, [
            'email' => $email
        ]);

        $res = $res -> fetchColumn(0);

        Assertions::assertSet($res);

        return $res > 0;
    }

    private function createAccount($sess, $data) {
        $insertIntoUsers = "INSERT INTO user
            (id, firstName, lastName, username, dob, joinDate, permissions) VALUES
            (:id, :firstName, :lastName, :username, :dob, :joinDate, :permissions);";

        $insertIntoCreds = "INSERT INTO credential
            (userId, email, password, token) VALUES
            (:id, :email, :password, :token);";

        $db = $sess -> db;

        $id = StringUtil ::newID();
        $tok = AuthUtil ::generateToken();

        $db -> query($insertIntoUsers, [
            'id' => $id,
            'firstName' => $data['firstName'],
            'lastName' => $data['lastName'],
            'username' => $data['username'],
            'dob' => $data['dob'],
            'joinDate' => time(),
            'permissions' => 1
        ]);

        $db -> query($insertIntoCreds, [
            'id' => $id,
            'email' => $data['email'],
            'password' => AuthUtil ::hashPassword($data['password']),
            'token' => $tok
        ]);

        return $tok;
    }

    public function validateRequest($sess, $res) {
        $data = $sess -> jsonParams()['data'];

        if (!isset($data)) {
            return HttpResult ::BadRequest("No data provided");
        }

        $validator = new ModelValidatorMiddleware(ModelKeys::SIGNUP_MODEL, Map ::from($data), "Invalid data provided");
        $sess -> applyMiddleware($validator);

        return HttpResult ::Ok();
    }
}

