<?php

class SignupUser extends Model {
    public $name;
    public $password;
    public $email;
    public $username;

    public function __construct($name, $password, $email, $username) {
        $this -> name = $name;
        $this -> password = $password;
        $this -> email = $email;
        $this -> username = $username;
    }

    public static function fromJSON($data) {
        return new SignupUser(
            $data['name'],
            $data['password'],
            $data['email'],
            $data['username']
        );
    }

    public function toJSON() {
        return [
            'name' => $this -> name,
            'password' => $this -> password,
            'email' => $this -> email,
            'username' => $this -> username
        ];
    }
}