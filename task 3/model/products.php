<?php

require "db.php";

class productModel{

    function allProduct(){
        $db = new db();
        $con = $db->openCon();

        $stmt = $con->prepare("SELECT * FROM products");
        $stmt->execute();

        return $stmt->get_result();
    }

    function searchProduct($name){
        $db = new db();
        $con = $db->openCon();

        $searchTerm = "%" . $name . "%";
        $stmt = $con->prepare("SELECT * FROM products WHERE proName LIKE ?");
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();

        return $stmt->get_result();
    }

    function searchProductWithCategory($name, $category){
        $db = new db();
        $con = $db->openCon();

        if($name != "" && $category != ""){
            $searchTerm = "%" . $name . "%";
            $stmt = $con->prepare("SELECT * FROM products WHERE proName LIKE ? AND proCategory = ?");
            $stmt->bind_param("ss", $searchTerm, $category);
        }
        else if($name != ""){
            $searchTerm = "%" . $name . "%";
            $stmt = $con->prepare("SELECT * FROM products WHERE proName LIKE ?");
            $stmt->bind_param("s", $searchTerm);
        }
        else if($category != ""){
            $stmt = $con->prepare("SELECT * FROM products WHERE proCategory = ?");
            $stmt->bind_param("s", $category);
        }
        else{
            $stmt = $con->prepare("SELECT * FROM products");
        }

        $stmt->execute();

        return $stmt->get_result();
    }

    function searchProductID($id){
        $db = new db();
        $con = $db->openCon();

        $stmt = $con->prepare("SELECT * FROM products WHERE proID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result();
    }

    function saveCart($sessionID, $proID, $size, $quantity) {
        $db = new db();
        $con = $db->openCon();

        $stmt = $con->prepare("INSERT INTO cart (sessionID, proID, size, quantity) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sisi", $sessionID, $proID, $size, $quantity);

        return $stmt->execute();
    }

}

?>
