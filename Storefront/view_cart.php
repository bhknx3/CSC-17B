<?php
//This page displays the contents of the shopping cart and lets user update cart contents
$page_title = 'Shopping Cart';
include ('includes/header.php');
require_once "config.php";
echo '<div class="page-header"><h2><b>Cart</b></h2></div>';

//GET method - removing cart items
if (isset($_GET['vid']) && is_numeric($_GET['vid'])) {
    $vid = $_GET['vid'];
    unset($_SESSION['cart'][$vid]);     //Remove item
    header("location: view_cart.php");
}

//Check if the form has been submitted to update cart
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Change any quantities:
    foreach ($_POST['qty'] as $k => $v) {  
        $varID = (int)$k;
        $qty = (int)$v;
        
        //Remove quantity
        if ($qty == 0) {
            unset ($_SESSION['cart'][$varID]);
        //Update quantity
        } elseif ($qty > 0) {
            $_SESSION['cart'][$varID]['quantity'] = $qty;
        }
    }
}

//Display the cart
if (!empty($_SESSION['cart'])) {
    //Query product details
    $q = "SELECT `entity_product`.`image`, `entity_product_variant`.`variant_id`, `entity_product`.`product_name`, `enum_size`.`size`, `entity_product`.`product_price` FROM `storefront`.`xref_product_variant_product` AS `xref_product_variant_product`, `storefront`.`entity_product` AS `entity_product`, `storefront`.`entity_product_variant` AS `entity_product_variant`, `storefront`.`enum_product_type` AS `enum_product_type`, `storefront`.`enum_size` AS `enum_size` WHERE `xref_product_variant_product`.`product_id` = `entity_product`.`product_id` AND `xref_product_variant_product`.`product_variant_id` = `entity_product_variant`.`variant_id` AND `entity_product`.`type` = `enum_product_type`.`id` AND `entity_product_variant`.`variant_size` = `enum_size`.`size_id` AND `entity_product_variant`.`variant_id` IN (";
    foreach ($_SESSION['cart'] as $varID => $value) {
        $q .= $varID . ',';     //Add each product in cart to query
    }
    $q = substr($q, 0, -1) . ')';
    $r = mysqli_query($link, $q);
    
    // Create a form and a table:
    echo '<form action="view_cart.php" method="post">
          <table border="0" class="tcenter">
          <tr>
            <td width="20%"><b>Image</b></td>
            <td width="30%"><b>Product</b></td>
            <td width="10%"><b>Size</b></td>
            <td width="20%"><b>Price</b></td>
            <td width="10%"><b>Quantity</b></td>
            <td width="25%"><b>Total Price</b></td>
            <td width="10%"></td>
           </tr>';
    
    //Print each item
    $total = 0; //Total cost of order
    while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {
        //Calculate the total and sub-totals
        $subtotal = $_SESSION['cart'][$row['variant_id']]['quantity'] * $row['product_price']; //Quantity * Price
        $total += $subtotal;

        //Print the row
        echo "<tr>
                <td><img height='100px;' width='300px;' src='./img/{$row['image']}'</td>
                <td>{$row['product_name']}</td>
                <td>{$row['size']}</td>
                <td>\${$row['product_price']}</td>
                <td><input type='number' style='width:50px;' min='0' max='99' name='qty[{$row['variant_id']}]' value='{$_SESSION['cart'][$row['variant_id']]['quantity']}'/></td>
                <td>$" . number_format ($subtotal, 2) . "</td>
                <td><a href='view_cart.php?vid=" . $row['variant_id'] . "'>Remove</a></td>
              </tr>\n";
    }
    
    mysqli_close($link);    //Close database connection

    //Print total, close table and form
    echo '<td colspan="7"><h4><b>Order Total: $' . number_format ($total, 2) . '</b></h4></div></td>
          </table>
         
          <div class="text-center">
             <input type="submit" name="submit" value="Update Cart" />
             </form><br><br>
             <a href="checkout.php" class="cart-btn">Checkout</a></p>
          </div>';
          
          $_SESSION['order_total'] = number_format($total, 2);

} else {    //Message if cart is empty
	echo '<p class="text-center">Your cart is currently empty</p>';
}
?>