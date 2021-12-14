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
        return $value;
    }

     /**
     * Asserts that $value is not falsy
     * @param mixed $value
     * @param string|null an error message
     */
    public static function assertNotFalse($value,$message = null) {
        if (!$value) {
            $m = "Assertion failed, value was falsy";
            if (isset($message)) {
                $m .= $message;
            }
            Logger::getInstance() -> fatal(new AssertionException($m));
        }
        return $value;
    }

    /**
     * Asserts that $value is true
     * @param bool $bool
     */
    public static function assert($bool) {
        if ($bool !== true) {
            Logger::getInstance() -> fatal(new AssertionException("Assertion failed, value was false"));
        }
    }
}