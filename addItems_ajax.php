<?php
    require ("functions.php");
    if(!empty(extract($_POST))){
        if($_POST["func"] == "getCategory"){
            getCategory();
        }elseif($_POST["func"] == "getSubCategory"){
            getSubCategory();
        }else if($_POST["func"] == "addSubCategory"){
            addSubCategory();
        }else if($_POST["func"] == "addProduct"){
            addProduct();
        }else if($_POST["func"] == "getRestore_data"){
            getRestoreData();
        }else if($_POST["func"] == "restoreData"){
            delete_OR_restore_Item();
        }else if($_POST["func"] == "permanentDeleteData"){
            delete_OR_restore_Item();
        }else{
            addCategory();
        }
        $stmt->close();
        $conn->close();
        echo json_encode($return_data);
        exit();
    }
?>