<?php
$page_title = 'Cart';
include ('includes/header.php');
require_once "config.php";

//Decode object
$obj = json_decode($_COOKIE["product"]);

//Set variables
$varID = $obj->varID;   //Product ID
$qty = $obj->qty;       //Quantity

//Check if the cart already contains the same item
if (isset($_SESSION['cart'][$varID])) {
    //Add new quantity
    $_SESSION['cart'][$varID]['quantity'] += $qty;
    echo '<p class="text-center">Shopping cart has been updated.</p>';
    header("location: view_cart.php");
} else { //New product
    //Query for price of product variant
    $q = "SELECT `entity_product_variant`.`variant_id`, `entity_product`.`product_price` FROM `storefront`.`xref_product_variant_product` AS `xref_product_variant_product`, `storefront`.`entity_product` AS `entity_product`, `storefront`.`entity_product_variant` AS `entity_product_variant` WHERE `xref_product_variant_product`.`product_id` = `entity_product`.`product_id` AND `xref_product_variant_product`.`product_variant_id` = `entity_product_variant`.`variant_id` AND `entity_product_variant`.`variant_id` = '$varID'";
    $r = mysqli_query($link, $q);
    
    //Get price
    if (mysqli_num_rows($r) == 1) {
        $row = $r->fetch_assoc();
        $price = $row['product_price'];
    }

    //Add to cart, utilize session
    $_SESSION['cart'][$varID] = array('quantity' => $qty, 'price' => $price);
    header("location: view_cart.php");
}