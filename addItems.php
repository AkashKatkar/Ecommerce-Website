<html>
    <head>
        <title>Add Items</title>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="referrer" content="none">

        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <link href="css/addItems.css" rel="stylesheet">
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="js/addItems.js"></script>
    </head>
    <body>
        <div id="addItems">
            <div class="container">
                <div id="addItems-row" class="row justify-content-center align-items-center">
                    <div id="addItems-column">
                        <div id="addItems-box" class="col-md-12">
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
                                <div class="text-center">
                                    <button type="button" class="btn btn-block send-button tx-tfm addItemBtn" onclick="addCategory()">SUBMIT</button>
                                </div>
                                <input type="hidden" name="func" value="addProduct" class="hidden_tag"/>
                            </form>

                            <div class="text-center main_restoreDataBtn">
                                <button class="btn btn-primary restoreDataBtn" onclick="getRestoreDataBtn('category')">RESTORE CATEGORY</button>
                            </div>
                            <div class="main_restoreData" style="display:none;">
                                <select class="form-control restoreData">
                                    <option hidden class="selectOneItem">Select item to add</option>
                                </select>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div hidden class="showModal" data-toggle="modal" data-target="#exampleModalCenter"></div>
		<!-- Modal -->
		<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLongTitle">Restore or Delete Data</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body text-dark">
						Press <b>RESTORE</b> Button If Retrieve Your Data.
						You Cannot Retrieve Any of Those Data & Informations After Press <b>PERMANENT DELETE</b> Button.
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" onclick="Permanent_delete_btn()">PERMANENT DELETE</button>
						<button type="button" class="btn btn-success" onclick="restore_btn()">RESTORE</button>
					</div>
				</div>
			</div>
		</div>
    </body>
</html>