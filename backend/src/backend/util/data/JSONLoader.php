<?php

namespace TLT\Util\Data;

use UnexpectedValueException;

class JSONLoader {
    private $path;
    private $data;

    public function __construct($path) {
        $this -> path = $path;
        $this -> data = null;
    }

    public function load() {
        $json = json_decode(file_get_contents($this -> path), true);
        if (!isset($json)) {
            throw new UnexpectedValueException("Data at " . $this -> path . " was invalid.");
        }
        $this -> data = Map ::from($json) -> toMapRecursive();
    }

    public function data() {
        return $this -> data;
    }
}