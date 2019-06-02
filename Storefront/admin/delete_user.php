<?php
$page_title = 'Delete User';
include ('../includes/header.php');
require ('../config.php');
echo '<div class="page-header"><h2><b>Delete User</b></h2></div>';

//Check admin status
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo '<h4 class="error text-center">Error: Access denied</h4>';
    exit;
}

//Check for a valid user ID, through GET or POST
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) {
    $id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) {
    $id = $_POST['id'];
} else {
    echo '<h4 class="error text-center">Error: Unable to find user</h4>';
    exit;
}

//Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['sure'] == 'Yes') {
        //Delete xrefs with foreign keys first
        $q = "DELETE FROM xref_user_order WHERE user_id = $id";
        mysqli_query($link, $q);
        
        //Delete user
        $q2 = "DELETE FROM entity_user WHERE user_id = $id LIMIT 1";
        $r = mysqli_query ($link, $q2);
        
        //If deletion is successful
        if (mysqli_affected_rows($link) == 1) {
            echo '<div class="text-center">
                  <p>The user has been deleted.</p>
                  <a href="view_user.php" class="btn btn-secondary">Go back</button>
                  </div>';	
        } else {
            echo '<div class="text-center">
                  <p class="error">The user could not be deleted due to a system error.</p>
                  <p>' . mysqli_error($link) . '<br />Query: ' . $q . '</p>
                  <a href="view_user.php" class="btn btn-secondary">Go back</button>
                  </div>';
        }
    } else {
        echo '<div class="text-center">
              <p>The user has NOT been deleted.</p>
              <a href="view_user.php" class="btn btn-secondary">Go back</button>
              </div>';
    }
} 
//Show form
else {
    //Retrieve the user's information
    $query = "SELECT * FROM entity_user WHERE user_id= '$id'";
    $rs = mysqli_query ($link, $query);

    //If query is successful
    if (mysqli_num_rows($rs) == 1) {

        // Get the user's information:
        $row = mysqli_fetch_array($rs, MYSQLI_NUM);
        
        $accType = ($row[6] == 0) ? "Admin" : "User";   //Get user account type

        // Display the record being deleted:
        echo   "<div class='text-center'>
                <h3>Email: $row[1]</h3>
                <h3>Name: $row[3] $row[4]</h3>
                <h3>Account type: $accType</h3>
                </div>";
                
        // Create the form:
        echo   '<div class="form">
                <p>Are you sure you want to delete this user?</p>
                <form action="delete_user.php" method="post">
                    <input type="radio" name="sure" value="Yes" /> Yes
                    <input type="radio" name="sure" value="No" style="margin-left:10px;" checked="checked" /> No<br><br>
                    <input type="submit" class="center" name="submit" value="Submit" />
                    <input type="hidden" name="id" value="' . $id . '" />
                </form>
                </div>';
        
        echo '<div class="text-center">
                <a href="view_user.php" class="btn btn-secondary">Go back to Users</a>
              </div>';

    } else { //Not a valid user ID
        echo '<p class="error">This page has been accessed in error.</p>';
    }
}

mysqli_close($link);
?>