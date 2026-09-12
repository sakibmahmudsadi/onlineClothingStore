<?php

declare(strict_types=1);

class Database
{
    private string $host = "localhost";
    private string $username = "root";
    private string $password = "";
    private string $database = "g7cs";

    public function connect(): mysqli
    {
        $conn = new mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->database
        );

        if ($conn->connect_error) {
            throw new RuntimeException(
                "Database connection failed."
            );
        }

        $conn->set_charset("utf8mb4");

        return $conn;
    }
}
