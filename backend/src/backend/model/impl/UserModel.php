<?php

namespace TLT\Model\Impl;

use TLT\Model\Model;
use TLT\Util\Data\Map;

class UserModel extends Model {
    /**
     * @var
     */
    public $id;
    /**
     * @var
     */
    public $firstName;

    /**
     * @var
     */
    public $lastName;
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

    public function __construct($id, $firstName, $lastName, $permissions, $dob, $joinDate, $username) {
        $this -> id = $id;
        $this -> firstName = $firstName;
        $this -> lastName = $lastName;
        $this -> permissions = $permissions;
        $this -> dob = $dob;
        $this -> joinDate = $joinDate;
        $this -> username = $username;
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
            $data['firstName'],
            $data['lastName'],
            $data['permissions'],
            $data['dob'],
            $data['joinDate'],
            $data['username']
        );
    }

    public function toMap() {
        return Map ::from(
            [
                'id' => $this -> id,
                'firstName' => $this -> firstName,
                'lastName' => $this -> lastName,
                'permissions' => (int) $this -> permissions,
                'dob' => (int) $this -> dob,
                'joinDate' => (int) $this -> joinDate,
                'username' => $this -> username
            ]
        );
    }
}