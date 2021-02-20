$(document).ready(function(){
    $(".outer_picture").on("change", ".file-upload-field", function(){ 
        $(this).parent(".file-upload-wrapper").attr("data-text", $(this).val().replace(/.*(\/|\\)/, '').substring(0, 30)+"...");
    });

    var setValues="";
    $(".heading").text(localStorage.getItem("whichWork"));
    if(localStorage.getItem("whichWork") == "Add Category"){
        $(".outer_subcategory").remove();
        $(".outer_price").remove();
        $(".outer_picture").remove();
        $(".hidden_tag").remove();
    }else if(localStorage.getItem("whichWork") == "Add Sub-Category"){
        $(".addItemBtn").attr("onclick", "addSubCategory()");
        setValues = "getCategory";
        $(".outer_price").remove();
        $(".outer_picture").remove();
        $(".hidden_tag").remove();
    }else{
        $(".outer_category_code").remove();
        $(".addItemBtn").attr("onclick", "addProduct()").attr("type", "submit");
        $(".subcategory_name").text("Select Sub-Category");
        setValues = "getSubCategory";
    }

    if(setValues != ""){
        $.ajax({
            url:"addItems_ajax.php",
            method:"POST",
            dataType:"json",
            data:{"func": setValues},
            success:function(resp){
                for($i=0;$i<resp["allCategory"].length;$i++){
                    $(".subcategory_name").after($("<option></option>").text(resp["allCategory"][$i]).attr("value", resp["allCategory"][$i]));
                }
            }
        });
    }
});

function addCategory()
{
    $.ajax({
        url:"addItems_ajax.php",
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
        url:"addItems_ajax.php",
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
    $("#add_Category").submit(function(e){
        e.preventDefault();
        $.ajax({
            url:"addItems_ajax.php",
            method:"POST",
            dataType:"json",
            contentType: false,
            processData: false,
            data: new FormData(this),
            success:function(resp){
                if(resp["ack"] == "yes"){
                    location.href = "http://localhost/Ecommerce%20Website/index.php";
                }else{
                    alert("Something Went Wrong");
                }
            }
        });
    });
}
