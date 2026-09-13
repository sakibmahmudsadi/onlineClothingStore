<html>

<body>


<h2>
Order Details
</h2>


<table border="1">


<tr>

<th>
Product ID
</th>


<th>
Quantity
</th>


<th>
Unit Price
</th>


<th>
Total
</th>


</tr>



<?php


while($row=$items->fetch_assoc())

{


?>


<tr>


<td>
<?php echo $row["proID"]; ?>
</td>


<td>
<?php echo $row["quantity"]; ?>
</td>


<td>
<?php echo $row["unitPrice"]; ?>
</td>


<td>

<?php

echo $row["quantity"] * $row["unitPrice"];

?>

</td>


</tr>



<?php

}


?>


</table>


</body>

</html>