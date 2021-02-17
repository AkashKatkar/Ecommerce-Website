function redirectAddItems($whichWork){
    if($whichWork == "addCategory"){
        location.href="http://localhost/Ecommerce%20Website/addItems.php";
        localStorage.setItem("whichWork", "Add Category");
    }else if($whichWork == "addSubCategory"){
        location.href="http://localhost/Ecommerce%20Website/addItems.php";
        localStorage.setItem("whichWork", "Add Sub-Category");
    }else{
        location.href="http://localhost/Ecommerce%20Website/addItems.php";
        localStorage.setItem("whichWork", "Add Product");
    }
}

function deleteRecord(position, source){
    var code;
    if(source != "product"){
        code=document.getElementById(source).rows[position].cells.item(1).innerHTML;
    }else{
        code=position;
    }
    $.ajax({
        url:"index.php",
        method:"POST",
        dataType: "json",
        data: {"func": "deleteCategory", "code": code, "source": source},
        success:function(resp){
            if(resp["ack"] == "yes"){
                location.reload();
                alert("Successfully Record Deleted");
            }else{
                alert("Something Went Wrong");
            }
        }
    });
}

var previous_name;
var previous_code
var previous_cname;
var editData;
var deleteData;
var editRows;

function getCategory(position, source){
    $.ajax({
        url:"addItems.php",
        method:"POST",
        dataType:"json",
        data:{"func": source},
        success:function(resp){
            for($i=0;$i<resp["allCategory"].length;$i++){
                if(previous_cname == resp["allCategory"][$i]){
                    $("<option selected></option>").text(resp["allCategory"][$i]).attr("value", resp["allCategory"][$i]).appendTo("."+source+position);
                }else{
                    $("<option></option>").text(resp["allCategory"][$i]).attr("value", resp["allCategory"][$i]).appendTo("."+source+position);
                }
            }
        }
    });
}

function editRecord(position, source){
    var oldCode=document.getElementById(source).rows[position].cells.item(1).innerHTML;
    if(source == "category"){
        editData = "#edit_categ";
        deleteData = "#delete_categ";
        editRows = ".cate_editRows";
        previous_name = $("#category_name"+position).text();
        previous_code = $("#category_code"+position).text();
    }else if(source == "subcategory"){
        editData = "#edit_subcateg";
        deleteData = "#delete_subcateg";
        editRows = ".subcate_editRows";
        previous_name = $("#subcategory_name"+position).text();
        previous_code = $("#subcategory_code"+position).text();
        previous_cname = $("#subcategory_categ"+position).text();
        $(".caddTag"+position).remove();
        $("<select class='getCategory"+position+"'></select>").appendTo("#subcategory_categ"+position);
        getCategory(position, "getCategory");
    }else{
        editData = "#edit_prod";
        deleteData = "#delete_prod";
        editRows = ".prod_editRows";
        oldCode=document.getElementById("product").rows[position].cells.item(0).innerHTML;
        previous_name = $("#product_name"+position).text();
        previous_code = $("#product_price"+position).text();
        previous_cname = $("#product_categ"+position).text();
        $(".paddTag"+position).remove();
        $("<select class='getSubCategory"+position+"'></select>").appendTo("#product_categ"+position);
        getCategory(position, "getSubCategory");
    }
    $(editRows+position).attr("contenteditable", "true");
    $(editData+position).attr("value", "Conform").attr("onclick", "editCancel('edit',"+position+",'"+source+"','"+oldCode+"')");
    $(deleteData+position).attr("value", "Cancel").attr("onclick", "editCancel('cancel',"+position+",'"+source+"','"+oldCode+"')");
}

function editCancel(action, position, source, oldCode){
    if(action == "cancel"){
        if(source=="category"){
            $("#category_name"+position).text(previous_name);
            $("#category_code"+position).text(previous_code);
        }else if(source=="subcategory"){
            $("#subcategory_name"+position).text(previous_name);
            $("#subcategory_code"+position).text(previous_code);
            $(".getCategory"+position).remove();
            $("<p class='caddTag"+position+"'>"+previous_cname+"</p>").appendTo("#subcategory_categ"+position);
        }else{
            $("#product_name"+position).text(previous_name);
            $("#product_price"+position).text(previous_code);
            $(".getSubCategory"+position).remove();
            $("<p class='paddTag"+position+"'>"+previous_cname+"</p>").appendTo("#product_categ"+position);
        }
    }else{
        var sourceData;
        var newCategory = $(".getCategory"+position).val();
        var newSubCategory = $(".getSubCategory"+position).val();
        if(source=="category"){
            sourceData = {"func":"editCancel", "source":source, "oldCode":oldCode, "name":$("#category_name"+position).text(), "code":$("#category_code"+position).text()};
        }else if(source=="subcategory"){
            sourceData = {"func":"editCancel", "source":source, "oldCode":oldCode, "name":$("#subcategory_name"+position).text(), "code":$("#subcategory_code"+position).text(),
                            "category":newCategory};
        }else{
            sourceData = {"func":"editCancel", "source":source, "oldCode":oldCode, "name":$("#product_name"+position).text(),
                            "category":newSubCategory, "price":$("#product_price"+position).text()};
        }
        $.ajax({
            url:"index.php",
            method:"POST",
            dataType:"json",
            data:sourceData,
            success:function(resp){
                if(resp["ack"] == "yes"){
                    alert("Data Updated Successfully");
                    if(source=="subcategory"){
                        $(".getCategory"+position).remove();
                        $("<p class='caddTag"+position+"'>"+newCategory+"</p>").appendTo("#subcategory_categ"+position);
                    }else if(source=="product"){
                        $(".getSubCategory"+position).remove();
                        $("<p class='paddTag"+position+"'>"+newSubCategory+"</p>").appendTo("#product_categ"+position);
                    }
                }else{
                    alert("Something Went Wrong");
                }
            }
        });
    }
    $(editData+position).attr("value", "EDIT").attr("onclick", "editRecord("+position+",'"+source+"')");
    $(deleteData+position).attr("value", "DELETE").attr("onclick", "deleteRecord("+position+",'"+source+"')");
    $(editRows+position).removeAttr("contenteditable");
}

var page;
var row;
function changeCategoryPage(page){
    this.page = page;
    load_pagination();
}

function load_pagination()
{
    $.ajax({
        url:"index_pagination.php",
        method:"post",
        data:{"page":page, "showRows":$(".showRows").val()},
        success:function(resp){
            $("#index_tables_onload").html(resp);
            setTimeout(function(){
                $(".showRows"+row).attr("selected", "true");
            },50);
        }
    });
}

function showRows(page){
    this.page = page;
    row = $(".showRows").val();
    load_pagination();
    setTimeout(function(){
        $(".showRows"+row).attr("selected", "true");
    },50);
}
load_pagination();