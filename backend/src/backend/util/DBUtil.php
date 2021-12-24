<?php

namespace TLT\Util;

class DBUtil {
    /**
     * Provides the current time as an epoch
     *
     * @return int The current time
     */
    public static function currentTime() {
        return time();
    }

    const DEFAULT_PERMISSIONS = 1;
}
