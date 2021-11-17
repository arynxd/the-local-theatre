<?php

require_once 'Model.php';

class UserModel extends Model {
    /**
     * @var
     */
    public $id;
    /**
     * @var
     */
    public $name;
    /**
     * @var
     */
    public $permissions;
    /**
     * @var
     */
    public $dob;
    /**
     * @var
     */
    public $joinDate;
    /**
     * @var string
     */
    public $username;
    /**
     * @var
     */
    public $avatar;


    /**
     * Constructs a user model from the given data.
     *
     * @param $id           string  The ID of the user (stored as UUID)
     * @param $name         string  The name of the user
     * @param $permissions  int     The permission level of the user
     * @param $dob
     * @param $joinDate
     * @param $username
     * @param $avatar
     */
    public function __construct($id, $name, $permissions, $dob, $joinDate, $username, $avatar) {
        $this -> id = $id;
        $this -> name = $name;
        $this -> permissions = $permissions;
        $this -> dob = $dob;
        $this -> joinDate = $joinDate;
        $this -> username = $username;
        $this -> avatar = $avatar;
    }

    /**
     * Creates a UserModel from JSON.
     *
     * @param $data Map   The JSON (as a Map instance) to create from
     * @return UserModel  The created user model
     */
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