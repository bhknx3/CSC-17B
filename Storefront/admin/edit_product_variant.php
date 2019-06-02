<?php
$page_title = 'Edit Product Variant';
include ('../includes/header.php');
require_once "../config.php";
echo '<div class="page-header"><h2><b>Edit Product Variant</b></h2></div>';

//Check admin status
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo '<h4 class="error text-center">Error: Access denied</h4>';
    exit;
}

//Check for a valid IDs through GET or POST
if ( isset($_GET['id_1']) && is_numeric($_GET['id_1']) && isset($_GET['id_2']) && is_numeric($_GET['id_2']) ) {
    $id1 = $_GET["id_1"];
    $id2 = $_GET["id_2"];
} elseif ( isset($_POST['id_1']) && is_numeric($_POST['id_1']) && isset($_POST['id_2']) && is_numeric($_POST['id_2']) ) {
    $id1 = $_POST["id_1"];
    $id2 = $_POST["id_2"];
} else {
    echo '<h4 class="error text-center">Error: Product variant not found</h4>';
    exit;
}

//Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Check product quantity
    if (!empty($_POST['quantity'])) {
        $qty = mysqli_real_escape_string($link, trim($_POST['quantity']));
    }

    //Make the query
    $query = "UPDATE `entity_product_variant` SET `variant_quantity` = $qty WHERE `variant_id` = $id2";
    mysqli_query($link, $query);

    //If product variant successfully updated
    if (mysqli_affected_rows($link) == 1) {
        echo '<p class="text-center" style="color:green;">The product variant has been edited.</p>';
    //If error occurred
    } else {
        echo '<p class="error text-center">The product variant could not be edited due to a system error.</p>';
    }
}

$sql = "SELECT `entity_product`.`product_id`, `entity_product_variant`.`variant_id`, `entity_product`.`product_name`, `enum_size`.`size`, `entity_product_variant`.`variant_quantity` FROM `storefront`.`entity_product` AS `entity_product`, `storefront`.`enum_product_type` AS `enum_product_type`, `storefront`.`entity_product_variant` AS `entity_product_variant`, `storefront`.`enum_size` AS `enum_size`, `storefront`.`xref_product_variant_product` AS `xref_product_variant_product` WHERE `entity_product`.`type` = `enum_product_type`.`id` AND `entity_product_variant`.`variant_size` = `enum_size`.`size_id` AND `xref_product_variant_product`.`product_id` = `entity_product`.`product_id` AND `xref_product_variant_product`.`product_variant_id` = `entity_product_variant`.`variant_id` AND `entity_product`.`product_id` = $id1 AND `entity_product_variant`.`variant_id` = $id2";
$rs = mysqli_query($link, $sql);

if (mysqli_num_rows($rs) == 1) {
    //Get product information:
    $row = mysqli_fetch_array ($rs, MYSQLI_NUM);

    //Display the record being deleted
    echo "<div class='text-center'>";
    echo "<h3>Product name: $row[2]</h3>
          <h3>Size: $row[3]</h3>
          <h3>Current Quantity: $row[4]</h3>";
    echo "</div>";
}
?>

<div class="form">
    <form action="edit_product_variant.php" method="post">
        <p>Quantity: <input type="number" style="width:50px;" min="1" name="quantity" value="<?php echo $row[4]; ?>"></p>
        <input type="submit" class="center" name="submit" value="Update" />
        <input type="hidden" name="id_1" value="<?php echo $id1; ?>">
        <input type="hidden" name="id_2" value="<?php echo $id2; ?>">
    </form>
</div>

<div class="text-center">
    <a href="inventory.php" class="btn btn-secondary">Go back to Inventory</a>
</div>
        
</body>
</html>