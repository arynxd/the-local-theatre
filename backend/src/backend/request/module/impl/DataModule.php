<?php

namespace TLT\Request\Module\Impl;

use TLT\Request\Module\BaseModule;
use TLT\Util\Assert\Assertions;
use TLT\Util\Data\Map;
use TLT\Util\Log\Logger;

class DataModule extends BaseModule {
    /**
     * @var Map $headers
     */
    public $headers;

    public function onEnable() {

        $this -> headers = $this -> parseHeaders();
    }

    private function parseHeaders() {
        if (!function_exists('getallheaders')) {
            Logger ::getInstance() -> fatal("getallheaders function did not exist? are we actually running under Apache?");
        }

        $h = getallheaders();

        Assertions::assertNotFalse($h);
        return Map ::from($h);
    }
}