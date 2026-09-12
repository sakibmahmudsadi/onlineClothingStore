<?php

declare(strict_types=1);

// session_start();

// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     header("Location: ../view/login.html?error=unauthorized");
//     exit;
// }
// if (
//     !isset($_SESSION['role']) ||
//     $_SESSION['role'] !== 'admin'
// ) {
//     http_response_code(403);

//     header(
//         "Content-Type: application/json"
//     );

//     echo json_encode([
//         "success" => false,
//         "message" => "Access denied."
//     ]);

//     exit;
// }



$host = "localhost";
$username = "root";
$password = "";
$database = "g7cs";


$conn = new mysqli(
    $host,
    $username,
    $password,
    $database
);


if ($conn->connect_error) {

    die("Database connection failed: " .
        $conn->connect_error);
}


$conn->set_charset("utf8mb4");


function clean(string $value): string
{
    return trim($value);
}


function showError(string $message): never
{
    http_response_code(400);

    echo "<h2>Product could not be added</h2>";

    echo "<p>" .
        htmlspecialchars(
            $message,
            ENT_QUOTES,
            'UTF-8'
        ) .
        "</p>";

    echo "<a href='../view/index.html'>Go Back</a>";

    exit;
}


if (
    $_SERVER['REQUEST_METHOD'] !== 'POST'
) {

    showError(
        "Invalid request method."
    );
}


$name =
    clean(
        $_POST['name'] ?? ''
    );


$description =
    clean(
        $_POST['description'] ?? ''
    );


$sizeChart =
    clean(
        $_POST['size_chart'] ?? ''
    );


$priceRaw =
    $_POST['price'] ?? '';


$stockRaw =
    $_POST['stock'] ?? '';


$category =
    clean(
        $_POST['category'] ?? ''
    );


if (
    $name === '' ||
    mb_strlen($name) < 2 ||
    mb_strlen($name) > 100
) {

    showError(
        "Product name must be between 2 and 100 characters."
    );
}


if (
    $description === '' ||
    mb_strlen($description) < 5 ||
    mb_strlen($description) > 1000
) {

    showError(
        "Description must be between 5 and 1000 characters."
    );
}


if (
    $sizeChart !== '' &&
    (
        $sizeChart[0] === '{' ||
        $sizeChart[0] === '['
    )
) {

    json_decode(
        $sizeChart,
        true
    );


    if (
        json_last_error() !== JSON_ERROR_NONE
    ) {

        showError(
            "Size chart contains invalid JSON."
        );
    }
}


if (
    $priceRaw === '' ||
    !is_numeric($priceRaw) ||
    (float)$priceRaw <= 0
) {

    showError(
        "Price must be greater than 0."
    );
}

if (
    $priceRaw  > 999.99
) {

    showError(
        "Price must be less than or equal to 999.99."
    );
}


$price =
    (float)$priceRaw;


if (
    $stockRaw === '' ||
    filter_var(
        $stockRaw,
        FILTER_VALIDATE_INT
    ) === false ||
    (int)$stockRaw < 0
) {

    showError(
        "Stock must be a whole number 0 or greater."
    );
}


$stock = (int)$stockRaw;


$categories = [

    'men',
    'woman',
    'child'

];


if (
    !in_array(
        $category,
        $categories,
        true
    )
) {

    showError(
        "Invalid category selected."
    );
}


if (
    !isset($_FILES['image']) ||
    $_FILES['image']['error'] !== UPLOAD_ERR_OK
) {

    showError(
        "Please upload a valid product image."
    );
}


$file =
    $_FILES['image'];


if (
    $file['size'] > 2 * 1024 * 1024
) {

    showError(
        "Image must be 2MB or smaller."
    );
}


$allowedMimeTypes = [

    'image/jpeg' => 'jpg',

    'image/png' => 'png'

];


$finfo =
    new finfo(
        FILEINFO_MIME_TYPE
    );


$mimeType =
    $finfo->file(
        $file['tmp_name']
    );


if (
    !isset(
        $allowedMimeTypes[$mimeType]
    )
) {

    showError(
        "Only JPEG and PNG images are allowed."
    );
}


$imageInfo =
    @getimagesize(
        $file['tmp_name']
    );


if (
    $imageInfo === false
) {

    showError(
        "Uploaded file is not a valid image."
    );
}


$uploadDir = __DIR__ . '../../images/products/';


if (
    !is_dir($uploadDir)
) {

    if (
        !mkdir(
            $uploadDir,
            0755,
            true
        )
    ) {

        showError(
            "Could not create upload directory."
        );
    }
}


$extension =
    $allowedMimeTypes[$mimeType];


$fileName =
    bin2hex(
        random_bytes(16)
    ) .
    '.' .
    $extension;


$destination =
    $uploadDir .
    $fileName;


if (
    !move_uploaded_file(
        $file['tmp_name'],
        $destination
    )
) {

    showError(
        "Could not save the uploaded image."
    );
}


$filePath =
    '../../images/products/' .
    $fileName;


$sql = "
    INSERT INTO products
    (
        proName,
        proDesc,
        proPrice,
        proImg,
        proSize,
        proQuantity,
        proCategory
    )
    VALUES (?, ?, ?, ?, ?, ?, ?)
";


$stmt =
    $conn->prepare($sql);


if (!$stmt) {

    unlink($destination);

    showError(
        "Database query preparation failed: " .
            $conn->error
    );
}


$stmt->bind_param(
    "ssdssis",
    $name,
    $description,
    $price,
    $filePath,
    $sizeChart,
    $stock,
    $category
);


if (!$stmt->execute()) {

    unlink($destination);

    showError(
        "Could not insert product: " .
            $stmt->error
    );
}


$stmt->close();

$conn->close();


header(
    "Location: ../view/index.html?success=true&message=" .
        urlencode(
            "Product added successfully!"
        )
);


exit;
