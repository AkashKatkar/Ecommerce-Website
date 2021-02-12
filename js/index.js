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