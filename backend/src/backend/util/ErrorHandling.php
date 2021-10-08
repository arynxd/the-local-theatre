<?php

class ErrorHandling {
    const DEBUG = true;

    public static function enableErrors() {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    public static function log($ex) {
        if (ErrorHandling::DEBUG) {
            echo "An unexpected error occurred: \n";
            echo $ex -> getCode();
            echo $ex -> getMessage();
        }
    }
}