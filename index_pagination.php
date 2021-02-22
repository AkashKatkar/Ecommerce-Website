<?php
    require ("functions.php");
    if(!empty(extract($_POST))){
        if(isset($_POST["page"])){
            $page = $_POST["page"];
            $showRows = $_POST["showRows"];
        }else{
            $page = 1;
            $showRows = 1;
        }

        $pagination = new Pagination();
        if($_POST["source"] == "category"){
            $pagination->myQuery("category");
            if($total_subcategory != 0){
                $pagination->showCategory();
            }
        }else if($_POST["source"] == "subcategory"){
            $pagination->myQuery("subcategory");
            global $total_subcategory;
            if($total_subcategory != 0){
                $pagination->showSubCategory();
            }
        }else{
            $pagination->myQuery("product");
            global $total_subcategory;
            if($total_subcategory != 0){
                $pagination->showProduct();
            }
        }
        if($total_subcategory != 0){
            $pagination->pagination_number();
        }else{
            // echo "<script>$('.card-body .dropdown .dropdown').remove();</script>";
            echo "<p style='text-align:center;font-size:50px;font-weight:bold;font-family:Baloo;color:#ffbf33;'>Please Add Data First</p>";
        }
        $stmt->close();
        $conn->close();
    }
?>