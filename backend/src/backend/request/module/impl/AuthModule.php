<?php

namespace TLT\Request\Module\Impl;

use TLT\Request\Module\BaseModule;
use TLT\Util\Log\Logger;

class AuthModule extends BaseModule {
    /**
     * @var string|null $token
     */
    public $token;

    /**
     * @var boolean $isAuthenticated
     */
    private $isAuthenticated;

    public function onEnable() {
        $this -> token = $this -> sess -> data -> headers['Authorisation'];

        if (!isset($this -> token)) {
            Logger::getInstance() -> info("Authorisation header was not set, this request will be treated as UNAUTHENTICATED..");
            $this -> isAuthenticated = false;
        }
    }

    public function isAuthenticated() {
        if (isset($this -> isAuthenticated)) {
            Logger::getInstance() -> debug("Short circuiting isAuthenticated with value {$this -> isAuthenticated}");
            return $this -> isAuthenticated;
        }



        Logger::getInstance() -> debug("Token is set, looking up from the DB");

        $query = "SELECT COUNT(*) FROM credential WHERE token = :token";
        $dbRes = $this -> sess -> db -> query($query, [
            'token' => $this -> token
        ]) -> rowCount();

        if ($dbRes > 0) {
            Logger::getInstance() -> info("Request auth validated, this is now an AUTHENTICATED request");
            $this -> isAuthenticated = true;
        }
        else {
            Logger::getInstance() -> info("Request auth validation failed, this is now an UNAUTHENTICATED request");
            $this -> isAuthenticated = false;
        }

        return $this -> isAuthenticated;
    }
}