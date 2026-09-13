<?php


include "../model/db.php";

include "../model/order_model.php";



$db = new mydb();

$conn = $db->openConn();



$order = new orderModel();



$orderID = $_GET["orderID"];



$items = $order->getOrderDetails(
    $orderID,
    $conn
);



include "../view/order_details.php";


?>