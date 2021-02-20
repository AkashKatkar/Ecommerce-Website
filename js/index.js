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

var previous_name;
var previous_code
var previous_cname;
var editData;
var deleteData;
var editRows;
var navSource = "category";
var selectRows = 5;
var page=1;

function deleteRecord(position, source){
    var code;
    if(source != "product"){
        code=document.getElementById(source).rows[position].cells.item(2).innerHTML;
    }else{
        code=document.getElementById(source).rows[position].cells.item(1).innerHTML;;
    }
    $.ajax({
        url:"index_ajax.php",
        method:"POST",
        dataType: "json",
        data: {"func": "deleteItem", "code": code, "source": source},
        success:function(resp){
            if(resp["ack"] == "yes"){
                load_pagination(navSource)
                alert("Successfully Record Deleted");
            }else{
                alert("Something Went Wrong");
            }
        }
    });
}

function getCategory(position, source){
    $.ajax({
        url:"addItems_ajax.php",
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

var product_image;
function editRecord(position, source){
    var oldCode=document.getElementById(source).rows[position].cells.item(2).innerHTML;
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
        $("<select class='form-control getCategory"+position+"' style='border:1px solid rgba(0,0,0,0.5);'></select>").appendTo("#subcategory_categ"+position);
        getCategory(position, "getCategory");
    }else{
        editData = "#edit_prod";
        deleteData = "#delete_prod";
        editRows = ".prod_editRows";
        oldCode=document.getElementById("product").rows[position].cells.item(1).innerHTML;
        previous_name = $("#product_name"+position).text();
        previous_code = $("#product_price"+position).text();
        previous_cname = $("#product_categ"+position).text();
        product_image= $("#product_image"+position).attr("src");
        $('#product_image'+position).replaceWith(function(){
            return $('<div class="file-upload-wrapper fuw'+position+'" data-text="Select your file!"><input type="file" onchange="changeImage('+position+')" name="image_file'+position+'" class="file-upload-field" id="product_image'+position+'"></div>');
            // return $("<input type='file' name='image_file"+position+"' id='product_image"+position+"'>");
        });
        $(".paddTag"+position).remove();
        $("<select class='form-control getSubCategory"+position+"' style='border:1px solid rgba(0,0,0,0.5);'></select>").appendTo("#product_categ"+position);
        getCategory(position, "getSubCategory");
    }
    $(editRows+position).attr("contenteditable", "true");
    $(editData+position).attr("onclick", "editCancel('edit',"+position+",'"+source+"','"+oldCode+"')");
    $(".btn_j"+position).attr("class", "fa fa-check btn_j"+position);
    $(".btn_i"+position).attr("class", "fa fa-close btn_i"+position);
    $(deleteData+position).attr("onclick", "editCancel('cancel',"+position+",'"+source+"','"+oldCode+"')");
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
            $('.fuw'+position).replaceWith(function(){
                return $("<img id='product_image"+position+"' src='"+product_image+"' alt='"+$(".paddTag"+position).text()+"' width='100' height='100' />");
            });
        }
    }else{
        $(editData+position).attr("type", "submit");
        $("#form").one("submit" ,function(ev) {
            ev.preventDefault();
            var formData = new FormData(this);
            formData.append("func", "editCancel");
            formData.append("source", source);
            formData.append("oldCode", oldCode);
            var sourceData;
            var newCategory = $(".getCategory"+position).val();
            var newSubCategory = $(".getSubCategory"+position).val();
            if(source=="category"){
                formData.append("name", $("#category_name"+position).text());
                formData.append("code", $("#category_code"+position).text());
            }else if(source=="subcategory"){
                formData.append("name", $("#subcategory_name"+position).text());
                formData.append("code", $("#subcategory_code"+position).text());
                formData.append("category", newCategory);
            }else{
                if($("#product_image"+position).val() != ""){
                    product_image= ($("#product_image"+position).val()).replace("C:\\fakepath\\", "images/");
                }
                formData.append("name", $("#product_name"+position).text());
                formData.append("category", newSubCategory);
                formData.append("position", position);
                formData.append("price", $("#product_price"+position).text());
                formData.append("image", product_image);
            }

            $.ajax({
                url:"index_ajax.php",
                method:"POST",
                dataType:"json",
                cache: false,
                contentType: false,
                processData: false,
                data:formData,
                success:function(resp){
                    if(resp["ack"] == "yes"){
                        alert("Data Updated Successfully");
                        if(source=="subcategory"){
                            $(".getCategory"+position).remove();
                            $("<p class='caddTag"+position+"'>"+newCategory+"</p>").appendTo("#subcategory_categ"+position);
                        }else if(source=="product"){
                            $(".getSubCategory"+position).remove();
                            $("<p class='paddTag"+position+"'>"+newSubCategory+"</p>").appendTo("#product_categ"+position);
                            $('#product_image'+position).replaceWith(function(){
                                return $("<img id='product_image"+position+"' src='"+product_image+"' alt='"+$(".paddTag"+position).text()+"' width='100' height='100' />");
                            });
                        }
                        $(editData+position).attr("type", "button");
                    }else{
                        alert("Something Went Wrong");
                    }
                }
            });
        });
    }
    $(editData+position).attr("onclick", "editRecord("+position+",'"+source+"')");
    $(".btn_j"+position).attr("class", "fa fa-pencil btn_j"+position);
    $(".btn_i"+position).attr("class", "fa fa-trash btn_i"+position);
    $(deleteData+position).attr("onclick", "deleteRecord("+position+",'"+source+"')");
    $(editRows+position).removeAttr("contenteditable");
}

function changePage(page){
    this.page = page;
    load_pagination(navSource);
}

function load_pagination(source)
{
    $.ajax({
        url:"index_pagination.php",
        method:"post",
        data:{"page":page, "showRows":selectRows, "source": source},
        success:function(resp){
            $("#index_tables_onload").html(resp);
        }
    });
}

function which_nav(navSource){
    this.page = 1;
    $(".inactive_nav").removeClass("active");
    load_pagination(navSource);
    if(navSource == "category"){
        $(".nav1").addClass("active");
        $(".card-header").text("Category");
        $(".addData button").attr("onclick", "redirectAddItems('addCategory')").text("Add Category");
    }else if(navSource == "subcategory"){
        $(".nav2").addClass("active");
        $(".card-header").text("Sub-Category");
        $(".addData button").attr("onclick", "redirectAddItems('addSubCategory')").text("Add SubCategory");
    }else{
        $(".nav3").addClass("active");
        $(".card-header").text("Product");
        $(".addData button").attr("onclick", "redirectAddItems('addProduct')").text("Add Product");
    }
    this.navSource = navSource;
}

$(document).ready(function(){
    $(".dropdown-item").on("click", function(event){
        page = 1;
        selectRows = $(this).text();
        load_pagination(navSource);
        $(".dropdown .dropdown-toggle").text(selectRows);
    });
});

load_pagination('category');

function changeImage(position){
    $("#product_image"+position).parent(".file-upload-wrapper").attr("data-text", $("#product_image"+position).val().replace(/.*(\/|\\)/, '').substring(0, 15)+"...");
};