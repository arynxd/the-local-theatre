<?php

namespace TLT\Util\Assert;

class Assertions {
    /**
     * Asserts that $value is non-null
     *
     * @throws AssertionException if the $value is not set
     */
    public static function assertSet($value) {
        if (!isset($value)) {
            throw new AssertionException("Assertion failed, value was not set.");
        }
    }
}