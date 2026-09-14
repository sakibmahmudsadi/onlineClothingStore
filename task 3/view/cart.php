<?php

session_start();

require "../model/products.php";

$productModel = new productModel();

if(isset($_POST['buy'])){

    header("Location: ../customer/view/checkout.php");
    exit;

}

if(isset($_POST['back'])){

    header("Location: index.php");
    exit;

}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Cart</title>

    <link rel="stylesheet" href="styles.css">

</head>

<body>

<h1>My Cart</h1>

<?php

$total = 0;

if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0){

    foreach($_SESSION['cart'] as $item){
        $result = $productModel->searchProductID($item['proID']);
        $product = $result->fetch_assoc();
        $subtotal = $product['proPrice'] * $item['quantity'];
        $total = $total + $subtotal;
?>

        <div class="cartItem" id="item-<?php echo $item['proID']; ?>">

            <img src="../<?php echo $product['proImg'] ?>">

            <div>

                <h2>
                    <?php echo $product['proName'] ?>
                </h2>

                <p id="price-<?php echo $item['proID']; ?>" data-price="<?php echo $product['proPrice']; ?>">
                    Price: ৳<?php echo $product['proPrice'] ?>
                </p>

                <p>
                    Size: <?php echo $item['size'] ?>
                </p>

                <p>
                    Quantity: 
                    <span id="qty-<?php echo $item['proID']; ?>"><?php echo $item['quantity'] ?></span>
                    <button
                    onclick="updateCart(<?php echo $item['proID'] ?>,-1)">
                    -1
                    </button>
                    <button
                    onclick="updateCart(<?php echo $item['proID'] ?>,1)">
                    +1
                </button>
                </p>

                <p id="subtotal-<?php echo $item['proID']; ?>">
                    Subtotal: ৳<?php echo $subtotal ?>
                </p>

                <p>
                    <?php echo $product['proDesc'] ?>
                </p>

                <button
                    onclick="removeCartAjax(<?php echo $item['proID'] ?>)">
                    Remove
                </button>
            </div>
        </div>

<?php

    }

?>

    <h2 id="cart-total">
        Total: ৳<?php echo $total ?>
    </h2>

    <form method="POST">
        <input type="submit"
               name="buy"
               value="Buy Now">
    </form>
<?php

}
else{
    echo "Cart is empty";
}

?>

<form method="POST">

    <input type="submit"
           name="back"
           value="Go Back">

</form>


<script src="../myjs.js"></script>

</body>

</html>