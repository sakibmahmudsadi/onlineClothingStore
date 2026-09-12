<?php
declare(strict_types=1);

require_once __DIR__ . "/db.php";

function findUserByEmail(string $email): ?array
{
    global $conn;

    $stmt = $conn->prepare(
        "SELECT id, name, email, password_hash, role
         FROM users
         WHERE email = ?
         LIMIT 1"
    );
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc() ?: null;
    $stmt->close();

    return $user;
}
