<?php

function TOKEN_LENGTH() { return 32; }

function generateToken() {
    return bin2hex(openssl_random_pseudo_bytes(TOKEN_LENGTH()));
}

function verifyPassword($rawPassword, $hashed) {
    return password_verify($rawPassword, $hashed);
}

function hashPassword($rawPassword) {
    return password_hash($rawPassword, PASSWORD_DEFAULT);
}
