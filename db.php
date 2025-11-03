<?php

class DatabaseConnector {
    public function __construct($serverName, $database, $username, $password) {
        $this->serverName = $serverName;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
        $this->connectionString = "odbc:Driver={ODBC Driver 18 for SQL Server};"
            . "Server=$this->serverName;"
            . "Database=$this->database;"
            . "Encrypt=no;"
            . "TrustServerCertificate=yes;"
            . "UID=$this->username;"
            . "PWD=$this->password;";
        error_log($serverName);
        error_log($database);
        error_log($username);
        $this->connect();
    }

    public function connect() {
        try {
            // $this->$conn = new PDO("sqlsrv:Server=$this->serverName;Database=$this->database", $this->username, $this->password);
            $this->conn = new PDO($this->connectionString);
        }
        catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function executeQuery($query, $params) {
        error_log($query);
        $statement = $this->conn->prepare($query);
        $success = $statement->execute($params);

        if ($statement->columnCount() > 0) {
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            error_log(json_encode($results));
            return $results;
        }

        return [
            "success" => $success,
            "rowsAffected" => $statement->rowCount()
        ];
    }
}

?>
