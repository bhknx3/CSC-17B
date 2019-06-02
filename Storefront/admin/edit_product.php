<?php
$page_title = 'Edit Product';
include ('../includes/header.php');
require_once "../config.php";
echo '<div class="page-header"><h2><b>Edit Product</b></h2></div>';

//Check admin status
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo '<h4 class="error text-center">Error: Access denied</h4>';
    exit;
}

//Check for a valid user ID, through GET or POST:
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) {
    $id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { //Form submission
    $id = $_POST['id'];
} else { //No valid ID, kill script
    echo '<h4 class="error text-center">Error: Produc tnot found</h4>';
    exit;
}

//Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = array();  //To hold errors

    //Check product name
    if (empty($_POST['product_name'])) {
        $errors[] = 'Please enter product name.';
    } else {
        $pn = mysqli_real_escape_string($link, trim($_POST['product_name']));
    }

    //Check description
    if (empty($_POST['description'])) {
        $errors[] = 'Please enter a description.';
    } else {
        $desc = mysqli_real_escape_string($link, trim($_POST['description']));
    }
    
    //Check color
    if (empty($_POST['color'])) {
        $errors[] = 'Please enter a color.';
    } else {
        $color = mysqli_real_escape_string($link, trim($_POST['color']));
    }

    //Check price
    if (empty($_POST['price'])) {
        $errors[] = 'Please enter a price.';
    } else {
        $price = mysqli_real_escape_string($link, trim($_POST['price']));
    }

    //Get type selected
    $type = mysqli_real_escape_string($link, trim($_POST['type']));

    //Check Image
    if (empty($_POST['image'])) {
        $errors[] = 'Please enter a image file.';
    } else {
        $image = mysqli_real_escape_string($link, trim($_POST['image']));
    }

    //If no errors
    if (empty($errors)) {
        // Make the query:
        $q = "UPDATE entity_product SET product_name='$pn', description='$desc', product_price='$price', product_color='$color', type='$type', image='$image' WHERE product_id = $id LIMIT 1";
        $r = mysqli_query ($link, $q);
        //If product successfully updated
        if (mysqli_affected_rows($link) == 1) {
            echo '<p class="text-center" style="color:green;">The product has been edited.</p>';
        //If error occurred
        } else {
            echo '<p class="error text-center">The product could not be edited due to a system error.</p>';
        }
    } else {    //Output errors
        echo '<p class="error text-center">The following error(s) occurred:<br />';
        foreach ($errors as $msg) { // Print each error.
                echo " - $msg<br>\n";
        }
        echo '<br>Please try again.</p>';
    }
}

//Query for products
$query = "SELECT * FROM `storefront`.`entity_product` AS `entity_product` WHERE product_id = '$id'";
$rs = mysqli_query($link, $query);

//Query for product types
$queryType = "SELECT * FROM `storefront`.`enum_product_type` AS `enum_product_type`";
$rsType = mysqli_query($link, $queryType);
$numType = mysqli_num_rows($rsType);    //Total number of product types

//If product ID chosen is valid
if (mysqli_num_rows($rs) == 1) {
    //Get product information:
    $row = mysqli_fetch_array($rs, MYSQLI_NUM);

    //Create form
    echo '<div class="form">';
    echo '<form action="edit_product.php?id=' . $id . '" method="post">
          <p>Product Name: <input type="text" name="product_name" value="' . $row[1] . '"></p>
          <p>Description:  <input type="text" name="description"  value="' . $row[2] . '"></p>
          <p>Type: <select name="type">';
    //Select box for product type
    for ($i=0; $i<$numType; $i++) {
        mysqli_data_seek($rsType, $i);
        $rowType = $rsType->fetch_assoc();
        //Output product types as a drop down list, default select the original product type
        if ($row[3] == $rowType['id']) {
            echo '<option selected="' . $rowType['product_type'] . '" value="' . ($i+1) . '">' . $rowType['product_type'] . '</option>';
        } else {
            echo '<option value="' . ($i+1) . '">' . $rowType['product_type'] . '</option>';
        }
    }
    echo '</select>
          <p>Color: <input type="text" name="color" value="' . $row[4] . '"> </p>
          <p>Price: <input type="text" name="price" value="' . $row[5] . '"> </p>
          <p>Image: <input type="text" name="image" value="' . $row[6] . '"> </p>
          <p><input class="center" type="submit" name="submit" value="Update"></p>
          <input type="hidden" name="id" value="' . $id . '" />
          </form>';
    echo '</div>';
    echo '<div class="text-center">
            <a href="inventory.php" class="btn btn-secondary">Go back to Inventory</a>
          </div>';
    
    echo '<span class="error"></span>';
}
else {  //If product ID is not valid
    echo '<h4 class="error text-center">This page has been accessed in error.</h4>';
}

//Product variant division
echo '<div class="page-header" style="margin-top:5%;"><h1><b>Product Variants</b></h1></div>';
echo "<div class='text-center' style='margin:5% 0%;'>";
echo '<a href="add_product_variant.php?id=' . $id . '" class="btn btn-primary">Add product variant</a><br><br>';

$query2 = "SELECT `entity_product`.`product_id`, `entity_product_variant`.`variant_id`, `entity_product_variant`.`variant_quantity`, `enum_size`.`size`, `enum_size`.`size_id` FROM `storefront`.`entity_product_variant` AS `entity_product_variant`, `storefront`.`enum_size` AS `enum_size`, `storefront`.`entity_product` AS `entity_product`, `storefront`.`enum_product_type` AS `enum_product_type`, `storefront`.`xref_product_variant_product` AS `xref_product_variant_product` WHERE `entity_product_variant`.`variant_size` = `enum_size`.`size_id` AND `entity_product`.`type` = `enum_product_type`.`id` AND `xref_product_variant_product`.`product_id` = `entity_product`.`product_id` AND `xref_product_variant_product`.`product_variant_id` = `entity_product_variant`.`variant_id` AND `entity_product`.`product_id` = $id ORDER BY `enum_size`.`size_id` ASC";
$rs2 = mysqli_query($link, $query2);

if (mysqli_num_rows($rs2) > 0) {
    //Create table of variants
    echo "<table class='tcenter' style='width:30%'>";  
    echo "<tr><th width=10%>" . 'Variant ID'  . "</th>";
    echo "<th width=10%>"     . 'Size'        . "</th>";
    echo "<th width=10%>"     . 'Quantity'    . "</th>";
    echo "<th width=7%></th>";
    echo "<th width=7%></th></tr>";
    while($re = mysqli_fetch_array($rs2, MYSQLI_BOTH)) {
        echo "<tr><td>" . $re['variant_id'] . "</td>";
        echo "<td>"     . $re['size']       . "</td>";
        echo "<td>"     . $re['variant_quantity']   . "</td>";
        echo "<td class='edit'><a href='edit_product_variant.php?id_1=" . $id . "&id_2=" . $re['variant_id'] . "'>Edit</a></td>";
        echo "<td class='edit'><a href='delete_product_variant.php?id_1=" . $id . "&id_2=" . $re['variant_id'] . "'>Delete</a></td></tr>";
    }
    echo "</table>";
    echo "</div>";
} else {
    echo '<p class="text-center">No variant found</p>';
}

mysqli_close($link);
?>

</body>
</html>