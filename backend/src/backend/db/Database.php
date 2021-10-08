<?php
require_once __DIR__ . "/../util/ErrorHandler.php";

class Database {
    private $dbh;

    public function __construct($url, $username, $password) {
        $this -> dbh = new PDO($url, $username, $password);
    }

    /**
     * Performs the given $sql query using the $params.
     *
     *
     * @param $sql      string   the sql string
     * @param $params   array    associative array of params to prepare
     * @return          array | false    the associative array representing the query, false if the query failed
     */
    public function query($sql, $params) {
        return $this -> prepare($sql, $params) -> fetch(PDO::FETCH_ASSOC);
    }

    private function prepare($sql, $params) {
        $stmt = $this -> dbh -> prepare($sql);
        foreach ($params as $key => $value) {
            $stmt -> bindParam($key, $value);
        }

        return $stmt;
    }
}