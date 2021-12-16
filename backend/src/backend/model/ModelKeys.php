<?php

namespace TLT\Model;

class ModelKeys {
    const POST_MODEL = ['content', 'title'];
    const COMMENT_MODEL = ['postId', 'content'];
    const USER_MODEL = ['id', 'firstName', 'lastName', 'permissions', 'dob', 'joinDate', 'username'];
    const USER_UPDATE_MODEL = ['id', 'firstName', 'lastName', 'permissions', 'dob', 'username'];
    const SIGNUP_MODEL = ['firstName', 'lastName', 'username', 'dob', 'email', 'password'];
}
