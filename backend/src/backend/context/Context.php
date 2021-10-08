<?php

abstract class Context {
    public $database;
    public $model;

    public function __construct($database, $model) {
        $this -> database    = $database;
        $this -> model       = $model;
    }

    public abstract function save();
}