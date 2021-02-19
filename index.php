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
            }else{
                $file = $_FILES["image_file".$_POST["position"]];
				if(basename($file["name"]) != NULL){
					$file_name = "images/".basename($file["name"]);
					move_uploaded_file($file["tmp_name"], "images/" . basename($file["name"]));
				}
                $file_name = $_POST["image"];
                $stmt = $conn->prepare("UPDATE product SET prod_name=?, code=(SELECT code FROM category WHERE category_name=?), price=?, product_image=? WHERE id=?");
                $stmt->bind_param("ssiss", $_POST["name"], $_POST["category"], $_POST["price"], $file_name, $_POST["oldCode"]);
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
<html lang="en">
	<head>
        <title>Ecommerce Website</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<link rel="stylesheet" href="css/style.css">
        <link href="css/index.css" rel="stylesheet"/>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
		<script src="js/index.js"></script>
	</head>
  	<body>
		<div class="wrapper d-flex align-items-stretch">
			<nav id="sidebar">
				<div class="custom-menu">
					<button type="button" id="sidebarCollapse" class="btn btn-primary">
						<i class="fa fa-bars"></i>
						<span class="sr-only">Toggle Menu</span>
					</button>
				</div>
				<div class="p-4">
					<h3><a href="index.html" class="logo">E-Commerce</a></h3>
					<ul class="list-unstyled components mb-5">
						<li class="active inactive_nav nav1" onclick="which_nav('category')">
							<a href="#"><span class="fa fa-copyright mr-3" style="font-size:18px"></span> Category</a>
						</li>
						<li class="inactive_nav nav2" onclick="which_nav('subcategory')">
							<a href="#"><span class="fa fa-creative-commons mr-3"></span> Sub-Category</a>
						</li>
						<li class="inactive_nav nav3" onclick="which_nav('product')">
							<a href="#"><span class="fa fa-product-hunt mr-3"></span> Product</a>
						</li>
					</ul>
				</div>
    		</nav>

			<!-- Page Content  -->
			<div class="container" id="content" style="margin-top: 70px;margin-bottom: 50px;">
				<div class="card">
					<div class="card-header" style='text-align: center;font-size:25px; padding: 3px;'>Category</div>
					
					<div class="card-body">
						<div class="dropdown" style="margin-bottom: 30px;width: 100%;">
							<button type="button" class="btn btn-info dropdown-toggle dropdown" style="width: 60px;height: 45px;padding-right: 25px;" data-toggle="dropdown">5</button>
							<div class="dropdown-menu">
								<a class="dropdown-item showRows5">5</a>
								<a class="dropdown-item showRows10">10</a>
								<a class="dropdown-item showRows15">15</a>
								<a class="dropdown-item showRows20">20</a>
							</div>

							<div class="addData float-right">
								<button type="button" class="btn btn-info" style="height: 45px;" onclick="redirectAddItems('addCategory')">Add Category</button>
							</div>
						</div>
						<div id="index_tables_onload"></div>
						
						<!-- <button class="btn"><i class="fa fa-home"></i></button> -->
					</div> 
				</div>
			</div>
		</div>
		<script src="js/main.js"></script> 
  	</body>
</html>