<?php
$page_title = 'View Users';
include ('../includes/header.php');
require ('../config.php');   //Connect DB
echo '<div class="page-header"><h2><b>User List</b></h2></div>';

//Check admin status
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo '<h4 class="error text-center">Error: Access denied</h4>';
    exit;
}

//Number of records to show per page
$display = 5;

//Determine how many pages there are
if (isset($_GET['p']) && is_numeric($_GET['p'])) { // Already been determined.
    $pages = $_GET['p'];
} else {    //Need to determine pages
    //Count number of records
    $q = "SELECT COUNT(user_id) FROM entity_user";
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
$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'rd';

//Determine sort
switch ($sort) {
    case 'e':
        $order_by = 'email ASC';
        break;
    case 'fn':
        $order_by = 'first_name ASC';
        break;
    case 'ln':
        $order_by = 'last_name ASC';
        break;
    case 'rd':
        $order_by = 'registration_date ASC';
        break;
    default:
        $order_by = 'registration_date ASC';
        $sort = 'rd';
        break;
}

//Query
$q = "SELECT `user_id`, `email`, `first_name`, `last_name`, `registration_date`, `role` FROM `storefront`.`entity_user` AS `entity_user` WHERE `role` = 1 ORDER BY $order_by LIMIT $start, $display";
$r = mysqli_query ($link, $q); //Run the query

//Table header
echo   '<table align="center" cellspacing="0" cellpadding="5" width="75%">
        <tr>
            <td width="7%"></td>
            <td width="7%"></td>
            <td width="30%"><b><a href="view_user.php?sort=e">Email</a></b></td>
            <td><b><a href="view_user.php?sort=fn">First Name</a></b></td>
            <td><b><a href="view_user.php?sort=ln">Last Name</a></b></td>
            <td width="30%"><b><a href="view_user.php?sort=rd">Date Registered</a></b></td>
        </tr>';

//Fetch and print all records
while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
    echo   '<tr>
                <td><a href="edit_user.php?id=' . $row['user_id'] . '">Edit</a></td>
                <td><a href="delete_user.php?id=' . $row['user_id'] . '">Delete</a></td>
                <td>' . $row['email'] . '</td>
                <td>' . $row['first_name'] . '</td>
                <td>' . $row['last_name'] . '</td>
                <td>' . $row['registration_date'] . '</td>
            </tr>';
}
echo '</table>';

mysqli_free_result($r);
mysqli_close($link);

//Pagination
if ($pages > 1) {
    echo '<div class="text-center">';
    echo '<br /><p>';

    $current_page = ($start/$display) + 1;
    //If it's not the first page, make a previous button:
    if ($current_page != 1) {
        echo '<a href="view_user.php?s=' . ($start - $display) . '&p=' . $pages . '&sort=' . $sort . '">Previous</a> ';
    }
    //Make all the numbered pages:
    for ($i = 1; $i <= $pages; $i++) {
        if ($i != $current_page) {
            echo '<a href="view_user.php?s=' . (($display * ($i - 1))) . '&p=' . $pages . '&sort=' . $sort . '">' . $i . '</a> ';
        } else {
            echo $i . ' ';
        }
    }
    //If it's not the last page, make a next button:
    if ($current_page != $pages) {
        echo '<a href="view_user.php?s=' . ($start + $display) . '&p=' . $pages . '&sort=' . $sort . '">Next</a>';
    }

    echo '</p>';
    echo '</div>';
}
?>