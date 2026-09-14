function myajax(){

    var data = document.getElementById("search").value;
    var catElem = document.getElementById("category");
    var category = catElem ? catElem.value : "";

    var xttp = new XMLHttpRequest();

    xttp.onreadystatechange = function(){

        if(this.readyState == 4 && this.status == 200){

            document.getElementById("products").innerHTML =
                this.responseText;

            console.log(this.responseText);
        }

    };

    xttp.open(
        "GET",
        "../control/search_control.php?search=" +
        encodeURIComponent(data) + "&category=" + encodeURIComponent(category),
        true
    );

    xttp.send();

}


function addCartAjax(form){

    var id = form.elements["id"].value;

    var quantity = form.elements["quantity"].value;

    var size = form.elements["size"].value;

    var maxStock = parseInt(form.elements["quantity"].getAttribute("max") || 9999);

    if(parseInt(quantity) > maxStock){
        alert("Only " + maxStock + " items available in stock!");
        return;
    }


    var xttp = new XMLHttpRequest();

    xttp.onreadystatechange = function(){

        if(this.readyState == 4 && this.status == 200){

            console.log(this.responseText);

            alert(this.responseText);

            var badge = document.getElementById("cartCount");
            if(badge){
                badge.innerText = parseInt(badge.innerText) + 1;
            }
        }

    };

    xttp.open(
        "GET",
        "../control/cartControl.php?action=add&id=" + id +
        "&quantity=" + quantity +
        "&size=" + size,
        true
    );

    xttp.send();

}


function removeCartAjax(id){

    var xttp = new XMLHttpRequest();

    xttp.onreadystatechange = function(){

        if(this.readyState == 4 && this.status == 200){
            console.log(this.responseText);
            document.getElementById("item-"+id).remove();
            recalculateCartTotal();
        }

    };

    xttp.open(
        "GET",
        "../control/cartControl.php?action=remove&id=" + id,
        true
    );

    xttp.send();

}

function updateCart(id, change){
    var qty = document.getElementById("qty-"+id);
    var curQty = parseInt(qty.innerText);
    var newQty = curQty + change;

    if(newQty<1){
        return;
    }

    var xttp = new XMLHttpRequest();
    xttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            qty.innerText=newQty;

            var priceElement = document.getElementById("price-" + id);
            var unitPrice = parseFloat(priceElement.getAttribute("data-price"));
            var newSubtotal = unitPrice * newQty;
            document.getElementById("subtotal-" + id).innerText = "Subtotal: ৳" + newSubtotal.toFixed(2);
            
            recalculateCartTotal();
        }
    };

    xttp.open(
        "GET",
        "../control/cartControl.php?action=update&id=" + id + "&change=" + change,
        true
    );

    xttp.send();
}

function recalculateCartTotal(){
    var cartItems = document.getElementsByClassName("cartItem");
    var total = 0;

    for(var i = 0; i < cartItems.length; i++){
        var itemId = cartItems[i].id.replace("item-", "");
        var priceElement = document.getElementById("price-" + itemId);
        var qtyElement = document.getElementById("qty-" + itemId);
        if(priceElement && qtyElement){
            var unitPrice = parseFloat(priceElement.getAttribute("data-price"));
            var q = parseInt(qtyElement.innerText);
            total += (unitPrice * q);
        }
    }

    var totalDisplay = document.getElementById("cart-total");
    if(totalDisplay){
        totalDisplay.innerText = "Total: ৳" + total.toFixed(2);
    }
}