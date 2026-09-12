<?php

declare(strict_types=1);

function db(): mysqli
{
    static $conn = null;

    if ($conn instanceof mysqli) {
        return $conn;
    }

    $conn = new mysqli('localhost', 'root', '', 'g7cs');

    if ($conn->connect_error) {
        http_response_code(500);
        exit('Database connection failed.');
    }

    $conn->set_charset('utf8mb4');
    return $conn;
}
