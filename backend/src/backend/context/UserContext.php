<?php

require_once 'Context.php';


class UserContext extends Context {

    function login() {
        return Authentication ::generateToken();
    }

    public function promote() {
        // alter model
    }

    public function save() {
        // save model to database here
    }
}