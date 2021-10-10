<?php

require_once 'Context.php';
require_once __DIR__ . '/../util/Authentication.php';

class UserContext extends Context {

    function login() {
        return Authentication ::generateToken();
    }

    public function hasAccount() {
        return true;
    }

    public function promote() {
        // alter model
    }

    public function save() {
        // save model to database here
    }
}