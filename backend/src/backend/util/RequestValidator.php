<?php

class RequestValidator {
    private $required;
    private $data;

    public function __construct($required, $data) {
        $this -> required = $required;
        $this -> data = $data;
    }

    public function validate() {
        foreach ($this -> required as $key) {
            if (!$this -> data -> exists($key)) {
                return false;
            }
        }
        return true;
    }
}

class Keys {
    const USER_MODEL = ['id', 'name', 'permissions', 'dob', 'joinDate', 'username'];
    const SIGNUP_MODEL = ['firstName', 'lastName', 'username', 'email', 'password'];
}
