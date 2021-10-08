<?php

require_once __DIR__ . "/../Middleware.php";

class ModelValidatorMiddleware extends Middleware {
    private $validator;
    private $key;
    private $errorStr;
    private $errorHeaders;

    public function __construct($validator, $dataKey, $errorString, ...$errorHeaders) {
        $this -> validator = $validator;
        $this -> key = $dataKey;
        $this -> errorStr = $errorString;
        $this -> errorHeaders = $errorHeaders;
    }

    public function apply($conn) {
        $json = $conn -> jsonParams()[$this -> key];
        if (!isset($json)) {
            return $this -> err();
        }

        $valid = $this -> validator -> validate($json);

        if (!$valid) {
            return $this -> err();
        }

        return [true];
    }

    private function err() {
        $arr = [false, $this -> errorStr];
        foreach ($this -> errorHeaders as $h) {
            array_push($arr, $h);
        }
        return $arr;
    }
}