<?php
declare(strict_types=1);

session_start();
header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . "/../Model/AdminModel.php";

if (($_SESSION["role"] ?? null) !== "admin") {
    http_response_code(403);
    echo json_encode(["ok" => false, "message" => "Admin access required."]);
    exit;
}

$action = $_GET["action"] ?? "";

if ($action === "check") {
    echo json_encode(["ok" => true, "role" => "admin", "name" => $_SESSION["name"] ?? "Admin"]);
    exit;
}

if ($action === "dashboard") {
    echo json_encode(["ok" => true, "data" => dashboardCounts()]);
    exit;
}

http_response_code(404);
echo json_encode(["ok" => false, "message" => "Unknown action."]);
