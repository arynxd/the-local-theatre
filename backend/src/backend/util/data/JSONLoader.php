<?php

namespace TLT\Util\Data;

use TLT\Util\Log\Logger;

class JSONLoader {
    private $path;
    private $data;

    public function __construct($path) {
        $this->path = $path;
        $this->data = null;
    }

    public function load() {
        $json = json_decode(file_get_contents($this->path), true);
        if (!isset($json)) {
            Logger::getInstance()->fatal(
                'Data at ' . $this->path . ' was invalid.'
            );
        }
        $this->data = Map::from($json)->toMapRecursive();
    }

    public function data() {
        return $this->data;
    }
}
