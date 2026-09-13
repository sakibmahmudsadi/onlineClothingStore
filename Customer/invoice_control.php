<?php

include "../model/db.php";
include "../model/order_model.php";


$db = new mydb();

$conn = $db->openConn();


$order = new orderModel();


$orderID = $_GET["orderID"];


$orderInfo = $order->getOrderInfo(
    $orderID,
    $conn
);


$paymentInfo = $order->getPaymentInfo(
    $orderID,
    $conn
);

$items = $order->getInvoiceItems(
    $orderID,
    $conn
);


include "../view/invoice.php";


?>