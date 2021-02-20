<?php
    date_default_timezone_set("Asia/Calcutta");
    ini_set("display_errors", 1);
    ini_set("log_errors", 1);
    ini_set("error_log",'error_log.txt');

    $conn = new mysqli("localhost","root","", "ecommerce_website");
    if ($conn->error) {
        die("Connection failed: " . $conn->connect_error);
    }

	function inactiveData($source){
		global $conn;
		global $row;
		$stmt1 = $conn->prepare("UPDATE category SET status='inactive' WHERE code=?");
		if($source == 'category'){
			$stmt1->bind_param("s", $row["code"]);
		}else{
			$stmt1->bind_param("s", $_POST["code"]);
		}
		$stmt1->execute();

		$stmt1 = $conn->prepare("SELECT id FROM product WHERE code=?");
		if($source == 'category'){
			$stmt1->bind_param("s", $row["code"]);
		}else{
			$stmt1->bind_param("s", $_POST["code"]);
		}
		$stmt1->execute();
		$result1 = $stmt1->get_result();
		while($row1 = $result1->fetch_assoc()){
			$stmt1 = $conn->prepare("UPDATE product SET status='inactive' WHERE id=?");
			$stmt1->bind_param("i", $row1["id"]);
			$stmt1->execute();
		}
	}
	
    if(!empty(extract($_POST))){
        if($_POST["func"] == "deleteItem"){
            if($_POST["source"] == "category"){
                $stmt = $conn->prepare("UPDATE category SET status='inactive' WHERE code=?");
				$stmt1 = $conn->prepare("SELECT code FROM category WHERE parent_id=(SELECT id from category WHERE code=?);");
				$stmt1->bind_param("s", $_POST["code"]);
				$stmt1->execute();
				$result = $stmt1->get_result();
				while($row = $result->fetch_assoc()){
					inactiveData('category');
				}
            }else if($_POST["source"] == "subcategory"){
                $stmt = $conn->prepare("UPDATE category SET status='inactive' WHERE code=?");
				inactiveData('subcategory');
            }else if($_POST["source"] == "product"){
                $stmt = $conn->prepare("UPDATE product SET status='inactive' WHERE id=?");
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

				$stmt1 = $conn->prepare("UPDATE product SET code=? WHERE code=?");
				$stmt1->bind_param("ss",$_POST["code"], $_POST["oldCode"]);
				$stmt1->execute();
            }else{
                $file = $_FILES["image_file".$_POST["position"]];
				if(basename($file["name"]) != NULL){
					$file_name = "images/".basename($file["name"]);
					move_uploaded_file($file["tmp_name"], "images/" . basename($file["name"]));
				}
                $file_name = $_POST["image"];
                $stmt = $conn->prepare("UPDATE product SET prod_name=?, code=(SELECT code FROM category WHERE category_name=?), price=?, product_image=? WHERE id=?");
                $stmt->bind_param("sssss", $_POST["name"], $_POST["category"], $_POST["price"], $file_name, $_POST["oldCode"]);
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