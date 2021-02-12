$(document).ready(function(){
    $(".heading").text(localStorage.getItem("whichWork"));
    if(localStorage.getItem("whichWork") == "Add Category"){
        $(".name").text("Category Name");
    }
});