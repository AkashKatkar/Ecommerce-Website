var source;
var itemValue;
$(document).ready(function(){
    $(".outer_picture").on("change", ".file-upload-field", function(){ 
        $(this).parent(".file-upload-wrapper").attr("data-text", $(this).val().replace(/.*(\/|\\)/, '').substring(0, 30));
    });

    $(".restoreData").on("change", function(){
        itemValue = $(this).val();
        $(".showModal").click();
    });

    $(".close").click(function(){
        $(".selectOneItem").attr("selected", "true");
        $(".selectOneItem").removeAttr("selected");
    });

    var setValues="";
    $(".heading").text(localStorage.getItem("whichWork"));
    if(localStorage.getItem("whichWork") == "Add Category"){
        $(".outer_subcategory").remove();
        $(".outer_price").remove();
        $(".outer_picture").remove();
        $(".hidden_tag").remove();
        source="category";
    }else if(localStorage.getItem("whichWork") == "Add Sub-Category"){
        $(".addItemBtn").attr("onclick", "addSubCategory()");
        setValues = "getCategory";
        $(".outer_price").remove();
        $(".outer_picture").remove();
        $(".hidden_tag").remove();
        $(".restoreDataBtn").attr("onclick", "getRestoreDataBtn('subcategory')");
        source="subcategory";
    }else{
        $(".outer_category_code").remove();
        $(".addItemBtn").attr("onclick", "addProduct()").attr("type", "submit");
        $(".subcategory_name").text("Select Sub-Category");
        setValues = "getSubCategory";
        $(".restoreDataBtn").attr("onclick", "getRestoreDataBtn('product')");
        source="product";
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
    getRestoreData(source);
});

var getData;
function addCategory()
{
    $.ajax({
        url:"addItems_ajax.php",
        method:"POST",
        data:{"func": "addCategory", "category_name":$(".category_name").val(), "category_code":$(".category_code").val(), "token": $("#token").val()},
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
        data:{"func": "addSubCategory", "category_name":$(".category_name").val(), "token": $("#token").val(),
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
    $("#add_Category").one("submit" ,function(e){
        formData = new FormData(this);
        formData.append("func", "addProduct");
        formData.append("token", $("#token").val());
        e.preventDefault();
        $.ajax({
            url:"addItems_ajax.php",
            method:"POST",
            dataType:"json",
            contentType: false,
            processData: false,
            data: formData,
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

function getRestoreData(source){
    this.source = source;
    $.ajax({
        url:"addItems_ajax.php",
        method:"POST",
        dataType:"json",
        data: {"func": "getRestore_data", "source": source},
        success:function(resp){
            if(resp["getData"].length == 0){
                $(".main_restoreDataBtn").remove();
            }else if(getData == "ok"){
                $(".main_restoreDataBtn").remove();
                $(".main_restoreData").show();
                for(var i=0; i<resp["getData"].length; i++){
                    $(".selectOneItem").after($("<option></option>").text(resp["getData"][i][0]).val(resp["getData"][i][1]));
                }
            }
        }
    });
}

function getRestoreDataBtn(){
    getData = "ok";
    getRestoreData(source);
}

function restore_btn(){
    $.ajax({
        url:"addItems_ajax.php",
        method:"POST",
        dataType:"json",
        data:{"func": "restoreData", "source": source, "code":$(".restoreData").val(), "operation": "restore_data"},
        success:function(resp){
            $(".restoreData option[value='"+itemValue+"']").remove();
            if ($(".restoreData").children("option").length == 1) {
                $(".main_restoreData").remove();
            }
            $(".close").click();
            alert("Successfully Restore Data");
        }
    });
}

function Permanent_delete_btn(){
    $.ajax({
        url:"addItems_ajax.php",
        method:"POST",
        dataType:"json",
        data:{"func": "permanentDeleteData", "source": source, "code":$(".restoreData").val(), "operation": "permanent_delete_data"},
        success:function(resp){
            if(resp["ack"] == "yes"){
            $(".restoreData option[value='"+itemValue+"']").remove();
            if ($(".restoreData").children("option").length == 1) {
                $(".main_restoreData").remove();
            }
            $(".close").click();
                alert("Successfully deleted data permanently.");
            }else{
                alert("Something Went Wrong");
            }
        }
    });
}