<?php
$page_title = 'Delete Product';
include ('../includes/header.php');
require_once "../config.php";
echo '<div class="page-header"><h2><b>Delete Product</b></h2></div>';

//Check admin status
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo '<h4 class="error text-center">Error: Access denied</h4>';
    exit;
}

//Check for a valid product ID, through GET or POST
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) {
    $id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) {
    $id = $_POST['id'];
} else { //No valid ID, kill script
    echo '<h4 class="error text-center">Error: Product not found</h4>';
    exit;
}

//Only if form has been submitted on the page
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Delete record if yes is chosen
    if ($_POST['sure'] == 'Yes') {
        //Delete any xref for the product
        $q = "DELETE FROM xref_product_variant_product WHERE product_id = $id";
        mysqli_query($link, $q);
        
        //Delete product_variant for the product
        $q2 = "DELETE FROM entity_product_variant WHERE product_id = $id";
        mysqli_query($link, $q2);

        //Delete the product itself
        $q3 = "DELETE FROM entity_product WHERE product_id = $id LIMIT 1";
        mysqli_query ($link, $q3);
        
        echo '<div class="text-center">';
        echo '<h4 style="color:green;">The product has been deleted.</h4>';
        echo '</div>';
    } else {
        echo '<div class="text-center">';
        echo '<p>The product has NOT been deleted.</p>';
        echo '</div>';
    }
    echo '<div class="text-center">
            <a href="inventory.php" class="btn btn-secondary">Go back to Inventory</a>
          </div>';
}
else {  //Show form
    $sql = "SELECT `entity_product`.*, `enum_product_type`.`product_type`, `entity_product`.`product_id` FROM `storefront`.`entity_product` AS `entity_product`, `storefront`.`enum_product_type` AS `enum_product_type` WHERE `entity_product`.`type` = `enum_product_type`.`id` AND `entity_product`.`product_id` = $id";
    $rs = mysqli_query($link,$sql);

    if (mysqli_num_rows($rs) == 1) {
        //Get product information
        $row = mysqli_fetch_array ($rs, MYSQLI_NUM);

        //Display the record being deleted
        echo "<div class='text-center'>";
        echo "<h3>Product name: $row[1]</h3>
              <h3>Description: $row[2]</h3>
              <h3>Type: $row[7]</h3>
              <h3>Price: $$row[5]</h3>
              </div>";

        //Create the form
        echo '<div class="form">
              <p>Are you sure you want to delete this product?</p>
              <form action="delete_product.php" method="post">
                <input type="radio" name="sure" value="Yes"> Yes
                <input type="radio" name="sure" value="No" style="margin-left:10px;" checked="checked"> No<br><br>
                <input type="submit" class="center" name="submit" value="Submit">
                <input type="hidden" name="id" value="' . $id . '">
              </form>';
        echo "</div>";
        
        echo '<div class="text-center">
                <a href="inventory.php" class="btn btn-secondary">Go back to Inventory</a>
              </div>';
    } else {    //Access error
        echo '<p class="error text-center">This page has been accessed in error.</p>';
    }
}
?>