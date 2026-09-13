<!DOCTYPE html>  
<html>  
  
<head>  
  
<title>Purchase History</title>  
  
  
<style>  
  
body{  
  
    font-family: Arial;  
    margin:40px;  
  
}  
  
  
table{  
  
    width:80%;  
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
  
  
</style>  
  
  
</head>  
  
  
  
<body>  
  
  
<h2>My Purchase History</h2>  
  
  
  
<table>  
  
  
<tr>  
  
<th>Order ID</th>  
  
<th>Date</th>  
  
<th>Total Amount</th>  
  
<th>Status</th>  
  
<th>Details</th>  
  
</tr>  
  
  
  
<?php  
  
  
if($orders->num_rows > 0)  
{  
  
  
    while($row = $orders->fetch_assoc())  
    {  
  
  
?>  
  
  
<tr>  
  
  
<td>  
  
<?php echo $row["orderID"]; ?>  
  
</td>  
  
  
  
<td>  
  
<?php echo $row["orderDate"]; ?>  
  
</td>  
  
  
  
<td>  
  
<?php echo $row["totalAmount"]; ?> BDT  
  
</td>  
  
  
  
<td>  
  
<?php echo $row["status"]; ?>  
  
</td>  
  
  
  
<td>  


<a href="../control/order_details_control.php?orderID=<?php echo $row["orderID"]; ?>">  

View Details  

</a>


<br><br>


<a href="../control/invoice_control.php?orderID=<?php echo $row["orderID"]; ?>">  

View Invoice  

</a>


</td>  
  
  
</tr>  
  
  
  
<?php  
  
    }  
  
}  
  
else  
{  
  
?>  
  
  
<tr>  
  
<td colspan="5">  
  
No Order Found  
  
</td>  
  
</tr>  
  
  
<?php  
  
}  
  
  
?>  
  
  
</table>  
  
  
  
</body>  
  
  
</html>