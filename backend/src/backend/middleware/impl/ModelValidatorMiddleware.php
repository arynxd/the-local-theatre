<?php

require_once __DIR__ . "/../Middleware.php";

class ModelValidatorMiddleware extends Middleware {
    private $required;
    private $data;
    private $err;

    public function __construct($required, $data, $err) {
        $this -> required = $required;
        $this -> data = $data;
        $this -> err = $err;
    }

    public function apply($conn) {
        foreach ($this -> required as $key) {
            if (!$this -> data -> exists($key)) {
                BadRequest($this -> err);
            }
        }
        return Ok();
    }
}