<?php

require_once __DIR__ . "/../Middleware.php";

class ModelValidatorMiddleware extends Middleware {
    private $validator;
    private $key;
    private $err;

    public function __construct($validator, $dataKey, $err) {
        $this -> validator = $validator;
        $this -> key = $dataKey;
        $this -> err = $err;
    }

    public function apply($conn) {
        $json = $conn -> jsonParams()[$this -> key];
        if (!isset($json)) {
            return $this -> err;
        }

        $valid = $this -> validator -> validate($json);

        if (!$valid) {
            return $this -> err;
        }

        return Ok();
    }
}