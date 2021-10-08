<?php
require_once __DIR__ . "/../util/ErrorHandling.php";

class Database {
    protected $connection = null;

    public function __construct($host, $username, $password, $db_name) {
        try {
            $this -> connection = new mysqli($host, $username, $password, $db_name);

            if (mysqli_connect_errno()) {
                $ex = new mysqli_sql_exception("Could not connect to database.");
                ErrorHandling::log($ex);
                throw $ex;
            }
        }
        catch (Exception $exc) {
            $ex = new mysqli_sql_exception($exc -> getMessage());
            ErrorHandling::log($ex);
            throw $ex;
        }

        $this -> init();
    }

    public function select($query = "" , $params = []) {
        try {
            $stmt = $this -> executeStatement($query, $params);
            $result = $stmt-> get_result() -> fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            return $result;
        }
        catch(Exception $e) {
            ErrorHandling::log($e);
            return false;
        }
    }

    private function init() {
        $tables = [];

        $sqlPath = __DIR__ . "/../sql/";
        $dir = opendir($sqlPath);

        while ($file = readdir($dir)) {
            if ($file == '.' || $file == '..') {
                continue;
            }

            $content = file_get_contents($sqlPath . $file);
            array_push($tables, $content);
        }
        closedir($dir);

        foreach ($tables as $table) {
            $this -> executeStatement($table);
        }
    }

    private function executeStatement($query = "" , $params = []) {
        try {
            $stmt = $this->connection->prepare($query);

            if(!$stmt) {
                throw new mysqli_sql_exception("Unable to prepare statement: " . $query);
            }

            foreach ($params as $key => $val){
               $stmt->bind_param($key, $value);
            }

            $stmt->execute();

            return $stmt;
        }
        catch(Exception $ex) {
            ErrorHandling::log($ex);
            return false;
        }
    }
}