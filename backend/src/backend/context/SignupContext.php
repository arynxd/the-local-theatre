<?php

require_once __DIR__ . '/../util/Authentication.php';
require_once 'Context.php';

class SignupContext extends Context {
    function createAccount() {
        return $this -> login();
    }

    function login() {
        return Authentication ::generateToken();
    }

    public function save() {
        // TODO: Implement save() method.
    }
}