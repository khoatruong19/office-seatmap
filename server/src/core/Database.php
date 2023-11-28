<?php

namespace core;

use PDO;

class Database
{
    private PDO $connection;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $dbType = $_ENV['DB_TYPE'] ?? "";
        $host = $_ENV['DB_HOST'] ?? "";
        $dbName = $_ENV['DB_NAME'] ?? "";
        $username = $_ENV['DB_USER'] ?? "";
        $password = $_ENV['DB_PASS'] ?? "";
        $port = $_ENV['DB_PORT'] ?? "";

        if ($dbType == "" || $host == "" || $username == "" || $password == "" || $dbName == "") {
            throw new \Exception("Database cannot be connected due to wrong configuration!");
        }

        $this->connection = new PDO("$dbType:host=$host;port=$port;dbname=$dbName", $username, $password);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function runMigration($nameFile) {
        $sql = file_get_contents( dirname(__DIR__)."/../shared/migrations/".$nameFile);
        $this->connection->exec($sql);
    }

    public function getConnection() {
        return $this->connection;
    }
}
