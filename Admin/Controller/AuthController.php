<?php
declare(strict_types=1);

session_start();
header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . "/../Model/AuthModel.php";

$action = $_GET["action"] ?? "";

if ($action === "login") {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        http_response_code(405);
        echo json_encode(["ok" => false, "message" => "Invalid request method."]);
        exit;
    }

    $email = trim((string)($_POST["email"] ?? ""));
    $password = (string)($_POST["password"] ?? "");

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === "") {
        http_response_code(400);
        echo json_encode(["ok" => false, "message" => "Enter a valid email and password."]);
        exit;
    }

    $user = findUserByEmail($email);

    if (!$user || !password_verify($password, $user["password_hash"])) {
        http_response_code(401);
        echo json_encode(["ok" => false, "message" => "Invalid email or password."]);
        exit;
    }

    session_regenerate_id(true);
    $_SESSION["user_id"] = (int)$user["id"];
    $_SESSION["name"] = $user["name"];
    $_SESSION["role"] = $user["role"];

    echo json_encode([
        "ok" => true,
        "role" => $user["role"],
        "redirect" => $user["role"] === "admin" ? "../view/dashboard.html" : "../view/home.html"
    ]);
    exit;
}

if ($action === "check") {
    echo json_encode([
        "ok" => isset($_SESSION["user_id"]),
        "authenticated" => isset($_SESSION["user_id"]),
        "role" => $_SESSION["role"] ?? null,
        "name" => $_SESSION["name"] ?? null
    ]);
    exit;
}

if ($action === "logout") {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            "",
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    session_destroy();
    echo json_encode(["ok" => true]);
    exit;
}

http_response_code(404);
echo json_encode(["ok" => false, "message" => "Unknown action."]);
