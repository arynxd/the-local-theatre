<?php

namespace TLT\Util\Assert;

use TLT\Util\Log\Logger;

class Assertions {
    /**
     * Asserts that $value is non-null
     *
     * @throws AssertionException if the $value is not set
     */
    public static function assertSet($value) {
        if (!isset($value)) {
            Logger::getInstance() -> fatal("Assertion failed, value was not set.");
        }
    }
}