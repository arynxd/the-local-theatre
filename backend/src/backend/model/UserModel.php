<?php

require_once 'Model.php';

class UserModel extends Model {
    public $id;
    public $name;

    public function __construct($id, $name) {
        $this -> id = $id;
        $this -> name = $name;
    }

    public function toJSON() {
        return json_encode([
            'id' => $this -> id,
            'name' => $this -> name
        ]);
    }
}