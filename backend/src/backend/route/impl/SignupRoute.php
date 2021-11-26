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

    private function userHasAccount($sess, $email) {
        $query = "SELECT COUNT(*) FROM credential WHERE email = :email";
        $res = $sess -> database -> query($query,[
            'email' => $email
        ]);

        $res = $res -> fetchColumn(0);

        if (!isset($res)) {
            throw new UnexpectedValueException("Count did not exist");
        }

        return $res > 0;
    }

    private function createAccount($sess, $data) {
        $insertIntoUsers = "INSERT INTO user
            (id, firstName, lastName, username, dob, joinDate, permissions) VALUES
            (:id, :firstName, :lastName, :username, :dob, :joinDate, :permissions);";

        $insertIntoCreds = "INSERT INTO credential
            (userId, email, password, token) VALUES
            (:id, :email, :password, :token);";

        $db = $sess -> database;

        $id = createIdentifier();
        $tok = generateToken();

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
            'password' => hashPassword($data['password']),
            'token' => $tok
        ]);

        return $tok;
    }

    public function handle($sess, $res) {
        $data = $sess -> jsonParams()['data'];

        if (!isset($data)) {
            throw new UnexpectedValueException("Data did not exist? Initial validation failed.");
        }

        foreach (ModelKeys::SIGNUP_MODEL as $key) {
            if (!isset($data[$key])) {
                throw new UnexpectedValueException("Data key $key did not exist? Initial validation failed.");
            }
        }

        $userHasAccount = $this -> userHasAccount($sess, $data['email']);

        if ($userHasAccount) {
            $res -> sendError("Account already exists", StatusCode::CONFLICT);
        }
        else {
            $newToken = $this -> createAccount($sess, $data);

            $res -> sendJSON(map([
                'token' => $newToken
            ]));
        }
    }

    public function validateRequest($sess, $res) {
        $data = $sess -> jsonParams()['data'];

        if (!isset($data)) {
            return BadRequest("No data provided");
        }

        $validator = new ModelValidatorMiddleware(ModelKeys::SIGNUP_MODEL, map($data), "Invalid data provided");
        $sess -> applyMiddleware($validator);

        return Ok();
    }
}

