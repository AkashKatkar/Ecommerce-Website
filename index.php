<?php
    date_default_timezone_set("Asia/Calcutta");
    ini_set("display_errors", 1);
    ini_set("log_errors", 1);
    ini_set("error_log",'error_log.txt');

    $conn = new mysqli("localhost","root","", "ecommerce_website");
    if ($conn->error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if(!empty(extract($_POST))){
        if($_POST["func"] == "deleteCategory"){
            if($_POST["source"] == "category"){
                $stmt = $conn->prepare("DELETE FROM category WHERE code=?");
            }else if($_POST["source"] == "subcategory"){
                $stmt = $conn->prepare("DELETE FROM category WHERE code=?");
            }else if($_POST["source"] == "product"){
                $stmt = $conn->prepare("DELETE FROM product WHERE id=?");
            }
            $stmt->bind_param("s", $_POST["code"]);
            if($stmt->execute()){
                $return_data["ack"] = "yes";
            }else{
                $return_data["ack"] = "no";
            }
        }else if($_POST["func"] == "editCancel"){
            if($_POST["source"] == "category"){
                $stmt = $conn->prepare("UPDATE category SET category_name=?, code=? WHERE code=?");
                $stmt->bind_param("sss", $_POST["name"], $_POST["code"], $_POST["oldCode"]);
            }else if($_POST["source"] == "subcategory"){
                $stmt = $conn->prepare("UPDATE category SET category_name=?, code=?, parent_id=(SELECT id FROM category WHERE category_name=?) WHERE code=?");
                $stmt->bind_param("ssss", $_POST["name"], $_POST["code"], $_POST["category"], $_POST["oldCode"]);
            }else{
                $stmt = $conn->prepare("UPDATE product SET prod_name=?, code=(SELECT code FROM category WHERE category_name=?), price=? WHERE id=?");
                $stmt->bind_param("ssis", $_POST["name"], $_POST["category"], $_POST["price"], $_POST["oldCode"]);
            }
            if($stmt->execute()){
                $return_data["ack"] = "yes";
            }else{
                $return_data["ack"] = "no";
            }
        }
        echo json_encode($return_data);
        exit();
    }
?>
<html>
    <head>
        <title>Ecommerce Website</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="js/index.js"></script>
        <link href="css/index.css" rel="stylesheet"/>
    </head>
    <body>
        <div id="index_tables_onload"></div>
    </body>
</html>