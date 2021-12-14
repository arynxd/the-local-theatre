<?php

namespace TLT\Util;

use TLT\Util\Assert\Assertions;

class AuthUtil {
    const TOKEN_LENGTH = 32;

    public static function generateToken() {
        $tok = openssl_random_pseudo_bytes(self::TOKEN_LENGTH);
        Assertions ::assertNotFalse($tok, "Token was not generated? Is the system setup correctly?");
        return bin2hex($tok);
    }

    public static function verifyPassword($rawPassword, $hashed) {
        return password_verify($rawPassword, $hashed);
    }

    public static function hashPassword($rawPassword) {
        return password_hash($rawPassword, PASSWORD_DEFAULT);
    }
}
