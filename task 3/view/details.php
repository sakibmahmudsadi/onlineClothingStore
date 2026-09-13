<?php

session_start();

require "../model/products.php";

$productModel = new productModel();

$id = $_GET['id'];

$result = $productModel->searchProductID($id);

$product = $result->fetch_assoc();

if (!$product) {
    echo "Product not found";
    exit;
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Product Details</title>

    <link rel="stylesheet" href="styles.css">

</head>

<body>

<h1>Product Details</h1>

<div class="details">

    <div class="detailsImage">

        <img src="../<?php echo $product['proImg']; ?>">

    </div>

    <div class="detailsInfo">

        <h1>
            <?php echo $product['proName']; ?>
        </h1>

        <p>
            <?php echo $product['proDesc']; ?>
        </p>

        <h2>
            ৳<?php echo $product['proPrice']; ?>
        </h2>

        <form onsubmit="addCartAjax(this); return false;">

            <input type="hidden"
                   name="id"
                   value="<?php echo $product['proID']; ?>">

            <label>Size:</label>

            <select name="size">

                <option value="M">M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
                <option value="XXL">XXL</option>

            </select>

            <br><br>

            <label>Quantity:</label>

            <input type="number"
                   name="quantity"
                   value="1"
                   min="1">

            <br><br>

            <input type="submit"
                   value="Add to Cart">

        </form>

        <br>

        <a href="cart.php">Go to Cart</a>

    </div>

</div>

<script src="../myjs.js"></script>

</body>

</html>