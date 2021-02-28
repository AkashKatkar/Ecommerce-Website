<html lang="en">
	<head>
        <title>Ecommerce Website</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="referrer" content="none">

		<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<link href='https://fonts.googleapis.com/css?family=Baloo' rel='stylesheet'>
		<link rel="stylesheet" href="css/style.css">
        <link href="css/index.css" rel="stylesheet"/>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
		<script src="js/index.js"></script>
	</head>
  	<body>
		<div class="wrapper d-flex align-items-stretch">
			<nav id="sidebar">
				<div class="custom-menu">
					<button type="button" id="sidebarCollapse" class="btn btn-primary">
						<i class="fa fa-bars"></i>
						<span class="sr-only">Toggle Menu</span>
					</button>
				</div>
				<div class="p-4">
					<h3><a href="index.php" class="logo">E-Commerce</a></h3>
					<ul class="list-unstyled components mb-5">
						<li class="active inactive_nav nav1" onclick="navigation_to('category')">
							<a href="#"><span class="fa fa-copyright mr-3" style="font-size:18px"></span> Category</a>
						</li>
						<li class="inactive_nav nav2" onclick="navigation_to('subcategory')">
							<a href="#"><span class="fa fa-creative-commons mr-3"></span> Sub-Category</a>
						</li>
						<li class="inactive_nav nav3" onclick="navigation_to('product')">
							<a href="#"><span class="fa fa-product-hunt mr-3"></span> Product</a>
						</li>
					</ul>
				</div>
    		</nav>

			<!-- Page Content  -->
			<div class="container" id="content" style="margin-top: 70px;margin-bottom: 50px;">
				<div class="card">
					<div class="card-header" style='text-align: center;font-size:25px; padding: 3px;'>Category</div>
					
					<div class="card-body">
						<div class="dropdown" style="margin-bottom: 30px;width: 100%;display: flex;">
							<div style='flex:1;'>
								<button type="button" class="btn btn-info dropdown-toggle dropdown" style="width: 60px;height: 45px;padding-right: 25px;" data-toggle="dropdown">5</button>
								<div class="dropdown-menu">
									<a class="dropdown-item showRows3">3</a>
									<a class="dropdown-item showRows5">5</a>
									<a class="dropdown-item showRows10">10</a>
									<a class="dropdown-item showRows15">15</a>
									<a class="dropdown-item showRows20">20</a>
								</div>
							</div>

							<div class="justify-content-center" style='flex:1;'>  <!-- margin-right:70px; -->
								<div class="search">
									<input type="text" class="search-input" placeholder="Search...">
									<a href="#" class="search-icon" onclick="search_query()">
										<i class="fa fa-search"></i> 
									</a> 
								</div>
							</div>

							<div style='flex:1;'>
								<div class="addData float-right">
									<button type="button" class="btn btn-info" style="height: 45px;" onclick="redirectAddItems('addCategory')">Add Category</button>
								</div>
							</div>
						</div>
						<div id="index_tables_onload"></div>
					</div> 
				</div>
			</div>
		</div>

		<script src="js/main.js"></script>
  	</body>
</html>