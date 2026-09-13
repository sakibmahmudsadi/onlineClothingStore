function validateCheckout() {

    
    let address = document.getElementById("address").value.trim();
    let paymentMethod = document.getElementById("payment_method").value;

   
    let addressError = document.getElementById("address-js-error");
    let paymentError = document.getElementById("payment-js-error");

    addressError.innerHTML = "";
    paymentError.innerHTML = "";

    let isValid = true;


    if (address === "") {

        addressError.innerHTML = "Address must not be empty";

        isValid = false;
    }


    if (paymentMethod === "") {

        paymentError.innerHTML = "Payment method must not be empty";

        isValid = false;
    }


    
    return isValid;
}