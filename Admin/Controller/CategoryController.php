<?php
declare(strict_types=1);

session_start();
require_once __DIR__ . "/../Model/CategoryModel.php";

header("Content-Type: application/json; charset=utf-8");

function categoryJson(array $data, int $code=200): never
{
    http_response_code($code);
    echo json_encode($data);
    exit;
}

if (($_SESSION["role"] ?? "") !== "admin") {
    categoryJson(["ok"=>false, "message"=>"Admin access required.", "redirect"=>"login.html"], 401);
}

$action = $_GET["action"] ?? "";

if ($action === "all") {
    categoryJson(["ok"=>true, "data"=>CategoryModel::all()]);
}

if ($action === "children") {
    $gender = trim($_GET["gender"] ?? "");
    if (!in_array($gender, ["Men", "Women"], true)) {
        categoryJson(["ok"=>false, "message"=>"Invalid gender."], 400);
    }
    categoryJson(["ok"=>true, "data"=>CategoryModel::childrenByGender($gender)]);
}

categoryJson(["ok"=>false, "message"=>"Invalid category action."], 400);
