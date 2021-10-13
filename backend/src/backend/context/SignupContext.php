<?php

require_once __DIR__ . '/../util/Authentication.php';
require_once 'Context.php';

class SignupContext extends Context {
    function hasAccount() {
        return true;
    }

    function login() {
        return Authentication::generateToken();
    }

    function createAccount() {
        return $this -> login();
    }

    public function save() {
        // TODO: Implement save() method.
    }
}