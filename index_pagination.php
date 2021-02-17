<?php
    date_default_timezone_set("Asia/Calcutta");
    ini_set("display_errors", 1);
    ini_set("log_errors", 1);
    ini_set("error_log",'error_log.txt');

    $conn = new mysqli("localhost","root","", "ecommerce_website");
    if ($conn->error) {
        die("Connection failed: " . $conn->connect_error);
    }

    function category(){
        global $conn;
        $output="<input type='button' value='Add Category' onclick=redirectAddItems('addCategory')>";
        $stmt = $conn->prepare("SELECT * FROM category WHERE parent_id IS NULL");
        $stmt->execute();
        $result = $stmt->get_result();
        $j=1;
        $source = '"category"';
        $output.="<table border='1' id='category' cellpadding='5px'>
                    <tr>
                        <th>Category Name</th>
                        <th>Code</th>
                        <th>Action</th>
                    </tr>";
                        while($row = $result->fetch_assoc()){
                            $output.="<tr>
                            <td id='category_name".$j."' class='cate_editRows".$j."'>".$row['category_name']."</td>
                            <td id='category_code".$j."' class='cate_editRows".$j."'>".$row['code']."</td>
                            <td><input type='button' value='EDIT' style='margin-right:5px' id='edit_categ".$j."' onclick='editRecord($j, $source)'>
                            <input type='button' value='DELETE' id='delete_categ".$j."' onclick='deleteRecord($j, $source)'></td></tr>";
                            $j++;
                        }
        $output.="<tr><td colspan='3'></td></tr></table><br/>";
        echo $output;
        $stmt->close();
    }

    function subCategory(){
        if(isset($_POST["page"])){
            $page = $_POST["page"];
            $showRows = $_POST["showRows"];
        }else{
            $page = 1;
            $showRows = 1;
        }
        $output = "<select class='showRows' onchange='showRows($page)'>
            <option class='showRows1' value='1'>1</option>
            <option class='showRows3' value='3'>3</option>
            <option class='showRows5' value='5'>5</option>
        </select>";

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
        $output.="<input type='button' value='Add Sub-Category' onclick=redirectAddItems('addSubCategory')>";
        $output.="<table border='1' id='subcategory' cellpadding='5px'>
            <tr>
                <th>Sub-Category Name</th>
                <th>Code</th>
                <th>Category</th>
                <th>Action</th>
            </tr>";
            while($row = $result->fetch_assoc()){
                $stmt = $conn->prepare("SELECT category_name FROM category WHERE id=?");
                $stmt->bind_param("s", $row['parent_id']);
                $stmt->execute();
                $result1 = $stmt->get_result();
                $row1 = $result1->fetch_assoc();

                $output.="<tr>
                <td id='subcategory_name".$j."' class='subcate_editRows".$j."'>".$row['category_name']."</td>
                <td id='subcategory_code".$j."' class='subcate_editRows".$j."'>".$row['code']."</td>
                <td id='subcategory_categ".$j."' class='subcate_editRows".$j."'><p class='caddTag".$j."'>".$row1['category_name']."</p></td>
                <td><input type='button' value='EDIT' style='margin-right:5px' id='edit_subcateg".$j."' onclick='editRecord($j, $source)'>
                <input type='button' value='DELETE' id='delete_subcateg".$j."' onclick='deleteRecord($j, $source)'></td></tr>";
                $j++;
            }
            $output.="<tr class='main_box' id=><td colspan='4'>";
            if($page!=1){
                $output.="<p class='box' id='first' onclick=changeCategoryPage(1)>FIRST</p>";
                $prev = $page-1;
                $output.="<p class='box' id='prev' onclick=changeCategoryPage(".$prev.")>PREV</p>";
            }

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
                        $output.="<p class='box' id=".$i." onclick='changeCategoryPage(".$i.")'>$i</p>";
                    }else{
                        $page1 = 2;
                    }
                }
            }
            echo '<script>$("#"+'.$page.').attr("class", "box selected_box");</script>';
            
            if($page!=$total_subcategory_pages){
                $next = $page+1;
                $output.="<p class='box' id='next' onclick=changeCategoryPage(".$next.")>NEST</p>";
                $output.="<p class='box' id='last' onclick=changeCategoryPage(".$total_subcategory_pages.")>LAST</p>";
            }
        $output.="</td></tr></table><br/>";
        echo $output;
        $stmt->close();
    }

    function product(){
        global $conn;
        $stmt=$conn->prepare("SELECT * FROM product");
        $stmt->execute();
        $result=$stmt->get_result();
        $j=1;
        $source = '"product"';
        $output="<input type='button' value='Add Product'  onclick=redirectAddItems('addProduct')>";
        $output.="<table border='1' id='product' cellpadding='5px'>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Sub-Category</th>
                <th>Price</th>
                <th>Image</th>
                <th>Action</th>
            </tr>";
            // $stmt1 = $conn->prepare("SELECT category_name FROM category 
            //                 WHERE id=(SELECT parent_id FROM category WHERE code='".$row["code"]."') 
            //                 order by id");
            while($row=$result->fetch_assoc()){
                $stmt = $conn->prepare("SELECT category_name FROM category WHERE code=? order by id");
                $stmt->bind_param("s",$row["code"]);
                $stmt->execute();
                $result1 = $stmt->get_result();
                $row1 = $result1->fetch_assoc();
                $output.="<tr>
                <td>".$row['id']."</td>
                <td id='product_name".$j."' class='prod_editRows".$j."'>".$row['prod_name']."</td>
                <td id='product_categ".$j."' class='prod_editRows".$j."'><p class='paddTag".$j."'>".$row1['category_name']."</p></td>
                <td id='product_price".$j."' class='prod_editRows".$j."'>".$row['price']."</td>
                <td><img src='".$row['image']."' alt='".$row["code"]."' width='100' height='100' /></td>
                <td><input type='button' value='EDIT' style='margin-right:5px' id='edit_prod".$j."' onclick='editRecord($j, $source)'>
                <input type='button' value='DELETE' id='delete_prod".$j."' onclick='deleteRecord($j, $source)'></td></tr>";
                $j++;
                $stmt->close();
            }
        $output.="<tr><td colspan='6'></td></tr></table><br/>";
        echo $output;
    }

    category();
    subCategory();
    product();
    
    $conn->close();
?>