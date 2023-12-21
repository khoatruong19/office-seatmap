<?php

declare(strict_types=1);

namespace core;

use PDO;
use shared\enums\GeneralResponse;

class Database
{
    private PDO $connection;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $db_type = $_ENV['DB_TYPE'] ?? "";
        $host = $_ENV['DB_HOST'] ?? "";
        $db_name = $_ENV['DB_NAME'] ?? "";
        $username = $_ENV['DB_USER'] ?? "";
        $password = $_ENV['DB_PASS'] ?? "";
        $port = $_ENV['DB_PORT'] ?? "";

        if ($db_type == "" || $host == "" || $username == "" || $password == "" || $db_name == "") {
            throw new \Exception(GeneralResponse::DATABASE_CONNECTION_FAIL->value);
        }

        $this->connection = new PDO("$db_type:host=$host;port=$port;dbname=$db_name", $username, $password);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @param string $schema
     * @param string $name_file
     * @return void
     */
    public function runMigration(string $schema, string $name_file)
    {
        $sql = file_get_contents(sprintf('%s/../shared/schemas/%s/%s', dirname(__DIR__), $schema, $name_file));
        $this->connection->exec($sql);
    }

    /**
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
