<?php

require_once 'Model.php';

class UserModel extends Model {
    public $id;
    public $name;
    public $permissions;
    public $dob;
    public $joinDate;
    public $username;

    public function __construct($id, $name, $permissions, $dob, $joinDate, $username) {
        $this -> id = $id;
        $this -> name = $name;
        $this -> permissions = $permissions;
        $this -> dob = $dob;
        $this -> joinDate = $joinDate;
        $this -> username = $username;
    }

    public function toJSON() {
        return json_encode([
            'id'          => $this -> id,
            'name'        => $this -> name,
            'permissions' => $this -> permissions,
            'dob'         => $this -> dob,
            'joinDate'    => $this -> joinDate,
            'username'    => $this -> username
        ]);
    }
}