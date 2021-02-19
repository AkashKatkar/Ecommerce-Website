<?php
    date_default_timezone_set("Asia/Calcutta");
    ini_set("display_errors", 1);
    ini_set("log_errors", 1);
    ini_set("error_log",'error_log.txt');

    $conn = new mysqli("localhost","root","", "ecommerce_website");
    if ($conn->error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if(isset($_POST["page"])){
        $page = $_POST["page"];
        $showRows = $_POST["showRows"];
    }else{
        $page = 1;
        $showRows = 1;
    }

    function category(){
        global $page;
        global $showRows;
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM category WHERE parent_id IS NULL");
        $stmt->execute();
        $result = $stmt->get_result();
        $total_subcategory = mysqli_num_rows($result);
        $total_subcategory_pages = ceil($total_subcategory / $showRows);
        $start = $showRows*($page-1);

        $stmt = $conn->prepare("SELECT * FROM category WHERE parent_id IS NULL LIMIT $start,$showRows");
        $stmt->execute();
        $result = $stmt->get_result();
        $total_subcategory = mysqli_num_rows($result);
        $j=1;
        $source = '"category"';
        $output="<table class='table text-center' id='category'>
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
                            <th>$j</th>
                            <td id='category_name".$j."' class='cate_editRows".$j."'>".$row['category_name']."</td>
                            <td id='category_code".$j."' class='cate_editRows".$j."'>".$row['code']."</td>
                            <td><input type='button' value='EDIT' style='margin-right:5px' id='edit_categ".$j."' onclick='editRecord($j, $source)'>
                            <input type='button' value='DELETE' id='delete_categ".$j."' onclick='deleteRecord($j, $source)'></td></tr>";
                            $j++;
                        }
        $output.="</tbody></table><br/>";
        echo $output;
        $stmt->close();
    }

    function subCategory(){
        global $page;
        global $showRows;
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM category WHERE parent_id IS NOT NULL");
        $stmt->execute();
        $result = $stmt->get_result();
        $total_subcategory = mysqli_num_rows($result);
        $total_subcategory_pages = ceil($total_subcategory / $showRows);
        $start = $showRows*($page-1);

        $stmt = $conn->prepare("SELECT * FROM category WHERE parent_id IS NOT NULL LIMIT $start,$showRows");
        $stmt->execute();
        $result = $stmt->get_result();
        $total_subcategory = mysqli_num_rows($result);
        $j=1;
        $source = '"subcategory"';
        $output="<table class='table text-center' id='subcategory'>
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
                <th>$j</th>
                <td id='subcategory_name".$j."' class='subcate_editRows".$j."'>".$row['category_name']."</td>
                <td id='subcategory_code".$j."' class='subcate_editRows".$j."'>".$row['code']."</td>
                <td id='subcategory_categ".$j."'><p class='caddTag".$j."'>".$row1['category_name']."</p></td>
                <td><input type='button' value='EDIT' style='margin-right:5px' id='edit_subcateg".$j."' onclick='editRecord($j, $source)'>
                <input type='button' value='DELETE' id='delete_subcateg".$j."' onclick='deleteRecord($j, $source)'></td></tr>";
                $j++;
            }
            $output.="</tbody></table>";

        echo $output;
        $stmt->close();
    }

    function product(){
        global $page;
        global $showRows;
        global $conn;
        $stmt=$conn->prepare("SELECT * FROM product");
        $stmt->execute();
        $result=$stmt->get_result();
        $total_subcategory = mysqli_num_rows($result);
        $total_subcategory_pages = ceil($total_subcategory / $showRows);
        $start = $showRows*($page-1);

        $stmt = $conn->prepare("SELECT * FROM product LIMIT $start,$showRows");
        $stmt->execute();
        $result = $stmt->get_result();
        $total_subcategory = mysqli_num_rows($result);
        $j=1;
        $source = '"product"';
        $output="<form id='product_form' method='POST' enctype='multipart/form-data'><table class='table text-center' id='product'>
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
                $stmt = $conn->prepare("SELECT category_name FROM category WHERE code=? order by id");
                $stmt->bind_param("s",$row["code"]);
                $stmt->execute();
                $result1 = $stmt->get_result();
                $row1 = $result1->fetch_assoc();
                $output.="<tr>
                <th>$j</th>
                <td>".$row['id']."</td>
                <td id='product_name".$j."' class='prod_editRows".$j."'>".$row['prod_name']."</td>
                <td id='product_categ".$j."'><p class='paddTag".$j."'>".$row1['category_name']."</p></td>
                <td id='product_price".$j."' class='prod_editRows".$j."'>".$row['price']."</td>
                <td><img id='product_image".$j."' src='".$row['product_image']."' alt='".$row["code"]."' width='100' height='100' /></td>
                <td><input type='button' value='EDIT' style='margin-right:5px' id='edit_prod".$j."' onclick='editRecord($j, $source)'>
                <input type='button' value='DELETE' id='delete_prod".$j."' onclick='deleteRecord($j, $source)'></td></tr>";
                $j++;
                $stmt->close();
            }
        $output.="</tbody></table></form>";
        echo $output;
    }

    if($_POST["source"] == "category"){
        category();
    }else if($_POST["source"] == "subcategory"){
        subCategory();
    }else{
        product();
    }

    $output="<nav aria-label='Page navigation example'><ul class='pagination justify-content-center'>";
    $output.="<li class='page-item firstPrev'><a class='page-link' id='first' href='#' onclick='changePage(1)'>First</a></li>";
    $prev = $page-1;
    $output.="<li class='page-item firstPrev'><a class='page-link' id='prev' href='#' onclick=changePage(".$prev.")>Previous</a></li>";
    if($page==1){
        echo '<script>$(".firstPrev").addClass("disabled");</script>';
    }

    global $total_subcategory_pages;
    $page1 = $page2 = $page;
    for($i=$page1-1;$i<=$page1+1;$i++){
        if($page2 == $total_subcategory_pages){
            $i = $page - 2;
            $page2 = -1;
            if($total_subcategory_pages <= 1){
                $i = $page-1;
            }
        }

        if($i<=$total_subcategory_pages)
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
    $output.="<li class='page-item nextLast'><a class='page-link' id='last' href='#' onclick=changePage(".$total_subcategory_pages.")>Last</a></li>";
    if($page==$total_subcategory_pages){
        echo '<script>$(".nextLast").addClass("disabled");</script>';
    }
    echo $output;
    $conn->close();
?>