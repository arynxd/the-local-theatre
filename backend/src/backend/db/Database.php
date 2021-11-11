<?php
require_once __DIR__ . '/../util/constant/ErrorStrings.php';
require_once __DIR__ . '/../util/constant/StatusCode.php';

class Database {
    private $dbh;

    /**
     * Constructs a database connection using the provided details.
     * This connection will persist through runs of script.
     *
     * @param  $url       string      The URL to use
     * @param  $username  string      The username to use
     * @param  $password  string      The password to use
     * @param  $conn      Connection  The current connection
     */
    public function __construct($url, $username, $password, $conn) {
        $opts = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_TIMEOUT => 5
        ];

        try {
            $this -> dbh = new PDO($url, $username, $password, $opts);
        }
        catch (PDOException $e) {
            $conn -> res -> sendError(ErrorStrings::INTERNAL_ERROR, StatusCode::INTERNAL_ERROR);
            die(1);
        }
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