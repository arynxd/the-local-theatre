<?php

namespace TLT\Util\Enum;

use TLT\Util\Data\Map;

class CORS {
    const ALL = 'Access-Control-Allow-Origin: *';

    public static function MAP() {
        return Map::from([
            'all' => self::ALL,
        ]);
    }
}
