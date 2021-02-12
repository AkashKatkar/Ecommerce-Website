<html>
    <head>
        <title>Ecommerce Website</title>
    </head>
    <body>
        <input type="button" value="Add Category">
        <?php
            date_default_timezone_set("Asia/Calcutta");
            ini_set("display_errors", 1);
            ini_set("log_errors", 1);
            ini_set("error_log",'error_log.txt');

            $conn = new mysqli("localhost","root","", "ecommerce_website");
            if ($conn->error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $stmt = $conn->prepare("SELECT * FROM category WHERE parent_id IS NULL");
            $stmt->execute();
            $result = $stmt->get_result();
            $output="<table border='1' cellpadding='5px'>
                        <tr>
                            <th>Category Name</th>
                            <th>Code</th>
                            <th>Action</th>
                        </tr>";
                            while($row = $result->fetch_assoc()){
                                $output.="<tr><td>".$row['category_name']."</td>
                                <td>".$row['code']."</td>
                                <td><input type='button' value='EDIT' style='margin-right:5px'><input type='button' value='DELETE'></td></tr>";
                            }
            $output.="</table><br/>";

            $stmt = $conn->prepare("SELECT * FROM category WHERE parent_id IS NOT NULL");
            $stmt->execute();
            $result = $stmt->get_result();
            $output.='<input type="button" value="Add Sub-Category">';
            $output.="<table border='1' cellpadding='5px'>
                <tr>
                    <th>Sub-Category Name</th>
                    <th>Code</th>
                    <th>Category</th>
                    <th>Action</th>
                </tr>";
                while($row = $result->fetch_assoc()){
                    $stmt1 = $conn->prepare("SELECT category_name FROM category WHERE id=".$row['parent_id']);
                    $stmt1->execute();
                    $result1 = $stmt1->get_result();
                    $row1 = $result1->fetch_assoc();

                    $output.="<tr><td>".$row['category_name']."</td>
                    <td>".$row['code']."</td>
                    <td>".$row1['category_name']."</td>
                    <td><input type='button' value='EDIT' style='margin-right:5px'><input type='button' value='DELETE'></td></tr>";
                }
            $output.="</table><br/>";

            $stmt=$conn->prepare("SELECT * FROM product");
            $stmt->execute();
            $result=$stmt->get_result();
            $output.='<input type="button" value="Add Product">';
            $output.="<table border='1' cellpadding='5px'>
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Image</th>
                </tr>";
                while($row=$result->fetch_assoc()){
                    $stmt1 = $conn->prepare("SELECT category_name FROM category WHERE id=(SELECT parent_id FROM category WHERE code='".$row["code"]."')");
                    $stmt1->execute();
                    $result1 = $stmt1->get_result();
                    $row1 = $result1->fetch_assoc();
                    $output.="<tr><td>".$row['prod_name']."</td>
                    <td>".$row1['category_name']."</td>
                    <td>".$row['price']."</td>
                    <td><img src=".$row['image']." alt=".$row["code"]." width='100' height='100' /></td></tr>";
                }

            echo $output;
            $stmt->close();
            $stmt1->close();
            $conn->close();
        ?>
    </body>
</html>