<?php

require_once 'Module.php';

class DataModule extends Module {
    /**
     * @var Map $headers
     */
    public $headers;

    protected function onEnable() {

        $this -> headers = $this -> parseHeaders();
    }

    private function parseHeaders() {
        if (!function_exists('getallheaders')) {
            throw new UnexpectedValueException("getallheaders function did not exist? are we actually running under apache??");
        }
        return map(getallheaders());
    }
}