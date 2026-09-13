<?php

session_start();

$orderID = $_SESSION["orderID"] ?? 0;
$totalAmount = $_SESSION["totalAmount"] ?? 0;
$paymentMethod = $_SESSION["paymentMethod"] ?? "";
$status = "Pending";

?>


<!DOCTYPE html>
<html>

<head>

<title>Order Confirmation</title>


<style>

body{

    font-family: Arial, sans-serif;
    text-align:center;
    margin-top:50px;

}


.box{

    width:500px;
    margin:auto;
    padding:30px;
    border:1px solid #ccc;
    border-radius:10px;

}


h2{

    color:green;

}


.info{

    font-size:18px;
    margin:15px;

}


button{

    padding:10px 25px;
    cursor:pointer;

}


</style>


</head>



<body>


<div class="box">


<h2>
Order Placed Successfully!
</h2>



<div class="info">

Order ID: #<?php echo $orderID; ?>

</div>



<div class="info">

Total Amount: 
<?php echo $totalAmount; ?> BDT

</div>



<div class="info">

Payment Method:
<?php echo $paymentMethod; ?>

</div>



<div class="info">

Status:
<?php echo $status; ?>

</div>



<p>

Thank you for shopping with us.

</p>



<a href="../control/invoice_control.php?orderID=<?php echo $orderID; ?>">

<button>

View Invoice

</button>

</a>



</div>



</body>


</html>