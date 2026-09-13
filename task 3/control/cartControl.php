<?php

session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


if (isset($_GET['action']) && $_GET['action'] == "add") {

    $id = $_GET['id'];
    $quantity = $_GET['quantity'];
    $size = $_GET['size'];

    $item = [
        "proID" => $id,
        "quantity" => $quantity,
        "size" => $size
    ];

    $_SESSION['cart'][] = $item;

    echo "Added to cart";
}


if (isset($_GET['action']) && $_GET['action'] == "update") {

    $id = $_GET['id'];
    $change = $_GET['change'];

    foreach ($_SESSION['cart'] as $key => $item) {

        if ($item['proID'] == $id) {

            $_SESSION['cart'][$key]['quantity'] += $change;

            if ($_SESSION['cart'][$key]['quantity'] < 1) {
                $_SESSION['cart'][$key]['quantity'] = 1;
            }

            break;
        }
    }

    echo "Updated cart";
}


if (isset($_GET['action']) && $_GET['action'] == "remove") {

    $id = $_GET['id'];

    if (isset($_GET['change'])) {

        $change = $_GET['change'];

        foreach ($_SESSION['cart'] as $key => $item) {

            if ($item['proID'] == $id) {

                $_SESSION['cart'][$key]['quantity'] += $change;

                if ($_SESSION['cart'][$key]['quantity'] < 1) {
                    $_SESSION['cart'][$key]['quantity'] = 1;
                }

                break;
            }
        }

        echo "Updated cart";

    }
    else {

        foreach ($_SESSION['cart'] as $key => $item) {

            if ($item['proID'] == $id) {

                unset($_SESSION['cart'][$key]);

                break;
            }
        }

        echo "Removed from cart";

    }

}

?>