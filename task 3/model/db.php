<?php 

class db{
    function openCon(){
        return new mysqli("localhost","root","","g7cs");
    }
}

?>
