<?php

class Authentication {
    const TOKEN_LENGTH = 64;

    public static function generateToken() {
        return openssl_random_pseudo_bytes(self::TOKEN_LENGTH);
    }
}
