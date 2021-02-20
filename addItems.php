<html>
    <head>
        <title>Add Items</title>

        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <link href="css/addItems.css" rel="stylesheet">
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="js/addItems.js"></script>
    </head>
    <body>
        <div id="login">
            <div class="container">
                <div id="login-row" class="row justify-content-center align-items-center">
                    <div id="login-column">
                        <div id="login-box" class="col-md-12">
                            <h3 class="text-center text-black heading">Add Category</h3>
                            <form id="add_Category" method="POST" enctype="multipart/form-data">
                                <div class="form-group main_category_name">
                                    <input type="text" name="product_name"  class="form-control my-input category_name" id="name" placeholder="Name">
                                </div>
                                <div class="form-group outer_category_code">
                                    <input type="text"  class="form-control my-input category_code" placeholder="Code">
                                </div>
                                <div class="outer_subcategory">
                                    <select class="form-control main_subcategory_name" name="product_code">
                                        <option hidden class="subcategory_name">Select Category</option>
                                    </select>
                                </div>
                                <div class="form-group outer_price">
                                    <input type="text" name="product_price"  class="form-control my-input product_price" placeholder="Price">
                                </div>
                                <div class="outer_picture">
                                    <div class="file-upload-wrapper" data-text="Select your file!">
                                        <input name="img_file" id="product-img" type="file" class="file-upload-field product_picture">
                                    </div>
                                </div>
                                <div class="text-center ">
                                    <button type="button" class="btn btn-block send-button tx-tfm addItemBtn" onclick="addCategory()">SUBMIT</button>
                                </div>
                                <input type="hidden" name="func" value="addProduct" class="hidden_tag"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>