<?php
    require ("functions.php");
    if(!empty(extract($_POST))){
        if($_POST["func"] == "deleteItem"){
            delete_OR_restore_Item();
        }else if($_POST["func"] == "editCancel"){
            editItem();
        }
        $stmt->close();
        $conn->close();
        echo json_encode($return_data);
        exit();
    }
?>