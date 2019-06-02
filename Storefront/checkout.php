<?php
$page_title = 'Checkout';
include ('includes/header.php');
require_once "config.php";

//First, check if user is logged in. If not, redirect
if (!isset($_SESSION['loggedin']) && $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
} elseif (!isset($_SESSION['order_total']) || !isset($_SESSION['id'])) {
    echo '<p class="error text-center">Access denied</p>';
    exit;
} else {
    $userID = $_SESSION['id'];          //Get user ID
    $total = $_SESSION['order_total'];  //Get total price
}

//Insert into order database
$q = "INSERT INTO entity_order (order_total) VALUES ($total)";
$r = mysqli_query($link, $q);
$orderID = $link->insert_id;    //Store primary key of order created

if (mysqli_affected_rows($link) == 1) {
    //Insert into xref_user_order - connect user and order
    $q2 = "INSERT INTO xref_user_order (order_id, user_id) VALUES ($orderID, $userID)";
    $r2 = mysqli_query($link, $q2);
    
    if (mysqli_affected_rows($link) == 1) {
        foreach ($_SESSION['cart'] as $varID => $item) {
            $qty = $item['quantity'];   //Get quantity
            $price = $item['price'];    //Get price

            //Insert individual items of the order into database
            $q3 = "INSERT INTO entity_order_item (order_item_quantity, order_item_price) VALUES ($qty, $price)";
            $r3 = mysqli_query($link, $q3);

            $last_id = $link->insert_id;    //Last entered PrimaryID of entity order_item from previous query

            //Insert xref_order_item_product_variant - connect order item and product variant
            $q4 = "INSERT INTO xref_order_item_product_variant (order_item_id, product_variant_id) VALUES ($last_id, $varID)";
            $r4 = mysqli_query($link, $q4);

            $last_id = $link->insert_id;    //Last entered primary ID of order item

            //Insert into xref_order_order_item - connect order and ordered items
            $q5 = "INSERT INTO xref_order_order_item (order_id, order_item_id) VALUES ($orderID, $last_id)";
            $r5 = mysqli_query($link, $q5);
        }
        
        //Make sure success
        unset($_SESSION['cart']);   //Clear cart
            
        //Output message
        echo '<div class="page-header"><h2><b>Order Success</b></h2></div>
                <div class="text-center">
                <h4 style="padding-bottom:100px;">Thank you for your order!<br>A confirmation email will be delivered to you shortly.</h4>
                <a href="home.php" style="width:200px;" class="btn btn-success">Continue Shopping</a>
              </div>';
    }
} else {
    echo '<p class="text-center">Your order could not be processed due to a system error. We apologize for any inconvenience. Please try again later</p>';
}

mysqli_close($link);
?>