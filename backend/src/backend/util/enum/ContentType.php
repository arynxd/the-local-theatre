<?php
namespace TLT\Util\Enum;


use TLT\Util\Data\Map;

class ContentType {
    const JSON = 'Content-Type: application/json';
    const PNG = 'Content-Type: image/png';
    const JPEG = 'Content-Type: image/jpeg';

    public static function ALL() {
        return Map::from([self::JSON, self::PNG]) -> freeze();
    }
}