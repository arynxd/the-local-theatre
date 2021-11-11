<?php

require_once 'Model.php';

class UserModel extends Model {
    public $id;
    public $name;
    public $permissions;
    public $dob;
    public $joinDate;
    public $username;
    public $avatar;

    public function __construct($id, $name, $permissions, $dob, $joinDate, $username, $avatar) {
        $this -> id = $id;
        $this -> name = $name;
        $this -> permissions = $permissions;
        $this -> dob = $dob;
        $this -> joinDate = $joinDate;
        $this -> username = $username;
        $this -> avatar = $avatar;
    }

    public static function fromJSON($data) {
        return new UserModel(
            $data['id'],
            $data['name'],
            $data['permissions'],
            $data['dob'],
            $data['joinDate'],
            $data['username'],
            $data['avatar']
        );
    }

    public function toMap() {
        return new Map(
            [
                'id' => $this -> id,
                'name' => $this -> name,
                'permissions' => $this -> permissions,
                'dob' => $this -> dob,
                'joinDate' => $this -> joinDate,
                'username' => $this -> username,
                'avatar' => $this -> avatar
            ]
        );
    }
}