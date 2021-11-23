<?php
require_once __DIR__ . "/../Map.php";

class ContentType {
    const JSON = 'Content-Type: application/json';
    const PNG = 'Content-Type: image/png';
    const JPEG = 'Content-Type: image/jpeg';

    public static function ALL() {
        $m = new Map([self::JSON, self::PNG]);
        return $m -> freeze();
    }
}

assert(ContentType ::ALL() -> length() == 2, "Missing content types");