<?php
$page_title = 'Orders';
include ('./includes/header.php');
require ('./config.php');
echo '<div class="page-header"><h2><b>Order History</b></h2></div>';

//Check that user is logged in, redirect if not
if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true) {
    header("location: /Storefront/login.php");
    exit;
}

$userID = $_SESSION['id'];

//Get order count from user
$qOrder = "SELECT `entity_order`.`order_id`, `entity_user`.`user_id`, `entity_order`.`created_at` FROM `storefront`.`xref_user_order` AS `xref_user_order`, `storefront`.`entity_user` AS `entity_user`, `storefront`.`entity_order` AS `entity_order` WHERE `xref_user_order`.`user_id` = `entity_user`.`user_id` AND `xref_user_order`.`order_id` = `entity_order`.`order_id` AND `entity_user`.`user_id` = $userID ORDER BY `entity_order`.`created_at` DESC";
$rOrder = mysqli_query($link, $qOrder);
$numOrder = mysqli_num_rows($rOrder);

if ($numOrder > 0) {
    $orders = array();
    while ($re = mysqli_fetch_array($rOrder, MYSQLI_BOTH)) {
        array_push($orders, $re['order_id']);
    }
    
    for ($i=0; $i<count($orders); $i++) {
        $q = "SELECT `entity_order`.`order_id`, `entity_user`.`user_id`, `entity_product`.`product_name`, `enum_size`.`size`, `entity_order_item`.`order_item_quantity`, `entity_order_item`.`order_item_price`, `entity_order`.`created_at`, `entity_order`.`order_total` FROM `storefront`.`entity_product` AS `entity_product`, `storefront`.`enum_product_type` AS `enum_product_type`, `storefront`.`entity_product_variant` AS `entity_product_variant`, `storefront`.`enum_size` AS `enum_size`, `storefront`.`xref_order_item_product_variant` AS `xref_order_item_product_variant`, `storefront`.`entity_order_item` AS `entity_order_item`, `storefront`.`xref_order_order_item` AS `xref_order_order_item`, `storefront`.`entity_order` AS `entity_order`, `storefront`.`xref_product_variant_product` AS `xref_product_variant_product`, `storefront`.`xref_user_order` AS `xref_user_order`, `storefront`.`entity_user` AS `entity_user` WHERE `entity_product`.`type` = `enum_product_type`.`id` AND `entity_product_variant`.`variant_size` = `enum_size`.`size_id` AND `xref_order_item_product_variant`.`order_item_id` = `entity_order_item`.`order_item_id` AND `xref_order_item_product_variant`.`product_variant_id` = `entity_product_variant`.`variant_id` AND `xref_order_order_item`.`order_id` = `entity_order`.`order_id` AND `xref_order_order_item`.`order_item_id` = `entity_order_item`.`order_item_id` AND `xref_product_variant_product`.`product_id` = `entity_product`.`product_id` AND `xref_product_variant_product`.`product_variant_id` = `entity_product_variant`.`variant_id` AND `xref_user_order`.`user_id` = `entity_user`.`user_id` AND `xref_user_order`.`order_id` = `entity_order`.`order_id` AND `entity_order`.`order_id` = $orders[$i] AND `entity_user`.`user_id` = $userID";
        $r = mysqli_query($link, $q);

        mysqli_data_seek($r, 0);
        $row = $r->fetch_assoc();

        echo '<div class="order">
              <fieldset style="margin-top:5%;">
              <div style="display:inline-block; margin-left:5%;">
                <legend style="font-size: 1.2em";>
                    <p>Order Reference: #' . $row["order_id"] . '</p>
                    <p style="vertical-align:middle;">Date Processed: ' . $row["created_at"] . '</p>
                    <p style="vertical-align:middle;">Order Total: $' . number_format($row['order_total'], 2) . '</p><br>
                </legend>
                <p>Order Details:</p>
              </div>';

        echo '<table class="tcenter" style="width:90%; margin-bottom:5%;">
                <th>Product</th>
                <th>Size</th>
                <th>Quantity</th>
                <th>Price per unit</th>
                <th>Total Price</th>';

        mysqli_data_seek($r ,0);
        while ($re = mysqli_fetch_array($r, MYSQLI_BOTH)) {
            echo '<tr><td>' . $re['product_name'] . '</td>
                  <td>' . $re['size'] . '</td>
                  <td>' . $re['order_item_quantity'] . '</td>
                  <td>$' . number_format($re['order_item_price'], 2) . '</td>
                  <td>$' . number_format($re['order_item_quantity'] * $re['order_item_price'], 2) . '</td></tr>';
        }

        echo'</table></fieldset></div><br>';  
    }
} else {
    echo '<p class="text-center">No order history</p>';
}

mysqli_close($link);