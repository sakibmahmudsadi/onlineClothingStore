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

if(isset($_SESSION['cart'])){
    $cartCount = count($_SESSION['cart']);
}
else{
    $cartCount = 0;
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Product Details</title>

    <link rel="stylesheet" href="styles.css">

</head>

<body>

<div class="header">

    <div>
        <a href="index.php">← Back to Store</a>
    </div>

    <div>Clothing Store</div>

    <div>
        <a href="cart.php">
            (<span id="cartCount"><?php echo $cartCount ?></span>) Cart
        </a>
    </div>

</div>


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

        <p>
            <b>Availability:</b> 
            <?php if($product['proQuantity'] > 0){ ?>
                <span style="color: green;">In Stock (<?php echo $product['proQuantity']; ?> available)</span>
            <?php } else { ?>
                <span style="color: red;">Out of Stock</span>
            <?php } ?>
        </p>

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
                   min="1"
                   max="<?php echo $product['proQuantity']; ?>">

            <br><br>

            <input type="submit"
                   value="Add to Cart"
                   <?php if($product['proQuantity'] <= 0) echo 'disabled'; ?>>

        </form>

        <br>

        <a href="cart.php">Go to Cart</a>

        <br><br>

        <h3>Size Chart</h3>

        <table border="1" style="border-collapse: collapse; width: 100%; text-align: center; margin-top: 10px;">
            <tr style="background-color: #f2f2f2;">
                <th>Size</th>
                <th>Chest (in)</th>
                <th>Length (in)</th>
            </tr>
            <tr>
                <td>M</td>
                <td>38 - 40</td>
                <td>28</td>
            </tr>
            <tr>
                <td>L</td>
                <td>40 - 42</td>
                <td>29</td>
            </tr>
            <tr>
                <td>XL</td>
                <td>42 - 44</td>
                <td>30</td>
            </tr>
            <tr>
                <td>XXL</td>
                <td>44 - 46</td>
                <td>31</td>
            </tr>
        </table>

    </div>

</div>

<script src="../myjs.js"></script>

</body>

</html>
