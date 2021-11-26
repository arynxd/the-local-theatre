<?php
require_once __DIR__ . '/../util/constant/ErrorStrings.php';
require_once __DIR__ . '/../util/constant/StatusCode.php';

class Database {
    private $dbh;

    /**
     * Constructs a database connection using the provided details.
     * This connection will persist through runs of script.
     *
     * This class implements the following options for the connection:
     *  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
     *  PDO::ATTR_PERSISTENT => true,
     *  PDO::ATTR_TIMEOUT => 5
     *
     * @param  $url       string      The URL to use
     * @param  $username  string      The username to use
     * @param  $password  string      The password to use
     * @param  $conn      Session  The current connection
     */
    public function __construct($url, $username, $password, $conn) {
        $opts = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_TIMEOUT => 5,
        ];

        try {
            $this -> dbh = new PDO($url, $username, $password, $opts);
        }
        catch (PDOException $e) {
            $conn -> res -> sendInternalError();
        }

        if ($conn -> config['env'] !== "production") {
            $this -> initTables();
        }
    }

    private function initTables() {
        $this -> initTable("tables.sql");
    }

    private function initTable($fileName) {
        $sql = file_get_contents(__DIR__ . "/../sql/" . $fileName);
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
        $st = $this -> prepare($sql);
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

    public function errorInfo() {
        return $this -> dbh -> errorInfo();
    }
}