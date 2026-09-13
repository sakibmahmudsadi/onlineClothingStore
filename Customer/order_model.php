<?php


class orderModel
{

    function createOrder($userID, $totalAmount, $conn)
    {

        $sql = "INSERT INTO orders
        (userID,totalAmount,status)
        VALUES
        ('$userID','$totalAmount','pending')";


        if($conn->query($sql))
        {
            return $conn->insert_id;
        }
        else
        {
            return false;
        }

    }



    function addOrderItems($orderID,$proID,$quantity,$unitPrice,$conn)
    {

        $sql = "INSERT INTO order_items
        (orderID,proID,quantity,unitPrice)
        VALUES
        ('$orderID','$proID','$quantity','$unitPrice')";


        return $conn->query($sql);

    }



    function addPayment($orderID,$amount,$paymentMethod,$transactionID,$conn)
    {

        $sql = "INSERT INTO payments
        (orderID,amount,paymentMethod,transactionID)
        VALUES
        ('$orderID','$amount','$paymentMethod','$transactionID')";


        return $conn->query($sql);

    }



    function getPurchaseHistory($userID, $conn)
    {

        $sql = "SELECT
                orderID,
                totalAmount,
                status,
                orderDate
                FROM orders
                WHERE userID='$userID'
                ORDER BY orderDate DESC";


        $result = $conn->query($sql);


        return $result;

    }

   
function getOrderDetails($orderID, $conn)
{

    $sql = "SELECT 
            order_items.proID,
            order_items.quantity,
            order_items.unitPrice

            FROM order_items

            WHERE order_items.orderID='$orderID'";


    return $conn->query($sql);

}

function getOrderInfo($orderID, $conn)
{

    $sql = "SELECT * FROM orders 
            WHERE orderID='$orderID'";

    return $conn->query($sql);

}



function getPaymentInfo($orderID, $conn)
{

    $sql = "SELECT * FROM payments 
            WHERE orderID='$orderID'";

    return $conn->query($sql);

}

function getInvoiceItems($orderID,$conn)
{
    $sql = "SELECT * FROM order_items 
            WHERE orderID='$orderID'";

    return $conn->query($sql);
}


}


?>