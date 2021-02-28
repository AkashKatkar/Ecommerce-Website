<?php
    require ("functions.php");
    if(!empty(extract($_POST))){
        switch ($_POST["func"]) {
            case "getCategory":
                getCategory();
                break;
            case "getSubCategory":
                getSubCategory();
                break;
            case "addSubCategory":
                addSubCategory();
                break;
            case "addProduct":
                addProduct();
                break;
            case "getRestore_data":
                getRestoreData();
                break;
            case "restoreData":
                delete_OR_restore_Item();
                break;
            case "permanentDeleteData":
                delete_OR_restore_Item();
                break;
            default:
                addCategory();
                break;
        }
        $stmt->close();
        $conn->close();
        echo json_encode($return_data);
        exit();
    }
?>