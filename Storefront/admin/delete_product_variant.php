<?php
$page_title = 'Delete Product Variant';
include ('../includes/header.php');
require_once "../config.php";
echo '<div class="page-header"><h2><b>Delete Product Variant</b></h2></div>';

//Check admin status
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo '<h4 class="error text-center">Error: Access denied</h4>';
    exit;
}

//Check for a valid product ID, through GET or POST
if ( (isset($_GET['id_1'])) && (is_numeric($_GET['id_1'])) && (isset($_GET['id_2'])) && (is_numeric($_GET['id_2'])) ) {
    $id1 = $_GET['id_1'];
    $id2 = $_GET['id_2'];
} elseif ( (isset($_POST['id_1'])) && (is_numeric($_POST['id_1'])) && (isset($_POST['id_2'])) && (is_numeric($_POST['id_2'])) ) {
    $id1 = $_POST['id_1'];
    $id2 = $_POST['id_2'];
} else { //No valid ID, kill script
    echo '<h4 class="error text-center">Error: Product variant not found</h4>';
    exit;
}

//Only if form has been submitted on the page
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Delete record if yes is chosen
    if ($_POST['sure'] == 'Yes') { 
        //Delete any xref for the product variant
        $q = "DELETE FROM xref_product_variant_product WHERE product_variant_id = $id2";
        mysqli_query($link, $q);
        
        //Delete the product variant
        $q2 = "DELETE FROM entity_product_variant WHERE variant_id = $id2";
        mysqli_query($link, $q2);
        
        echo '<div class="text-center">';
        echo '<p class="text-center" style="color:green;">The product variant has been deleted.</p>';
        echo '</div>';
    } else {
        echo '<div class="text-center">';
        echo '<p>The product variant has NOT been deleted.</p>';
        echo '</div>';
    }
    echo '<div class="text-center">
            <a href="inventory.php" class="btn btn-secondary">Go back to Inventory</a>
          </div>';
}
else {  //Show form
    $sql = "SELECT `entity_product`.`product_id`, `entity_product_variant`.`variant_id`, `entity_product`.`product_name`, `enum_size`.`size`, `entity_product_variant`.`variant_quantity` FROM `storefront`.`entity_product` AS `entity_product`, `storefront`.`enum_product_type` AS `enum_product_type`, `storefront`.`entity_product_variant` AS `entity_product_variant`, `storefront`.`enum_size` AS `enum_size`, `storefront`.`xref_product_variant_product` AS `xref_product_variant_product` WHERE `entity_product`.`type` = `enum_product_type`.`id` AND `entity_product_variant`.`variant_size` = `enum_size`.`size_id` AND `xref_product_variant_product`.`product_id` = `entity_product`.`product_id` AND `xref_product_variant_product`.`product_variant_id` = `entity_product_variant`.`variant_id` AND `entity_product`.`product_id` = $id1 AND `entity_product_variant`.`variant_id` = $id2";
    $rs = mysqli_query($link,$sql);

    if (mysqli_num_rows($rs) == 1) {
        //Get product information
        $row = mysqli_fetch_array ($rs, MYSQLI_NUM);

        //Display the record being deleted
        echo "<div class='text-center'>
              <h3>Product name: $row[2]</h3>
              <h3>Size: $row[3]</h3>
              <h3>Quantity: $row[4]</h3>
              </div>";

        //Create the form
        echo '<div class="form">
              <p>Are you sure you want to delete this product variant?</p>
              <form action="delete_product_variant.php" method="post">
                <input type="radio" name="sure" value="Yes"> Yes
                <input type="radio" name="sure" value="No" style="margin-left:10px;" checked="checked"> No<br><br>
                <input type="submit" class="center" name="submit" value="Submit">
                <input type="hidden" name="id_1" value="' . $id1 . '">
                <input type="hidden" name="id_2" value="' . $id2 . '">
              </form>';
        echo "</div>";

        echo '<div class="text-center">
                <a href="inventory.php" class="btn btn-secondary">Go back to Inventory</a>
              </div>';
    } else {    //Access error
        echo '<p class="error text-center">This page has been accessed in error.</p>';
    }
}

mysqli_close($link);
?>