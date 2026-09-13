<?php

session_start();


include "../model/db.php"; 
include "../model/order_model.php"; 


$addressError = "";
$paymentError = "";


$address = "";
$paymentMethod = "";


$hasError = 0;



if (isset($_POST["placeOrder"])) {


    $address = trim($_POST["address"] ?? "");

    $paymentMethod = $_POST["payment_method"] ?? "";



    // Address validation

    if (empty($address)) {

        $addressError = "Address must not be empty";

        $hasError = 1;

    }



    // Payment validation

    if (empty($paymentMethod)) {


        $paymentError = "Payment method must not be empty";

        $hasError = 1;


    }

    else {


        $allowedMethods = [

            "Credit Card",
            "bKash",
            "Nagad",
            "Bank Transfer",
            "Cash on Delivery"

        ];



        if (!in_array($paymentMethod, $allowedMethods, true)) {


            $paymentError = "Invalid payment method";

            $hasError = 1;

        }

    }




    // Insert data if validation successful

    if ($hasError == 0) {


        $db = new mydb();

        $conn = $db->openConn();



        $order = new orderModel();



        // temporary user data

        $userID = 1;

        $totalAmount = 500;



        // Create Order

        $orderID = $order->createOrder(

            $userID,

            $totalAmount,

            $conn

        );




        if ($orderID) {



            // temporary product data

            $proID = 1;

            $quantity = 1;

            $unitPrice = 500;




            // Create Order Items

            $order->addOrderItems(

                $orderID,

                $proID,

                $quantity,

                $unitPrice,

                $conn

            );





            // Create Payment

            $order->addPayment(

                $orderID,

                $totalAmount,

                $paymentMethod,

                "",

                $conn

            );





            // Save data for order_success page

            $_SESSION["orderID"] = $orderID;

            $_SESSION["totalAmount"] = $totalAmount;

            $_SESSION["paymentMethod"] = $paymentMethod;



            header("Location: ../view/order_success.php");

            exit();


        }


    }


}


?>