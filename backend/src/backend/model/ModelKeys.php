<?php

namespace TLT\Model;

class ModelKeys {
    const COMMENT_MODEL = ['author', 'postId', 'content', 'createdAt', 'editedAt'];
    const USER_MODEL = ['id', 'firstName', 'lastName', 'permissions', 'dob', 'joinDate', 'username'];
    const USER_UPDATE_MODEL = ['id', 'firstName', 'lastName', 'permissions', 'dob', 'username'];
    const SIGNUP_MODEL = ['firstName', 'lastName', 'username', 'dob', 'email', 'password'];
}
