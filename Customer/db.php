<?php

class mydb
{

    function openConn()
    {

        return new mysqli(
            "localhost",
            "root",
            "",
            "g7cs"
        );

    }

}

?>