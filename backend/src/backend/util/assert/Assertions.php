<?php

namespace TLT\Util\Assert;

use TLT\Util\Log\Logger;

class Assertions {
    /**
     * Asserts that $value is non-null
     */
    public static function assertSet($value) {
        if (!isset($value)) {
            Logger ::getInstance() -> fatal(new AssertionException("Assertion failed, value was not set."));
        }
    }

     /**
     * Asserts that $value is not false
     */
    public static function assertNotFalse($value) {
        if (!$value) {
            Logger::getInstance() -> fatal(new AssertionException("Assertion failed, value was falsy"));
        }
    }
}