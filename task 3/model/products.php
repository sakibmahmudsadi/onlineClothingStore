<?php

require "db.php";

class productModel{

    function allProduct(){
        $db = new db();
        $con = $db->openCon();

        $sql = "SELECT * FROM products";
        return $con->query($sql);
    }

    function searchProduct($name){
        $db = new db();
        $con = $db->openCon();

        $sql = "SELECT * FROM products WHERE proName LIKE '%$name%'";
        return $con->query($sql);
    }

    function searchProductID($id){
        $db = new db();
        $con = $db->openCon();

        $sql = "SELECT * FROM products WHERE proID=$id";
        return $con->query($sql);
    }

    function saveCart($sessionID, $proID, $size, $quantity) {

    $db = new db();
    $con = $db->openCon();

    $sql = "INSERT INTO cart (sessionID, proID, size, quantity)
            VALUES ('$sessionID', '$proID', '$size', '$quantity')";

    return $con->query($sql);
}

}

?>
