<?php

class AuthModule extends Module {
    /**
     * @var string|null $token
     */
    public $token;

    private $isAuthenticated;

    protected function onEnable() {
        $this -> token = $this -> sess -> data -> headers['Authorisation'];
    }

    public function isAuthenticated() {
        if (!isset($this -> token)) {
            $this -> isAuthenticated = false;
            return false;
        }

        if (isset($this -> isAuthenticated)) {
            return $this -> isAuthenticated;
        }

        $query = "SELECT COUNT(*) FROM credential WHERE token = :token";
        $dbRes = $this -> sess -> db -> query($query,[
            'token' => $this -> token
        ]) -> rowCount();

        if ($dbRes > 0) {
            $this -> isAuthenticated = true;
        }
        else {
            $this -> isAuthenticated = false;
        }

        return $this -> isAuthenticated;
    }
}