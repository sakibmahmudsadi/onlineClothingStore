<?php


include "../model/db.php";
include "../model/order_model.php";



$db = new mydb();

$conn = $db->openConn();



$order = new orderModel();


// temporary user id for testing

$userID = 1;



$orders = $order->getPurchaseHistory(
    $userID,
    $conn
);



include "../view/purchase_history.php";


?>