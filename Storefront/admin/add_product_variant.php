<?php
$page_title = 'Add Product Variant';
include ('../includes/header.php');
require "../config.php";
echo '<div class="page-header"><h2><b>Add Product Variant</b></h2></div>';

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
    echo '<h4 class="error text-center">Error: Product variant not found</h4>';
    exit;
}

//For form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Check if all fields have an input
    if (!empty($_POST['size']) && !empty($_POST['quantity'])) {
        //Trim inputs
        $size = trim($_POST['size']);       //Get size from form as: XS, S, M, L, XL, etc...
        $qty  = trim($_POST['quantity']);

        //Convert the size and check if the enum_size table contains it
        $qSz = "SELECT `size_id`, `size` FROM `storefront`.`enum_size` AS `enum_size` WHERE `size` = '$size'";
        $rSz = mysqli_query($link, $qSz);
        
        //If query successful
        if (mysqli_num_rows($rSz) == 1) {
            $row = mysqli_fetch_array($rSz, MYSQLI_NUM);
            $sizeInt = $row[0]; //Size as an integer (Ex: Input XL -> Output 6)
            
            // Add product to database
            $q = "INSERT INTO entity_product_variant (variant_size, variant_quantity) VALUES (?, ?)";
            $stmt = mysqli_prepare($link, $q);
            mysqli_stmt_bind_param($stmt, 'ss', $sizeInt, $qty);
            mysqli_stmt_execute($stmt);

            //Check results
            if (mysqli_stmt_affected_rows($stmt) == 1) {  
                //Add to xref_product_variant_product
                $variant_id = mysqli_insert_id($link);
                $q2 = "INSERT INTO xref_product_variant_product (product_id, product_variant_id) VALUES (?, ?)";
                $stmt2 = mysqli_prepare($link, $q2);
                mysqli_stmt_bind_param($stmt2, 'ss', $id, $variant_id);
                mysqli_stmt_execute($stmt2);
                mysqli_stmt_close($stmt2);

                //Output message
                echo '<p class="text-center" style="color:green;">The product variant has been added.</p>';
            } else {    //Error occurred
                $error = 'The product variant could not be added to the database.';
            }
            
            mysqli_stmt_close($stmt);   //Close prepared statement 
        }
        else {
            echo '<h4 class="error text-center">Please enter a valid size. If not sure, please look at manual.</h4>';
        }
    } else {
        $error = 'Please enter all fields'; //Error message
    }
}

// Check for an error and print it:
if (isset($error)) {
    echo '<p class="error text-center">Error: ' . $error . '</p>';
}

$query = "SELECT `entity_product`.`product_id`, `entity_product`.`product_name`, `entity_product`.`description`, `enum_product_type`.`product_type`, `entity_product`.`product_color`, `entity_product`.`product_price` FROM `storefront`.`entity_product` AS `entity_product`, `storefront`.`enum_product_type` AS `enum_product_type` WHERE `entity_product`.`type` = `enum_product_type`.`id` AND `entity_product`.`product_id` = $id";
$rs = mysqli_query($link, $query);

echo "<div class='text-center'>";
echo "<table border='1' class='tcenter'>";
echo "<tr><th>" . 'Name'        . "</th>";
echo "<th>"     . 'Description' . "</th>";
echo "<th>"     . 'Type'        . "</th>";
echo "<th>"     . 'Color'       . "</th>";
echo "<th>"     . 'Price'       . "</th>";
while($re = mysqli_fetch_array($rs, MYSQLI_BOTH)) {
    echo "<tr><td>" . $re['product_name']  . "</td>";
    echo "<td>"     . $re['description']  . "</td>";
    echo "<td>"     . $re['product_type']  . "</td>";
    echo "<td>"     . $re['product_color']  . "</td>";
    echo "<td>"     . $re['product_price']  . "</td></tr>";
}
echo "</table>";
echo "</div>";
?>

<div class="form">
    <form action="add_product_variant.php" method="post">
        <p>Size: <input type="text" name="size"></p>
        <p>Quantity: <input type="text" name ="quantity"></p>
        <div class="text-center"> <input type="submit" name="submit" value="Add Variant"></div>
        <?php echo '<input type="hidden" name="id" value="' . $id . '">'; ?>
    </form>
</div>

<div class="text-center">
    <a href="inventory.php" class="btn btn-secondary">Go back to Inventory</a>
</div>

</body>
</html>