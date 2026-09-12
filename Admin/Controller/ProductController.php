<?php
declare(strict_types=1);

session_start();
header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . "/../Model/ProductModel.php";

if (($_SESSION["role"] ?? null) !== "admin") {
    http_response_code(403);
    echo json_encode(["ok" => false, "message" => "Admin access required."]);
    exit;
}

function productError(string $message, int $code = 400): never
{
    http_response_code($code);
    echo json_encode(["ok" => false, "message" => $message]);
    exit;
}

function validateProductInput(): array
{
    $name = trim((string)($_POST["name"] ?? ""));
    $desc = trim((string)($_POST["description"] ?? ""));
    $priceRaw = $_POST["price"] ?? "";
    $size = trim((string)($_POST["size"] ?? ""));
    $quantityRaw = $_POST["quantity"] ?? "";
    $category = trim((string)($_POST["category"] ?? ""));

    if (mb_strlen($name) < 2 || mb_strlen($name) > 100) {
        productError("Product name must be between 2 and 100 characters.");
    }

    if (mb_strlen($desc) > 2000) {
        productError("Description is too long.");
    }

    if ($priceRaw === "" || !is_numeric($priceRaw) || (float)$priceRaw <= 0 || (float)$priceRaw > 99999999) {
        productError("Price must be greater than 0.");
    }

    if ($quantityRaw === "" || filter_var($quantityRaw, FILTER_VALIDATE_INT) === false || (int)$quantityRaw < 0) {
        productError("Stock must be a whole number 0 or greater.");
    }

    if (mb_strlen($size) > 20) {
        productError("Size must be 20 characters or less because of the current database.");
    }

    if (!in_array($category, ["men", "woman", "child"], true)) {
        productError("Invalid category. Choose men, woman, or child.");
    }

    return [$name, $desc, (float)$priceRaw, $size, (int)$quantityRaw, $category];
}

function uploadProductImage(?array $file, ?string $oldPath = null): ?string
{
    if (!$file || ($file["error"] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return $oldPath;
    }

    if ($file["error"] !== UPLOAD_ERR_OK) {
        productError("Image upload failed.");
    }

    if ($file["size"] > 2 * 1024 * 1024) {
        productError("Image must be 2MB or smaller.");
    }

    $allowed = [
        "image/jpeg" => "jpg",
        "image/png" => "png"
    ];

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file["tmp_name"]);

    if (!isset($allowed[$mime]) || @getimagesize($file["tmp_name"]) === false) {
        productError("Only valid JPEG and PNG images are allowed.");
    }

    $dir = dirname(__DIR__) . "/images/products/";
    if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
        productError("Could not create image directory.", 500);
    }

    $name = bin2hex(random_bytes(16)) . "." . $allowed[$mime];
    $destination = $dir . $name;

    if (!move_uploaded_file($file["tmp_name"], $destination)) {
        productError("Could not save uploaded image.", 500);
    }

    if ($oldPath) {
        $old = dirname(__DIR__) . "/" . ltrim($oldPath, "/");
        if (is_file($old)) {
            @unlink($old);
        }
    }

    return "images/products/" . $name;
}

$action = $_GET["action"] ?? "";

if ($action === "list") {
    echo json_encode(["ok" => true, "data" => getAllProducts()]);
    exit;
}

if ($action === "get") {
    $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
    if (!$id) productError("Invalid product ID.");
    $product = getProduct($id);
    if (!$product) productError("Product not found.", 404);
    echo json_encode(["ok" => true, "data" => $product]);
    exit;
}

if ($action === "create" && $_SERVER["REQUEST_METHOD"] === "POST") {
    [$name, $desc, $price, $size, $quantity, $category] = validateProductInput();

    $image = uploadProductImage($_FILES["image"] ?? null);
    if (!$image) {
        productError("Product image is required.");
    }

    if (!insertProduct($name, $desc, $price, $image, $size, $quantity, $category)) {
        @unlink(dirname(__DIR__) . "/" . $image);
        productError("Could not insert product.", 500);
    }

    echo json_encode(["ok" => true, "message" => "Product added successfully."]);
    exit;
}

if ($action === "update" && $_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
    if (!$id) productError("Invalid product ID.");

    $old = getProduct($id);
    if (!$old) productError("Product not found.", 404);

    [$name, $desc, $price, $size, $quantity, $category] = validateProductInput();
    $image = uploadProductImage($_FILES["image"] ?? null, $old["proImg"]);

    if (!updateProduct($id, $name, $desc, $price, $image ?? "", $size, $quantity, $category)) {
        productError("Could not update product.", 500);
    }

    echo json_encode(["ok" => true, "message" => "Product updated successfully."]);
    exit;
}

if ($action === "delete" && $_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
    if (!$id) productError("Invalid product ID.");

    $result = deleteProduct($id);

    if (!$result["ok"]) {
        productError($result["message"]);
    }

    if (!empty($result["image"])) {
        $path = dirname(__DIR__) . "/" . ltrim($result["image"], "/");
        if (is_file($path)) {
            @unlink($path);
        }
    }

    echo json_encode(["ok" => true, "message" => $result["message"]]);
    exit;
}

http_response_code(404);
echo json_encode(["ok" => false, "message" => "Unknown action."]);
