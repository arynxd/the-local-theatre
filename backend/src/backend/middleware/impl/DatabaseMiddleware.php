<?php

namespace TLT\Middleware\Impl;

use TLT\Middleware\BaseMiddleware;
use TLT\Request\Session;
use TLT\Util\HttpResult;
use TLT\Util\Log\Logger;

class DatabaseMiddleware extends BaseMiddleware {

    /**
     * @inheritDoc
     */
    public function apply($sess) {
        if (!$sess -> db -> isEnabled()) {
            Logger::getInstance() -> warn("Database was not loaded, cancelling request");
            $sess -> res -> sendInternalError("Database was not loaded, cancelling request");
        }
        return HttpResult::Ok();
    }
}