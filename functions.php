<?php
    date_default_timezone_set("Asia/Calcutta");
    ini_set("display_errors", 1);
    ini_set("log_errors", 1);
    ini_set("error_log",'error_log.txt');

    require_once ("db_conn.php");
    $conn = connectionDB();
    mysqli_select_db($conn, "ecommerce_website");

    function delete_OR_restore_Item(){
        global $conn, $return_data, $stmt, $row;
        if($_POST["source"] == "category"){
            $stmt = $conn->prepare("CALL incative_delete_active_category(?, ?);");
            $stmt->bind_param("ss", $_POST["operation"], $_POST["code"]);
        }else if($_POST["source"] == "subcategory"){
            if($_POST["operation"] == "delete_record"){
                $stmt = $conn->prepare("UPDATE category SET status='inactive' WHERE code=?");
            }else if($_POST["operation"] == "permanent_delete_data"){
                $stmt = $conn->prepare("DELETE FROM category WHERE code=?");
            }else{
                $stmt = $conn->prepare("UPDATE category SET status='active' WHERE code=?");
            }
            $stmt->bind_param("s", $_POST["code"]);
        }else if($_POST["source"] == "product"){
            if($_POST["operation"] == "delete_record"){
                $stmt = $conn->prepare("UPDATE product SET status='inactive' WHERE id=?");
            }else if($_POST["operation"] == "permanent_delete_data"){
                $stmt = $conn->prepare("DELETE FROM product WHERE id=?");
            }else{
                $stmt = $conn->prepare("UPDATE product SET status='active' WHERE id=?");
            }
            $stmt->bind_param("s", $_POST["code"]);
        }
        if($stmt->execute()){
            if($_POST["operation"] == "delete_record"){
                if($_POST["source"] == "category"){
                    $stmt = $conn->prepare("SELECT * FROM category WHERE parent_id IS NULL AND status='active'");
                }else if($_POST["source"] == "subcategory"){
                    $stmt = $conn->prepare("SELECT * FROM category WHERE parent_id IS NOT NULL AND status='active'");
                }else{
                    $stmt = $conn->prepare("SELECT * FROM product WHERE status='active'");
                }
                $stmt->execute();
                $result = $stmt->get_result();
                $return_data["remaining_rows"] = mysqli_num_rows($result);
            }
            $return_data["ack"] = "yes";
        }else{
            $return_data["ack"] = "no";
        }
    }
    
    function editItem(){
        global $conn, $return_data, $stmt;
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
    
    function getCategory(){
        global $conn, $return_data, $stmt;
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
    }

    function getSubCategory(){
        global $conn, $return_data, $stmt;
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
    }
    function addSubCategory(){
        global $conn, $return_data, $stmt;
        $stmt = $conn->prepare("SELECT * FROM category;");
        $stmt->execute();
        $result = $stmt->get_result();
        $count = mysqli_num_rows($result)+1;

        $stmt = $conn->prepare("SELECT id FROM category WHERE category_name=?");
        $stmt->bind_param("s", $_POST["select_category"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $stmt = $conn->prepare("INSERT INTO category VALUES(?,?,?,?,'active')");
        $stmt->bind_param("issi", $count, $_POST["category_name"], $_POST["category_code"], $row["id"]);
        if($stmt->execute()){
            $return_data["ack"] = "yes";
        }else{
            $return_data["ack"] = "no";
        }
    }
    function addProduct(){
        global $conn, $return_data, $stmt;
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

        $stmt->prepare("INSERT INTO product VALUES(?,?,?,?,?,'active')");
        $stmt->bind_param("issss", $row1, $_POST["product_name"], $row["code"], $_POST["product_price"], $file_location);
        if($stmt->execute()){
            $return_data["ack"] = "yes";
            move_uploaded_file($file["tmp_name"], "images/" . basename($file["name"]));
        }else{
            $return_data["ack"] = "no";
        }
    }
    function addCategory(){
        global $conn, $return_data, $stmt;
        $stmt = $conn->prepare("SELECT id FROM category ORDER BY id DESC LIMIT 0, 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $id=$row["id"]+1;
        
        $stmt = $conn->prepare("INSERT INTO category(id, category_name, code, status) VALUES(?, ?, ?, 'active')");
        $stmt->bind_param("iss", $id, $_POST["category_name"], $_POST["category_code"] );
        if($stmt->execute()){
            $return_data["ack"] = "yes";
        }else{
            $return_data["ack"] = "no";
        }
    }
    function getRestoreData(){
        global $conn, $return_data, $stmt;
        if($_POST["source"] == "category"){
            $stmt = $conn->prepare("SELECT category_name AS name, code FROM category WHERE parent_id IS NULL AND status='inactive'");
        }else if($_POST["source"] == "subcategory"){
            $stmt = $conn->prepare("SELECT c2.category_name AS name, c2.code FROM category c1, category c2 
                            WHERE c1.id=(SELECT c2.parent_id FROM category WHERE c2.parent_id IS NOT NULL AND c2.status='inactive' LIMIT 0,1)
                            AND c1.status='active' 
                            AND c1.parent_id IS NULL");
        }else{
            $stmt = $conn->prepare("SELECT product.prod_name AS name, product.id AS code
                            FROM product
                            INNER JOIN category ON category.code=product.code
                            WHERE product.status='inactive' AND category.status<>'inactive'");
        }
        $stmt->execute();
        $arr=[];
        $result = $stmt->get_result();
        $i=0;
        while($row = $result->fetch_assoc()){
            $arr[$i][0] = $row['name'];
            $arr[$i][1] = $row['code'];
            $i++;
        }
        $return_data["getData"] = $arr;
    }

    class Pagination{
        public $total_subcategory;

        public function myQuery($source){
            global $conn, $page, $showRows, $total_pages, $stmt, $result, $j, $num, $total_subcategory, $search;
            if($source == "category"){
                $stmt = $conn->prepare("SELECT * FROM category WHERE parent_id IS NULL AND status<>'inactive' AND (category_name LIKE concat('%','$search','%') OR code LIKE concat('%','$search','%'))");
            }else if($source == "subcategory"){
                $stmt = $conn->prepare("SELECT * FROM category WHERE parent_id IS NOT NULL AND status<>'inactive' AND (category_name LIKE concat('%','$search','%') OR code LIKE concat('%','$search','%'))");
            }else{
                $stmt=$conn->prepare("SELECT product.id, product.prod_name, category.category_name, product.price, product.product_image 
                            FROM product
                            INNER JOIN category ON category.code = product.code
                            WHERE product.status<>'inactive' 
                            AND (product.id LIKE concat('%','$search','%') 
                            OR product.prod_name LIKE concat('%','$search','%') 
                            OR product.price LIKE concat('%','$search','%') 
                            OR category.category_name LIKE concat('%','$search','%'))");
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $total_subcategory = mysqli_num_rows($result);
            $this->total_subcategory = $total_subcategory;
            if($total_subcategory != 0){
                $total_pages = ceil($total_subcategory / $showRows);
                $start = $showRows*($page-1);

                if($source == "category"){
                    if($search!=''){
                        $stmt = $conn->prepare("SELECT * FROM category WHERE parent_id IS NULL 
                        AND status<>'inactive' AND (category_name LIKE concat('%','$search','%') 
                        OR code LIKE concat('%','$search','%'))");
                    }else{
                        $stmt = $conn->prepare("SELECT * FROM category WHERE parent_id IS NULL AND status<>'inactive' LIMIT $start,$showRows");
                    }
                }else if($source == "subcategory"){
                    if($search!=''){
                        $stmt = $conn->prepare("SELECT * FROM category WHERE parent_id IS NOT NULL 
                        AND status<>'inactive' AND (category_name LIKE concat('%','$search','%') 
                        OR code LIKE concat('%','$search','%'))");
                    }else{
                        $stmt = $conn->prepare("SELECT * FROM category WHERE parent_id IS NOT NULL AND status<>'inactive' LIMIT $start,$showRows");
                    }
                }else{
                    if($search!=''){
                        $stmt = $conn->prepare("SELECT product.id, product.prod_name, category.category_name, product.price, product.product_image 
                        FROM product
                        INNER JOIN category ON category.code = product.code
                        WHERE product.status<>'inactive' 
                        AND (product.id LIKE concat('%','$search','%') 
                        OR product.prod_name LIKE concat('%','$search','%') 
                        OR product.price LIKE concat('%','$search','%') 
                        OR category.category_name LIKE concat('%','$search','%'))");
                    }else{
                        $stmt = $conn->prepare("SELECT product.id, product.prod_name, category.category_name, product.price, product.product_image 
                        FROM product
                        INNER JOIN category ON category.code = product.code
                        WHERE product.status<>'inactive' 
                        LIMIT $start,$showRows");
                    }
                }
                $stmt->execute();
                $result = $stmt->get_result();
                $total_subcategory = mysqli_num_rows($result);
                $j=1;
                $num=$showRows*($page-1)+1;
            }
        }

        public function showCategory(){
            global $result, $j, $num, $stmt;
            $output="<form id='form' method='POST' enctype='multipart/form-data'><table class='table text-center' id='category'>
                        <thead class='thead-dark'>
                            <tr>
                                <th>#</th>
                                <th>Category Name</th>
                                <th>Code</th>
                                <th>Action</th>
                            </tr>
                        </thead><tbody>";
                            while($row = $result->fetch_assoc()){
                                $output.="<tr>
                                <th>$num</th>
                                <td id='category_name".$j."' class='cate_editRows".$j."'>".$row['category_name']."</td>
                                <td id='category_code".$j."' class='cate_editRows".$j."'>".$row['code']."</td>
                                <td><button type='button' class='btn_' style='margin-right:5px' id='edit_categ".$j."' onclick=editRecord($j,'category')><i class='fa fa-pencil btn_j$j'></i></button>
                                <button type='button' class='btn_' id='delete_categ".$j."' onclick=deleteRecord($j,'category')><i class='fa fa-trash btn_i$j'></i></button></td></tr>";
                                $j++;
                                $num++;
                            }
            $output.="</tbody></table></form>";
            echo $output;
        }

        public function showSubCategory(){
            global $result, $j, $num, $conn;
            $output="<form id='form' method='POST' enctype='multipart/form-data'><table class='table text-center' id='subcategory'>
                <thead class='thead-dark'>
                    <tr>
                        <th>#</th>
                        <th>Sub-Category Name</th>
                        <th>Code</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                </thead><tbody>";
                while($row = $result->fetch_assoc()){
                    $stmt = $conn->prepare("SELECT category_name FROM category WHERE id=?");
                    $stmt->bind_param("s", $row['parent_id']);
                    $stmt->execute();
                    $result1 = $stmt->get_result();
                    $row1 = $result1->fetch_assoc();

                    $output.="<tr>
                    <th>$num</th>
                    <td id='subcategory_name".$j."' class='subcate_editRows".$j."'>".$row['category_name']."</td>
                    <td id='subcategory_code".$j."' class='subcate_editRows".$j."'>".$row['code']."</td>
                    <td id='subcategory_categ".$j."'><p class='caddTag".$j."'>".$row1['category_name']."</p></td>
                    <td><button type='button' class='btn_' style='margin-right:5px' id='edit_subcateg".$j."' onclick=editRecord($j,'subcategory')><i class='fa fa-pencil btn_j$j'></i></button>
                    <button type='button' class='btn_' id='delete_subcateg".$j."' onclick=deleteRecord($j,'subcategory')><i class='fa fa-trash btn_i$j'></i></button></td></tr>";
                    $j++;
                    $num++;
                }
                $output.="</tbody></table></form>";

            echo $output;
        }

        public function showProduct(){
            global $result, $j, $num, $conn;
            $output="<form id='form' method='POST' enctype='multipart/form-data'><table class='table text-center' id='product'>
                <thead class='thead-dark'>
                    <tr>
                        <th>#</th>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Sub-Category</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead><tbody>";
                
                while($row=$result->fetch_assoc()){
                    $output.="<tr>
                    <th>$num</th>
                    <td>".$row['id']."</td>
                    <td id='product_name".$j."' class='prod_editRows".$j."'>".$row['prod_name']."</td>
                    <td id='product_categ".$j."'><p class='paddTag".$j."'>".$row['category_name']."</p></td>
                    <td id='product_price".$j."' class='prod_editRows".$j."'>".$row['price']."</td>
                    <td><img id='product_image".$j."' src='".$row['product_image']."' alt='".$row["prod_name"]."' width='100' height='100' /></td>
                    <td style='min-width: 104px;'><button type='button' class='btn_' style='margin-right:5px' id='edit_prod".$j."' onclick=editRecord($j,'product')><i class='fa fa-pencil btn_j$j'></i></button>
                    <button type='button' class='btn_' id='delete_prod".$j."' onclick=deleteRecord($j,'product')><i class='fa fa-trash btn_i$j'></i></button></td></tr>";
                    $j++;
                    $num++;
                }
            $output.="</tbody></table></form>";
            echo $output;
        }

        public function pagination_number(){
            global $page, $total_pages;
            $output="<nav aria-label='Page navigation example'><ul class='pagination justify-content-center'>";
            $output.="<li class='page-item firstPrev'><a class='page-link' id='first' href='#' onclick='changePage(1)'>First</a></li>";
            $prev = $page-1;
            $output.="<li class='page-item firstPrev'><a class='page-link' id='prev' href='#' onclick=changePage(".$prev.")>Previous</a></li>";
            if($page==1){
                echo '<script>$(".firstPrev").addClass("disabled");</script>';
            }

            $page1 = $page2 = $page;
            for($i=$page1-1;$i<=$page1+1;$i++){
                if($page2 == $total_pages){
                    $i = $page - 2;
                    $page2 = -1;
                    if($total_pages <= 1){
                        $i = $page-1;
                    }
                }

                if($i<=$total_pages)
                {
                    if($i!=0){
                        $output.="<li class='page-item'><a class='page-link' id=".$i." href='#' onclick=changePage($i)>$i</a></li>";
                    }else{
                        $page1 = 2;
                    }
                }
            }
            echo '<script>$("#"+'.$page.').addClass("selected_box");</script>';
            
            $next = $page+1;
            $output.="<li class='page-item nextLast'><a class='page-link' id='next' href='#' onclick=changePage(".$next.")>Next</a></li>";
            $output.="<li class='page-item nextLast'><a class='page-link' id='last' href='#' onclick=changePage(".$total_pages.")>Last</a></li>";
            if($page==$total_pages){
                echo '<script>$(".nextLast").addClass("disabled");</script>';
            }
            echo $output;
        }
    }
?>