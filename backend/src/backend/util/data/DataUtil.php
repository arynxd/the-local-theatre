<?php

namespace TLT\Util\Data;

use Exception;
use TLT\Util\Log\Logger;
use UnexpectedValueException;

class DataUtil {
    public static function readOrDefault($fileName, $default, ...$headers) {
        $path = __DIR__ . "/../../../data/";

        if (file_exists($path . $fileName)) {
            self ::read($fileName, ...$headers);
        }
        else if (file_exists($path . $default)) {
            self ::read($default, ...$headers);
        }
        else {
            Logger::getInstance() -> fatal("File did not exist at fileName ($fileName) or default ($default)");
        }
    }

    public static function read($fileName, ...$headers) {
        foreach ($headers as $header) {
            header($header);
        }
        try {
            readfile(__DIR__ . "/../../../data/$fileName");
        }
        catch (Exception $ex ) {
            Logger::getInstance() -> error($ex);
            Logger::getInstance() -> fatal("Failed to read file $fileName");
        }
    }
}