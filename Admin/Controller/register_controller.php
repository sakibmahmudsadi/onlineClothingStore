<?php
declare(strict_types=1);

header("Content-Type: application/json");

require_once __DIR__ . "/../Model/User.php";

function respond(bool $success, string $message, int $status = 200): void
{
    http_response_code($status);
    echo json_encode(["success" => $success, "message" => $message]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    respond(false, "Invalid request method.", 405);
}

$input = $_POST;
if (empty($input)) {
    $raw = file_get_contents("php://input");
    $input = json_decode($raw, true) ?? [];
}

$name            = trim($input["name"] ?? "");
$email           = trim($input["email"] ?? "");
$password        = $input["password"] ?? "";
$confirmPassword = $input["confirm_password"] ?? "";
$address         = trim($input["address"] ?? "");
$phone           = trim($input["phone"] ?? "");

if ($name === "" || $email === "" || $password === "" || $confirmPassword === "") {
    respond(false, "Please fill in all required fields.", 422);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(false, "Please enter a valid email address.", 422);
}

if (strlen($password) < 8) {
    respond(false, "Password must be at least 8 characters long.", 422);
}

if ($password !== $confirmPassword) {
    respond(false, "Passwords do not match.", 422);
}

if (emailExists($email)) {
    respond(false, "An account with this email already exists.", 409);
}

$ok = insertUser(
    $name,
    $email,
    password_hash($password, PASSWORD_DEFAULT),
    $address !== "" ? $address : null,
    $phone !== "" ? $phone : null
);

if (!$ok) {
    respond(false, "Registration failed. Please try again.", 500);
}

respond(true, "Account created successfully. You can now log in.");