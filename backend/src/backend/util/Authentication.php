<?php

class Authentication {
    const TOKEN_LENGTH = 50;

    public static function generateToken() {
        return openssl_random_pseudo_bytes(self::TOKEN_LENGTH);
    }
}