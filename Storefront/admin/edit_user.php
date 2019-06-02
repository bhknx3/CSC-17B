<?php
$page_title = 'Edit User';
include ('../includes/header.php');
require ('../config.php');
echo '<div class="page-header"><h2><b>Edit User</b></h2></div>';

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
} else { //No valid ID, kill the script
    echo '<h4 class="error text-center">Error: User not found</h4>';
    exit;
}

//Check if the form has been submitted:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Array to hold errors
    $errors = array();

    //Check for a first name
    if (empty($_POST['first_name'])) {
        $errors[] = 'You forgot to enter first name.';
    } else {
        $fn = mysqli_real_escape_string($link, trim($_POST['first_name']));
    }
    //Check for a last name
    if (empty($_POST['last_name'])) {
        $errors[] = 'You forgot to enter your last name.';
    } else {
        $ln = mysqli_real_escape_string($link, trim($_POST['last_name']));
    }
    //Check for an email address
    if (empty($_POST['email'])) {
        $errors[] = 'You forgot to enter your email address.';
    } else {
        $e = mysqli_real_escape_string($link, trim($_POST['email']));
    }

    if (empty($errors)) { //No errors found
        //Test for unique email address in database
        $q = "SELECT user_id FROM entity_user WHERE email = '$e' AND user_id != $id";
        $r = mysqli_query($link, $q);
        
        if (mysqli_num_rows($r) == 0) {
            //Make query
            $q = "UPDATE entity_user SET first_name='$fn', last_name='$ln', email='$e' WHERE user_id=$id LIMIT 1";
            $r = mysqli_query($link, $q);
            if (mysqli_affected_rows($link) == 1) { //Successful edit of user details
                    echo '<p class="text-center">The user has been edited.</p>';
            } else { //Not successful
                    echo '<div class="text-center"><p class="error">The user could not be edited due to a system error. We apologize for any inconvenience.</p>'; // Public message.
                    echo '<p>' . mysqli_error($link) . '<br />Query: ' . $q . '</p></div>'; // Debugging message.
            }
        } else { //Already registered
                echo '<p class="error text-center">The email address has already been registered.</p>';
        }
    } else { //Report the errors
        echo '<div class="text-center"><p class="error">The following error(s) occurred:<br />';
        foreach ($errors as $msg) { // Print each error.
                echo " - $msg<br />\n";
        }
        echo '</p><p>Please try again.</p></div>';
    }
}

//Retrieve the users information
$q = "SELECT email, first_name, last_name FROM entity_user WHERE user_id = $id";		
$r = mysqli_query($link, $q);

//Show form
if (mysqli_num_rows($r) == 1) {
    //Get the user information
    $row = mysqli_fetch_array($r, MYSQLI_NUM);

    //Create form
    echo '<div class="form">
          <form action="edit_user.php" method="post">
            <p>Email Address: <input type="text" name="email" value="' . $row[0] . '" /></p>
            <p>First Name: <input type="text" name="first_name" value="' . $row[1] . '" /></p>
            <p>Last Name: <input type="text" name="last_name" value="' . $row[2] . '" /></p>
            <p><input type="submit" class="center" name="submit" value="Submit" /></p>
            <input type="hidden" name="id" value="' . $id . '" />
          </form>';
    
    echo '<div class="text-center">
            <a href="view_user.php" class="btn btn-secondary">Go back to Users</a>
          </div>';
} else { //Not a valid user ID
	echo '<p class="error text-center">This page has been accessed in error.</p>';
        echo '<div class="text-center">
                <a href="view_user.php" class="btn btn-secondary">Go back to Users</a>
              </div>';
}

mysqli_close($link);
?>