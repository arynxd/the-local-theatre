<?php

namespace TLT\Model\Impl;

use TLT\Model\Model;
use TLT\Util\Data\Map;
use TLT\Util\Enum\Constants;

class SelfUserModel extends UserModel {

    public $email;
    public function __construct($id, $firstName, $lastName, $email, $permissions, $dob, $joinDate, $username) {
        parent::__construct($id, $firstName, $lastName, $permissions, $dob, $joinDate, $username);
        $this -> email = $email;
    }

    /**
     * Creates a UserModel from JSON.
     *
     * @param $data Map   The JSON (as a Map instance) to create from
     * @return UserModel  The created user model
     */
    public static function fromJSON($data) {
        return new SelfUserModel(
            $data['id'],
            $data['firstName'],
            $data['lastName'],
            $data['email'],
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
                'email' => $this -> email,
                'permissions' => (int)$this -> permissions,
                'dob' => (int)$this -> dob,
                'joinDate' => (int)$this -> joinDate,
                'username' => $this -> username
            ]
        );
    }
}