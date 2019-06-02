<?php
$page_title = 'View Users';
include ('../includes/header.php');
require ('../config.php');   //Connect DB
?>

<div class="page-header">
    <h1><b>List of Users</b></h1>
</div>

<?php
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

// Determine where in the database to start returning results...
if (isset($_GET['s']) && is_numeric($_GET['s'])) {
    $start = $_GET['s'];
} else {
    $start = 0;
}

//Determine the sort
//Default is by registration date
$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'rd';

//Determine the sorting order
switch ($sort) {
    case 'un':
        $order_by = 'username ASC';
        break;
    case 'rd':
        $order_by = 'registration_date ASC';
        break;
    default:
        $order_by = 'registration_date ASC';
        $sort = 'rd';
        break;
}

// Define the query:
$q = "SELECT `user_id`, `username`, `registration_date` FROM `survey`.`entity_user` AS `entity_user` ORDER BY $order_by LIMIT $start, $display";
$r = mysqli_query($link, $q); //Run the query

//Table header:
echo   '<table align="center" style="width:30%;">
        <tr>
            <th width=5%></td>
            <th width=5%><b><a href="view_user.php?sort=un">Username</a></b></td>
            <th width=5%><b><a href="view_user.php?sort=rd">Date Registered</a></b></td>
        </tr>';

// Fetch and print all the records....
while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
    echo   '<tr>
                <td><a href="delete_user.php?id=' . $row['user_id'] . '">Delete</a></td>
                <td>' . $row['username'] . '</td>
                <td>' . $row['registration_date'] . '</td>
            </tr>';
}
echo '</table>';

mysqli_free_result ($r);
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