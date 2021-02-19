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
        if($_POST["func"] == "getCategory"){
            $stmt = $conn->prepare("SELECT category_name FROM category WHERE parent_id IS NULL");
            $stmt->execute();
            $result = $stmt->get_result();
            $arr = [];
            $i=0;
            while($row = $result->fetch_assoc()){
                $arr[$i] = $row["category_name"];
                $i = $i+1;
            }
            $return_data["allCategory"] = $arr;
        }elseif($_POST["func"] == "getSubCategory"){
            $stmt = $conn->prepare("SELECT category_name FROM category WHERE parent_id IS NOT NULL");
            $stmt->execute();
            $result = $stmt->get_result();
            $arr = [];
            $i=0;
            while($row = $result->fetch_assoc()){
                $arr[$i] = $row["category_name"];
                $i = $i+1;
            }
            $return_data["allCategory"] = $arr;
        }else if($_POST["func"] == "addSubCategory"){
            $stmt = $conn->prepare("SELECT * FROM category;");
            $stmt->execute();
            $result = $stmt->get_result();
            $count = mysqli_num_rows($result)+1;

            $stmt = $conn->prepare("SELECT id FROM category WHERE category_name=?");
            $stmt->bind_param("s", $_POST["select_category"]);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            $stmt = $conn->prepare("INSERT INTO category VALUES(?,?,?,?)");
            $stmt->bind_param("issi", $count, $_POST["category_name"], $_POST["category_code"], $row["id"]);
            if($stmt->execute()){
                $return_data["ack"] = "yes";
            }else{
                $return_data["ack"] = "no";
            }
        }else if($_POST["func"] == "addProduct"){
            $stmt = $conn->prepare("SELECT code FROM category WHERE category_name=?");
            $stmt->bind_param("s", $_POST["product_code"]);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            $stmt = $conn->prepare("SELECT id FROM product ORDER BY id DESC LIMIT 0, 1");
            $stmt->execute();
            $result = $stmt->get_result();
            $row1 = $result->fetch_assoc();
            $row1 = $row1["id"]+1;
            
            $file = $_FILES['img_file'];
            $file_location = "images/".basename($file["name"]);

            $stmt->prepare("INSERT INTO product VALUES(?,?,?,?,?)");
            $stmt->bind_param("issis", $row1, $_POST["product_name"], $row["code"], $_POST["product_price"], $file_location);
            if($stmt->execute()){
                $return_data["ack"] = "yes";
                move_uploaded_file($file["tmp_name"], "images/" . basename($file["name"]));
            }else{
                $return_data["ack"] = "no";
            }
        }else{
            $stmt = $conn->prepare("SELECT * FROM category;");
            $stmt->execute();
            $result = $stmt->get_result();
            $count = mysqli_num_rows($result)+1;
            
            $stmt = $conn->prepare("INSERT INTO category(id, category_name, code) VALUES(?, ?, ?)");
            $stmt->bind_param("iss", $count, $_POST["category_name"], $_POST["category_code"] );
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
        <title>Add Items</title>
        <link href="css/addItems.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="js/addItems.js"></script>
    </head>
    <body>
        <div class="main_div">
            <h2 class="heading"></h2>
            <form id="add_Category" method="POST" enctype="multipart/form-data">
                <label class="name"></label>
                <div class="main_category_name">
                    <input type="text" class="category_name" name="product_name"/>
                </div>
                <div class="outer_category_code">
                <br/>
                    <label class="code">Code</label>
                    <div class="main_category_code">
                        <input type="text" placeholder="Enter Unique Code" class="category_code"/>
                    </div>
                </div>
                <div class="outer_subcategory">
                <br/>
                    <select class="main_subcategory_name" name="product_code">
                        <option hidden class="subcategory_name">Select Category</option>
                    </select>
                </div>
                <div class="outer_price">
                    <br/>
                    <label class="price">Price</label>
                    <div class="main_product_price">
                        <input type="text" class="product_price" name="product_price"/>
                    </div>
                </div>
                <div class="outer_picture">
                    <br/>
                    <label class="picture">Select Picture</label>
                    <input type="file" id="product-img" name="img_file" class="product_picture"/>
                </div>
                
                <div class="main_addItemBtn">
                    <input type="button" value="SUBMIT" class="addItemBtn" onclick="addCategory()"/>
                </div>

                <input type="hidden" name="func" value="addProduct" class="hidden_tag"/>
            </form>
        </div>
    </body>
</html>