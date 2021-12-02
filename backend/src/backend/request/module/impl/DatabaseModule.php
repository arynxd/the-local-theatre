<?php

namespace TLT\Request\Module\Impl;

use PDO;
use PDOException;
use PDOStatement;
use TLT\Request\Module\BaseModule;
use TLT\Util\Assert\AssertionException;
use TLT\Util\Assert\Assertions;
use TLT\Util\Log\Logger;

/*
* This connection will persist through runs of script.
*
* This class implements the following options for the connection:
*  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
*  PDO::ATTR_PERSISTENT => true,
*  PDO::ATTR_TIMEOUT => 5
*/

class DatabaseModule extends BaseModule {
    /**
     * @var PDO $dbh
     */
    private $dbh;

    public function onEnable() {
        if (!$this -> sess -> cfg -> dbEnabled) {
            Logger ::getInstance() -> debug("db_enabled false, halting DB connection");
            $this -> dbh = null;
            return;
        }

        $opts = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_TIMEOUT => 5,
        ];

        $cfg = $this -> sess -> cfg;

        $url = $cfg -> dbURL;
        $username = $cfg -> dbUsername;
        $password = $cfg -> dbPassword;

        Logger ::getInstance() -> info("Starting connection to DB..");

        Logger ::getInstance() -> debug("\tURL: $url");
        Logger ::getInstance() -> debug("\tUsername: $username");
        Logger ::getInstance() -> debug("\tPassword: $password");

        try {
            $this -> dbh = new PDO($url, $username, $password, $opts);
        }
        catch (PDOException $ex) {
            Logger ::getInstance() -> error("PDO connection failed");
            $this -> sess -> res -> sendInternalError($ex);
        }

        if ($this -> sess -> cfg -> env !== "PRODUCTION") {
            Logger ::getInstance() -> info("ENV not in prod, initialising tables");
            $this -> initTables();
        }
    }

    private function initTables() {
        $this -> initTable("tables.sql");
    }

    private function initTable($fileName) {
        Logger ::getInstance() -> debug("Running SQL from file " . $fileName);
        $sql = file_get_contents(__DIR__ . "/../../../sql/" . $fileName);
        Assertions::assertNotFalse($sql);
        $this -> query($sql, []);
    }

    /**
     * Performs the given $sql query using the $params.
     * This function will attempt to execute the query, returning false if that fails.
     * If this function returns a non-false value,
     *  the query was successful and all data can be accessed without checks
     *
     * @param $sql      string   the sql string
     * @param $params   array    associative array of params to prepare
     * @return          PDOStatement the statement representing this query
     */
    public function query($sql, $params = []) {
        Logger ::getInstance() -> debug("Running SQL query " . $sql);
        $st = $this -> prepare($sql);

        if (!$st) {
            Logger ::getInstance() -> warn("SQL failed to prepare");
            Logger ::getInstance() -> warn("\t$sql");
            throw new AssertionException("SQL query failed");
        }

        $st -> execute($params);

        return $st;
    }

    private function prepare($sql) {
        $stmt = $this -> dbh -> prepare($sql);

        if (!$stmt) {
            return false;
        }

        return $stmt;
    }

    public function isEnabled() {
        return isset($this -> dbh);
    }

    public function errorInfo() {
        return $this -> dbh -> errorInfo();
    }
}