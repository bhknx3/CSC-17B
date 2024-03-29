<?php
$page_title = 'Login';
include ('includes/header.php');
require_once "config.php";

//Check if user is already logged in, if so, redirect to account page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    //Check if user is an admin or not
    if (isset($_SESSION["admin"]) && $_SESSION["admin"] === true) {
        header("location: /Storefront/admin/admin.php");
    } else {
        header("location: account.php");
    }
    exit;
}
 
//Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = "";
 
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Check if email is empty
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter email.";
    } else {
        $email = trim($_POST["email"]);
    }
    
    //Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    //Validate credentials
    if (empty($email_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT user_id, email, password, role, first_name FROM entity_user WHERE email = ?";
        
        if ($stmt = mysqli_prepare($link, $sql)) {
            //Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            //Set parameters
            $param_email = $email;
            
            //Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                //Store result
                mysqli_stmt_store_result($stmt);
                
                //Check if email exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    //Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password, $role, $fn);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            //Password is correct, so start a new session
                            session_start();
                            
                            //Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["firstname"] = $fn;
                            
                            if ($role == 0) {   //If admin
                                $_SESSION["admin"] = true;
                                $_SESSION["firstname"] = "admin";
                                //Redirect to admin page
                                header("location: /Storefront/admin/admin.php");
                            } else {           //If regular user
                                $_SESSION["admin"] = false;
                                // Redirect user to welcome page
                                header("location: /Storefront/account.php");
                            }
                        } else {
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    // Display an error message if email doesn't exist
                    $email_err = "No account found with that email.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            //Close statement
            mysqli_stmt_close($stmt);
        }
    }
    //Close connection
    mysqli_close($link);
}
?>
    
<div id="accountForm">
    <form action="" method="post">
        <div class="container">
            <h2>Login</h2>
            <hr>

            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>" >
                <label>Email</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>" placeholder="Enter Email">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>    

            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>" >
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter Password">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>

            <p>No account? <a href="./register.php">Register here.</a></p>

            <div class="form-group" style="padding-top:25px">
                <button type="submit">Login</button>
            </div>
        </div>
    </form>
</div>

</body>
</html>