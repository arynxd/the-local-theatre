<?php

abstract class Context {
    public $conn;
    public $model;

    public function __construct($conn, $model) {
        $this -> conn = $conn;
        $this -> model = $model;
    }

    public abstract function save();
}