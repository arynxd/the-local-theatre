<?php

namespace TLT\Util\Polyfill;

class Polyfill {
    public static function applyPollyfills() {
        require_once 'password_hash.php';
    }
}