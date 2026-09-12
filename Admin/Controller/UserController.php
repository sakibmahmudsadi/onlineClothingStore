<?php
declare(strict_types=1);

session_start();
header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . "/../Model/UserModel.php";

if (($_SESSION["role"] ?? null) !== "admin") {
    http_response_code(403);
    echo json_encode(["ok" => false, "message" => "Admin access required."]);
    exit;
}

$action = $_GET["action"] ?? "";

if ($action === "list") {
    echo json_encode(["ok" => true, "data" => getCustomers()]);
    exit;
}

if ($action === "delete" && $_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
    if (!$id) {
        http_response_code(400);
        echo json_encode(["ok" => false, "message" => "Invalid customer ID."]);
        exit;
    }

    $result = deleteCustomer($id);

    if (!$result["ok"]) {
        http_response_code(400);
    }

    echo json_encode($result);
    exit;
}

http_response_code(404);
echo json_encode(["ok" => false, "message" => "Unknown action."]);
