<?php
declare(strict_types=1);

require_once __DIR__ . "/db.php";

function emailExists(string $email): bool
{
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $exists = (bool) $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $exists;
}

function getUserByEmail(string $email): ?array
{
    global $conn;
    $stmt = $conn->prepare(
        "SELECT id, name, email, password_hash, role, address, phone
         FROM users WHERE email=?"
    );
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc() ?: null;
    $stmt->close();
    return $row;
}

function insertUser(
    string $name,
    string $email,
    string $passwordHash,
    ?string $address,
    ?string $phone
): bool {
    global $conn;
    $stmt = $conn->prepare(
        "INSERT INTO users (name, email, password_hash, role, address, phone)
         VALUES (?, ?, ?, 'customer', ?, ?)"
    );
    $stmt->bind_param("sssss", $name, $email, $passwordHash, $address, $phone);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}