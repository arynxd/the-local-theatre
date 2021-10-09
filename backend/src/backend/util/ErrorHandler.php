<?php

require_once __DIR__ . "/../request/Response.php";
require_once __DIR__ . '/constant/ErrorStrings.php';
require_once __DIR__ . '/constant/StatusCode.php';

class ErrorHandler {
    const DEBUG = true;

    public static function enableErrors() { // this is static because we always want to turn this on, regardless of other application state
                                            // not ideal, but better than unlogged errors.
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }



    private $res;

    public function __construct($res) {
        $this -> res = $res;
    }

    public function log($ex) {
        if (ErrorHandler::DEBUG) {
            echo "An unexpected error occurred:";
            echo $ex -> getCode();
            echo $ex -> getMessage();
        }
    }

    public function addInterceptor() {
        set_error_handler(
            function ($errno, $errstr, $errfile, $errline) {
                self::log(new Exception("<h1>An internal error has occurred.</h1> <br> Line $errline in $errfile <br><br> <strong>Error</strong>: $errstr"));
                $this -> res -> sendError(ErrorStrings::INTERNAL_ERROR, StatusCode::INTERNAL_ERROR);
            }
        );
    }

}