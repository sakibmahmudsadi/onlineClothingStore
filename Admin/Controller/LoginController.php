<?php
declare(strict_types=1);

session_start();
require_once __DIR__ . "/../Model/UserModel.php";

header("Content-Type: application/json; charset=utf-8");

function loginJson(array $data, int $code=200): never
{
    http_response_code($code);
    echo json_encode($data);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    loginJson(["ok"=>false, "message"=>"Invalid request method."], 405);
}

$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === "") {
    loginJson(["ok"=>false, "message"=>"Enter a valid email and password."], 400);
}

$user = UserModel::findByEmail($email);

if (!$user || !password_verify($password, $user["password_hash"])) {
    loginJson(["ok"=>false, "message"=>"Invalid email or password."], 401);
}

session_regenerate_id(true);
$_SESSION["user_id"] = (int)$user["id"];
$_SESSION["role"] = $user["role"];
$_SESSION["name"] = $user["name"];

if ($user["role"] !== "admin") {
    session_unset();
    session_destroy();
    loginJson(["ok"=>false, "message"=>"Admin account required."], 403);
}

loginJson(["ok"=>true, "message"=>"Login successful.", "redirect"=>"dashboard.html"]);
