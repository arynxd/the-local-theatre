<?php

class RequestValidator {
    private $required;

    public function __construct($required) {
        $this -> required = $required;
    }

    public function validate($data) {
        foreach ($this -> required as $key) {
            if (!isset($data[$key])) {
                return false;
            }
        }
        return true;
    }
}

class Keys {
    const USER_MODEL = ['id', 'name'];
}