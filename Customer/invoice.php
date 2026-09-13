<!DOCTYPE html> 
<html> 
 
<head> 
 
<title>Invoice</title> 
 
<style> 
 
body{ 
    font-family: Arial; 
    margin:40px; 
} 
 
 
table{ 
 
    width:70%; 
    border-collapse:collapse; 
 
} 
 
 
th,td{ 
 
    border:1px solid black; 
    padding:10px; 
    text-align:center; 
 
} 
 
 
th{ 
 
    background:#ddd; 
 
} 
 
 
.invoice-box{ 
 
    width:70%; 
 
} 
 
 
</style> 
 
 
</head> 
 
 
<body> 
 
 
<div class="invoice-box"> 
 
 
<h2>Order Invoice</h2> 
 
 
<?php
 
if($orderInfo->num_rows > 0) 
{ 
 
    $order = $orderInfo->fetch_assoc(); 
 
 
?> 
 
 
<h3>
Order ID:
<?php echo $order["orderID"]; ?> 
</h3> 
 
 
<p> 
 
Order Date:
<?php echo $order["orderDate"]; ?> 
 
</p> 
 
 
<p> 
 
Status:
<?php echo $order["status"]; ?> 
 
</p> 
 
 
<br>


<!-- Product Details Added -->

<h3>Product Details</h3>


<table>

<tr>

<th>Product ID</th>
<th>Quantity</th>
<th>Unit Price</th>
<th>Total</th>

</tr>


<?php

if($items->num_rows > 0)
{

    while($item = $items->fetch_assoc())
    {

?>


<tr>

<td>
<?php echo $item["proID"]; ?>
</td>


<td>
<?php echo $item["quantity"]; ?>
</td>


<td>
<?php echo $item["unitPrice"]; ?>
</td>


<td>
<?php echo $item["quantity"] * $item["unitPrice"]; ?>
</td>


</tr>


<?php

    }

}

?>


</table>


<br>



<!-- Total Amount -->

<table> 
 
<tr> 
 
<th>Total Amount</th> 
 
<th>
<?php echo $order["totalAmount"]; ?> BDT
</th> 
 
</tr> 
 
</table> 
 
 
<br> 
 
 
 
<h3>Payment Information</h3> 
 
 
<?php 
 
if($paymentInfo->num_rows > 0) 
{ 
 
$payment = $paymentInfo->fetch_assoc(); 
 
 
?> 
 
 
<table> 
 
 
<tr> 
 
<th>Payment Method</th> 
 
<td> 
 
<?php echo $payment["paymentMethod"]; ?> 
 
</td> 
 
 
</tr> 
 
 
 
<tr> 
 
<th>Payment Amount</th> 
 
<td> 
 
<?php echo $payment["amount"]; ?> BDT 
 
</td> 
 
 
</tr> 
 
 
 
</table> 
 
 
 
<?php 
 
} 
 
?> 
 
 
<br> 
 
 
<a href="history_control.php"> 
 
Back to Purchase History 
 
</a> 
 
 
 
<?php 
 
} 
 
?> 
 
 
</div> 
 
 
</body> 
 
 
</html>