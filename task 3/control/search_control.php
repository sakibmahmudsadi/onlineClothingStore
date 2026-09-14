<?php

require "../model/products.php";

$productModel = new productModel();

if (isset($_GET["search"])) {

    $search = $_GET["search"];
    $category = $_GET["category"] ?? "";

    if ($search != "" || $category != "") {
        $products = $productModel->searchProductWithCategory($search, $category);
    }
    else {
        $products = $productModel->allProduct();
    }

    if ($products && $products->num_rows > 0) {

        while ($product = $products->fetch_assoc()) {
?>

            <div class="product">

                <img src="../<?php echo $product['proImg']; ?>">

                <h3 class="zero">

                    <a href="details.php?id=<?php echo $product['proID']; ?>">

                        <?php echo $product['proName']; ?>

                    </a>

                </h3>

                <p class="zero">

                    <a href="details.php?id=<?php echo $product['proID']; ?>">

                        ৳<?php echo $product['proPrice']; ?>

                    </a>

                </p>

            </div>

<?php
        }

    }
    else {

        echo "<p>No products found.</p>";

    }

}

?>
