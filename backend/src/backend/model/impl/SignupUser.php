<?php

namespace TLT\Model\Impl;

use TLT\Model\Model;
use TLT\Util\Data\Map;

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

    public function toMap() {
        return Map ::from(
            [
                'name' => $this -> name,
                'password' => $this -> password,
                'email' => $this -> email,
                'username' => $this -> username
            ]
        );
    }
}