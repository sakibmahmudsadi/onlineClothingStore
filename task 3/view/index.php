<?php

session_start();

require "../model/products.php";

$productModel = new productModel();

if(isset($_GET['search']) && $_GET['search'] != ""){
    $products = $productModel->searchProduct($_GET['search']);
}
else{
    $products = $productModel->allProduct();
}

if(isset($_SESSION['cart'])){
    $cartCount = count($_SESSION['cart']);
}
else{
    $cartCount = 0;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Catalog</title>

    <link rel="stylesheet" href="styles.css">

</head>

<body>

<div class="header">

    <div>Clothing Store</div>

    <div>

        <form onsubmit="myajax(); return false;">

            <input type="text" name="search" id="search">

            <input type="submit" value="Search">

        </form>

    </div>

    <div>

        <a href="cart.php">
            (<?php echo $cartCount ?>) Cart
        </a>

    </div>

</div>


<div class="products" id="products">

<?php while($product = $products->fetch_assoc()){ ?>

    <div class="product">

        <img src="../<?php echo $product['proImg'] ?>">

        <h3 class="zero">

            <a href="details.php?id=<?php echo $product['proID'] ?>">

                <?php echo $product['proName'] ?>

            </a>

        </h3>

        <p class="zero">

            <a href="details.php?id=<?php echo $product['proID'] ?>">

                ৳<?php echo $product['proPrice'] ?>

            </a>

        </p>

    </div>

<?php } ?>

</div>


<script src="../myjs.js"></script>

</body>

</html>