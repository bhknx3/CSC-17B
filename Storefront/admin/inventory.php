<?php
$page_title = 'Manage Inventory';
include ('../includes/header.php');
require_once "../config.php";
echo '<div class="page-header"><h2><b>Inventory Manager</b></h2></div>';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo '<h4 class="error text-center">Error: Access denied</h4>';
    exit;
}

//Number of records to show per page
$display = 7;

//Determine how many pages there are
if (isset($_GET['p']) && is_numeric($_GET['p'])) { // Already been determined.
    $pages = $_GET['p'];
} else {    //Need to determine pages
    //Count number of records
    $q = "SELECT COUNT(product_id) FROM entity_product";
    $r = mysqli_query ($link, $q);
    $row = mysqli_fetch_array($r, MYSQLI_NUM);
    $records = $row[0];
    
    //Calculate number of pages
    if ($records > $display) { //More than 1 page
        $pages = ceil ($records/$display);
    } else {
        $pages = 1;
    }
}

//Determine where in the database to start returning results
if (isset($_GET['s']) && is_numeric($_GET['s'])) {
    $start = $_GET['s'];
} else {
    $start = 0;
}

//Default is by registration date
$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'pid';

//Determine sort
switch ($sort) {
    case 'pid':
        $order_by = 'product_id ASC';
        break;
    case 'name':
        $order_by = 'product_name ASC';
        break;
    case 'type':
        $order_by = 'product_type ASC';
        break;
    case 'col':
        $order_by = 'product_color ASC';
        break;
    case 'p':
        $order_by = 'product_price ASC';
        break;
    default:
        $order_by = 'product_id ASC';
        $sort = 'pid';
        break;
}

//Query products with page limit
$sql = "SELECT `entity_product`.*, `enum_product_type`.`product_type` FROM `storefront`.`entity_product` AS `entity_product`, `storefront`.`enum_product_type` AS `enum_product_type` WHERE `entity_product`.`type` = `enum_product_type`.`id` ORDER BY $order_by LIMIT $start, $display";
$rs = mysqli_query($link,$sql);

//Output information
echo "<div class='text-center'>";
echo '<a href="add_product.php" class="btn btn-primary">Add product</a><br><br>';   //Add product button

//Output table of inventory products
echo '<table class="tcenter">
      <tr>
        <th>Image</th>
        <th width=10%><a href="inventory.php?sort=pid">Product ID</a></th>
        <th width=15%><a href="inventory.php?sort=name">Name</a></th>
        <th width=15%>Description</th>
        <th><a href="inventory.php?sort=type">Type</a></th>
        <th><a href="inventory.php?sort=col">Color</a></th>
        <th><a href="inventory.php?sort=p">Price</a></th>
        <th width=7%></th>
        <th width=7%></th>
      </tr>';
while($re = mysqli_fetch_array($rs, MYSQLI_BOTH)) {
    echo "<tr><td><img height='100px;' width='300px;' src='/Storefront/img/{$re['image']}'</td>";
    echo "<td>"     . $re['product_id']   . "</td>";
    echo "<td>"     . $re['product_name']  . "</td>";
    echo "<td>"     . $re['description']  . "</td>";
    echo "<td>"     . $re['product_type']  . "</td>";
    echo "<td>"     . $re['product_color']  . "</td>";
    echo "<td>$"    . $re['product_price']  . "</td>";
    echo "<td><a href='edit_product.php?id=" . $re['product_id'] . "'>Edit</a></td>";          //Edit product link
    echo "<td><a href='delete_product.php?id=" . $re['product_id'] . "'>Delete</a></td></tr>"; //Delete product link
}
echo "</table>";
echo "</div>";

mysqli_close($link);

//Pagination
if ($pages > 1) {
    echo '<div class="text-center">';
    echo '<br /><p>';

    $current_page = ($start/$display) + 1;
    //If it's not the first page, make a previous button:
    if ($current_page != 1) {
        echo '<a href="inventory.php?s=' . ($start - $display) . '&p=' . $pages . '&sort=' . $sort . '">Previous</a> ';
    }
    //Make all the numbered pages:
    for ($i = 1; $i <= $pages; $i++) {
        if ($i != $current_page) {
            echo '<a href="inventory.php?s=' . (($display * ($i - 1))) . '&p=' . $pages . '&sort=' . $sort . '">' . $i . '</a> ';
        } else {
            echo $i . ' ';
        }
    }
    //If it's not the last page, make a next button:
    if ($current_page != $pages) {
        echo '<a href="inventory.php?s=' . ($start + $display) . '&p=' . $pages . '&sort=' . $sort . '">Next</a>';
    }

    echo '</p>';
    echo '</div>';
}
?>

</body>
</html>