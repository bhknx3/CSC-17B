<?php
$page_title = 'Add Product';
include ('../includes/header.php');
require "../config.php";
echo '<div class="page-header"><h2><b>Add Product</b></h2></div>';

//Deny access to any non-admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo '<h4 class="error text-center">Error: Access denied</h4>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') { //Handle post form
    //Check if all fields have an input
    if (!empty($_POST['product_name']) && !empty($_POST['description']) && !empty($_POST['price']) && !empty($_POST['image']) ) {
        //Trim inputs
        $pn    = trim($_POST['product_name']);
        $desc  = trim($_POST['description']);
        $type  = $_POST['type'];
        $color = trim($_POST['color']);
        $price = trim($_POST['price']);
        $img   = trim($_POST['image']);

        //Add product to database
        $q = 'INSERT INTO entity_product (product_name, description, type, product_color, product_price, image) VALUES (?, ?, ?, ?, ?, ?)';
        $stmt = mysqli_prepare($link, $q);
        mysqli_stmt_bind_param($stmt, 'ssssss', $pn, $desc, $type, $color, $price, $img);
        mysqli_stmt_execute($stmt);

        //Check results
        if (mysqli_stmt_affected_rows($stmt) == 1) {
            echo '<p class="text-center" style="color:green;">The product has been added.</p>';
            $_POST = array();
        } else {
            $error = 'The product could not be added to the database.';
        }

        //Close prepared statement
        mysqli_stmt_close($stmt);
    } else {
        $error = 'Please enter all fields'; //Error message
    }
}

// Check for an error and print it:
if (isset($error)) {
    echo '<div class="text-center">
          <h1>Error!</h1>
          <p style="font-weight:bold; color:#C00">' . $error . '</p>
          </div>';
}
?>

<div class="form">
    <form action="add_product.php" method="post">
        <p>Product Name: <input type="text" name="product_name"></p>
        <p>Description:  <input type="text" name="description"></p>

        <?php
        //Query for product types
        $qType = "SELECT * FROM `storefront`.`enum_product_type` AS `enum_product_type`";
        $rsType = mysqli_query($link, $qType);
        $numType = mysqli_num_rows($rsType);    //Total number of product types
        
        echo '<p>Type: <select name="type">';
        //Select box for product type
        for ($i=0; $i<$numType; $i++) {
            mysqli_data_seek($rsType, $i);
            $rowType = $rsType->fetch_assoc();
            //Output product types as a drop down list, default select the original product type
            echo '<option value="' . ($i+1) . '">' . $rowType['product_type'] . '</option>';
        }
        echo '</select>';
        ?>
        
        <p>Color: <input type="text" name="color"></p>
        <p>Price: <input type="text" name="price"></p>
        <p>Image: <input type="text" name="image"></p>
        <div class="text-center"> <input type="submit" name="submit" value="Add"></div>
    </form>
</div>
    
<div class="text-center">
    <a href="inventory.php" class="btn btn-secondary">Go back to Inventory</a>
</div>

</body>
</html>