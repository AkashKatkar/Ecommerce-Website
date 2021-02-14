$(document).ready(function(){
    $(".heading").text(localStorage.getItem("whichWork"));
    if(localStorage.getItem("whichWork") == "Add Category"){
        $(".name").text("Category Name");
        $(".outer_subcategory").remove();
    }else if(localStorage.getItem("whichWork") == "Add Sub-Category"){
        $(".name").text("Sub-Category Name");
        $(".addItemBtn").attr("onclick", "addSubCategory()");
        $setValues = "getCategory";
    }else{
        $(".name").text("Product Name");
        $(".outer_category_code").remove();
        $(".addItemBtn").attr("onclick", "addProduct()");
        $(".subcategory_name").text("Select Sub-Category");
        $setValues = "getProduct";
    }

    $.ajax({
        url:"addItems.php",
        method:"POST",
        dataType:"json",
        data:{"func": $setValues},
        success:function(resp){
            for($i=0;$i<=resp["allCategory"].length-1;$i++){
                $(".subcategory_name").after($("<option></option>").text(resp["allCategory"][$i]).attr("value", resp["allCategory"][$i]));
            }
        }
    });
});

function addCategory()
{
    $.ajax({
        url:"addItems.php",
        method:"POST",
        data:{"func": "", "category_name":$(".category_name").val(), "category_code":$(".category_code").val()},
        dataType:"json",
        success:function(resp){
            if(resp["ack"] == "yes"){
                location.href = "http://localhost/Ecommerce%20Website/index.php";
            }else{
                alert("Something Went Wrong");
            }
        }
    });  
}

function addSubCategory(){
    $.ajax({
        url:"addItems.php",
        method:"POST",
        dataType: "json",
        data:{"func": "addSubCategory", "category_name":$(".category_name").val(), 
                "category_code":$(".category_code").val(), "select_category": $(".main_subcategory_name").val()},
        success:function(resp){
            if(resp["ack"] == "yes"){
                location.href = "http://localhost/Ecommerce%20Website/index.php";
            }else{
                alert("Something Went Wrong");
            }
        }
    });
}

function addProduct(){
    $.ajax({
        url:"addItems.php",
        method:"POST",
        dataType: "json",
        data:{"func": "addProduct", "product_name":$(".category_name").val(), "product_code":$(".main_subcategory_name").val(), 
                        "product_price": $(".product_price").val(), "product_picture": "image/"+$(".product_picture").val().replace("C:\\fakepath\\",'')},
        success:function(resp){
            if(resp["ack"] == "yes"){
                location.href = "http://localhost/Ecommerce%20Website/index.php";
            }else{
                alert("Something Went Wrong");
            }
        }
    });
}