<?php
declare(strict_types=1);

session_start();
header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . "../Model/OrderModel.php";

if (($_SESSION["role"] ?? null) !== "admin") {
    http_response_code(403);
    echo json_encode(["ok" => false, "message" => "Admin access required."]);
    exit;
}

$action = $_GET["action"] ?? "";

if ($action === "list") {
    echo json_encode(["ok" => true, "data" => getOrders()]);
    exit;
}

if ($action === "history") {
    echo json_encode(["ok" => true, "data" => getPurchaseHistory()]);
    exit;
}

if ($action === "status" && $_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
    $status = trim((string)($_POST["status"] ?? ""));

    if (!$id || !in_array($status, ["confirmed", "rejected"], true)) {
        http_response_code(400);
        echo json_encode(["ok" => false, "message" => "Invalid order update."]);
        exit;
    }

    if (!updateOrderStatus($id, $status)) {
        http_response_code(400);
        echo json_encode(["ok" => false, "message" => "Order could not be updated. It may no longer be pending."]);
        exit;
    }

    echo json_encode(["ok" => true, "message" => "Order status updated.", "status" => $status]);
    exit;
}

http_response_code(404);
echo json_encode(["ok" => false, "message" => "Unknown action."]);
