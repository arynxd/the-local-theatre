<?php

namespace TLT\Util;

class AuthUtil {
    const TOKEN_LENGTH = 32;

    public static function generateToken() {
        return bin2hex(openssl_random_pseudo_bytes(self::TOKEN_LENGTH));
    }

    public static function verifyPassword($rawPassword, $hashed) {
        return password_verify($rawPassword, $hashed);
    }

    public static function hashPassword($rawPassword) {
        return password_hash($rawPassword, PASSWORD_DEFAULT);
    }
}
