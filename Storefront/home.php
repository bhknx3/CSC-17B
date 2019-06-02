<?php
$page_title = 'Home';
include ('includes/header.php');
require_once "config.php";

//Number of records to show per page
$display = 6;

//Determine how many pages there are
if (isset($_GET['p']) && is_numeric($_GET['p'])) { //Already on a page
    $pages = $_GET['p'];
} else {    //Need to determine pages
    //Count number of records
    if (isset($_GET['cat'])) {
        $cat = $_GET['cat'];
        $q = "SELECT COUNT(product_id) FROM entity_product WHERE type = '$cat'";
    } else {
        $q = "SELECT COUNT(product_id) FROM entity_product";
    }

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

//Determine the sort
//Default is by registration date
$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'rd';

// Determine the sorting order:
switch ($sort) {
    case 'plo':
        $order_by = 'product_price ASC';
        break;
    case 'phi':
        $order_by = 'product_price DESC';
        break;
    default:
        $order_by = 'type ASC';
        $sort = 'type';
        break;
}
?>

<div class="container-fluid home">
    <div class="jumbotron">
        <a href="home.php"><img class="center img-responsive" src="./img/banner.png"></a>
    </div>         
  
    <div class="col-sm-2 sidenav well">
        <h4>Categories</h4>
        <ul class="nav nav-stacked">
            <li><a style="color:black;" href="home.php">All</a></li>
            <?php
            //Get product type categories
            $qCat = "SELECT * FROM `storefront`.`enum_product_type` AS `enum_product_type`";
            $rsCat = mysqli_query($link, $qCat);
            while($re = mysqli_fetch_array($rsCat, MYSQLI_BOTH)) {
                echo '<li><a style="color: black;" href="home.php?cat=' . $re['id'] . '">' . $re['product_type'] . '</a></li>';
            }
            ?>
        </ul>
        <br>
        <h4>Sort by</h4>
        <ul class="nav nav-stacked">
            <?php
            if (isset($_GET['cat'])) {  //Category and price
                echo '<li><a style="color:black;" href="home.php?sort=plo&cat=' . $_GET['cat'] . '">Price low to high</a></li>';
                echo '<li><a style="color:black;" href="home.php?sort=phi&cat=' . $_GET['cat'] . '">Price high to low</a></li>';
            } else {                    //Only price
                echo '<li><a style="color:black;" href="home.php?sort=plo">Price low to high</a></li>';
                echo '<li><a style="color:black;" href="home.php?sort=phi">Price high to low </a></li>';
            }
            ?>
        </ul>
    </div>
            
    <div class="col-sm-10">
    <?php
        if (isset($_GET['cat'])) {
            $cat = $_GET['cat'];
            $query = "SELECT `product_id`, `product_name`, `product_price`, `image`, `type` FROM `storefront`.`entity_product` AS `entity_product` WHERE `type` = '$cat' ORDER BY $order_by LIMIT $start, $display";
        } else {
            $query = "SELECT `product_id`, `product_name`, `product_price`, `image` FROM `storefront`.`entity_product` AS `entity_product` ORDER BY $order_by LIMIT $start, $display";
        }
        $rs = mysqli_query($link, $query);

        $counter = 0;
        $itemPerRow = 3;
        if (mysqli_num_rows($rs) == 0) {
            echo '<p style="text-align:center; padding-top:100px;">No items found. Check again later.</p>';
        } else {
            echo '<div class="row">';
            while ($re = mysqli_fetch_array($rs,MYSQLI_BOTH)) {
                if ($counter%$itemPerRow == 0) {
                    echo '</div><div class="row">';
                }
                echo '<div class="col-sm-4">';
                echo '<div class="panel panel-default">';
                echo '<div class="panel-body"><a href="product.php?id=' . $re['product_id'] . '"><img src="./img/' . $re['image'] . '" class="img-responsive" style="width:100%" alt="Image"></div>';
                echo '<div class="panel-footer text-center">';
                echo '<p>' . $re['product_name'] . '</p></a>';
                echo '<p>$' . $re['product_price'] . '</p>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                $counter++;
            }
            echo '</div>';
        }
    ?>
    </div>
</div>

<?php
//Pagination
if ($pages > 1) {
    echo '<div class="text-center">';
    echo '<br><p>';

    $current_page = ($start/$display) + 1;
    //If it's not the first page, make a previous button
    if ($current_page != 1) {
        echo '<a href="home.php?s=' . ($start - $display) . '&p=' . $pages . '&sort=' . $sort . '">Previous</a> ';
    }
    //Make all the numbered pages
    for ($i = 1; $i <= $pages; $i++) {
        if ($i != $current_page) {
            echo '<a href="home.php?s=' . (($display * ($i - 1))) . '&p=' . $pages . '&sort=' . $sort . '">' . $i . '</a> ';
        } else {
            echo $i . ' ';
        }
    }
    //If it's not the last page, make a next button
    if ($current_page != $pages) {
        echo '<a href="home.php?s=' . ($start + $display) . '&p=' . $pages . '&sort=' . $sort . '">Next</a>';
    }

    echo '</p>';
    echo '</div>';	
}

include('includes/footer.html');
?>