<?php
    function connectionDB(){
        $conn = mysqli_connect('localhost', 'root', '');
        return $conn;
    }
?>