<?php

namespace TLT\Model;

class ModelKeys {
    public static function POST_MODEL() {
        return ['content', 'title'];
    }

    public static function COMMENT_MODEL() {
        return ['content'];
    }

    public static function USER_MODEL() {
        return ['id', 'firstName', 'lastName', 'permissions', 'dob', 'joinDate', 'username'];
    }

    public static function USER_UPDATE_MODEL() {
        return ['id', 'firstName', 'lastName', 'permissions', 'dob', 'username'];
    }

    public static function SIGNUP_MODEL() {
        return ['firstName', 'lastName', 'username', 'dob', 'email', 'password'];
    }
}
