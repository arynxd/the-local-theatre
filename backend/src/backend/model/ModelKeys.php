<?php

namespace TLT\Model;

class ModelKeys {
    const POST_MODEL = ['author', 'content', 'title', 'createdAt'];
    const COMMENT_MODEL = ['authorId', 'postId', 'content', 'createdAt', 'editedAt'];
    const USER_MODEL = ['id', 'firstName', 'lastName', 'permissions', 'dob', 'joinDate', 'username'];
    const USER_UPDATE_MODEL = ['id', 'firstName', 'lastName', 'permissions', 'dob', 'username'];
    const SIGNUP_MODEL = ['firstName', 'lastName', 'username', 'dob', 'email', 'password'];
}
