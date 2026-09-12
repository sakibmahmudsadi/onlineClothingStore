<?php
declare(strict_types=1);

$host = "localhost";
$user = "root";
$password = "";
$database = "g7cs";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    http_response_code(500);
    exit("Database connection failed.");
}

$conn->set_charset("utf8mb4");
