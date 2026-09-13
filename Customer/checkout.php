<?php 

include "../control/checkout_control.php"; 

?>

<!DOCTYPE html>

<html>

<head>

    <title>Checkout</title>

    <script src="../js/checkout_validation.js"></script>

</head>


<body>


<h2>Checkout Page</h2>


<form 
    action="" 
    method="post" 
    onsubmit="return validateCheckout();"
>


    <!-- Delivery Address -->

    <label for="address">
        Delivery Address:
    </label>

    <br>


    <input 
        type="text"
        id="address"
        name="address"
        value="<?php echo htmlspecialchars($address); ?>"
    >


    <br>


    <span
        id="address-js-error"
        style="color:red;"
    ></span>


    <span style="color:red;">
        <?php echo $addressError; ?>
    </span>


    <br><br>



    <!-- Payment Method -->


    <label for="payment_method">
        Payment Method:
    </label>

    <br>


    <select 
        id="payment_method"
        name="payment_method"
    >


        <option value="">
            Select Payment Method
        </option>


        <option value="Credit Card"
        <?php if($paymentMethod=="Credit Card") echo "selected"; ?>
        >
            Credit Card
        </option>


        <option value="bKash"
        <?php if($paymentMethod=="bKash") echo "selected"; ?>
        >
            bKash
        </option>


        <option value="Nagad"
        <?php if($paymentMethod=="Nagad") echo "selected"; ?>
        >
            Nagad
        </option>


        <option value="Bank Transfer"
        <?php if($paymentMethod=="Bank Transfer") echo "selected"; ?>
        >
            Bank Transfer
        </option>


        <option value="Cash on Delivery"
        <?php if($paymentMethod=="Cash on Delivery") echo "selected"; ?>
        >
            Cash on Delivery
        </option>


    </select>


    <br>


    <span
        id="payment-js-error"
        style="color:red;"
    ></span>


    <span style="color:red;">
        <?php echo $paymentError; ?>
    </span>


    <br><br>



    <!-- Cancel Button -->

    <a href="#">
        <button 
            type="button"
        >
            Cancel
        </button>
    </a>



    <!-- Place Order Button -->


    <input 
        type="submit"
        name="placeOrder"
        value="Place Order"
    >


</form>


</body>

</html>