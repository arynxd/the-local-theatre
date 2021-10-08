<?php

class JSONLoader {
    private $path;
    private $data;

    public function __construct($path) {
        $this -> path = $path;
        $this -> data = null;
    }

    public function load() {
        $json = json_decode(file_get_contents($this -> path), true);
        $this -> data = $json;
    }

    public function data() {
        if (!isset($this -> data)) {
            throw new UnexpectedValueException("Data was null. Did you forget to call load()?");
        }
        return $this -> data;
    }
}