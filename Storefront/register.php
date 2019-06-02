<?php
$page_title = 'Register';
include ('includes/header.php');
require_once "config.php";

//Define variables and initialize with empty values
$email = $fn = $ln = $password = $confirm_password = "";
$email_err = $fn_err = $ln_err = $password_err = $confirm_password_err = "";

//Email regex validation
function validate_email($email) {
    if ( !preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email) ) {
        return false;
    }
    return true;
}
//Password regex must be 6-20 characters including: a digit, a lower case char, a upper case char, a special symbol
function validate_password($pass) {
    if ( !preg_match("/^((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{6,20})$/", $pass) ) {
        return false;
    }
    return true;
}
 
//Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    //Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email address.";
    } elseif (!validate_email(trim($_POST["email"]))) {
        $email_err = "Must be a valid email address";
    } else {
        //Prepare a select statement
        $sql = "SELECT email FROM entity_user WHERE email = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            //Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            //Set parameters
            $param_email = trim($_POST["email"]);
            
            //Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                //Store result
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email is already used.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
            
            //Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    //Validate first name
    if (empty(trim($_POST["first_name"]))) {
        $fn_err = "Please enter a first name.";
    } else {
        $fn = trim($_POST["first_name"]);
    }
    
    //Validate last name
    if (empty(trim($_POST["last_name"]))) {
        $ln_err = "Please enter a last name.";
    } else {
        $ln = trim($_POST["last_name"]);
    }
    
    //Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";     
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } elseif (!validate_password(trim($_POST["password"]))) {
        $password_err = "Password must have each of the following: digit, lowercase character, uppercase character, and a special symbol.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    //Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";     
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password does not match.";
        }
    }
    
    //Check input errors before inserting in database
    if (empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($fn_err) && empty($ln_err)) {
        $user = 1;  //Regular user type code
        // Prepare an insert statement
        $sql = "INSERT INTO entity_user (email, password, first_name, last_name, role) VALUES (?, ?, ?, ?, $user)";
         
        if ($stmt = mysqli_prepare($link, $sql)) {
            //Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_email, $param_password, $param_fn, $param_ln);
            
            //Set parameters
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_fn = $fn;
            $param_ln = $ln;
            
            //Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                //Redirect to login page
                header("location: login.php");
            } else {
                echo "Something went wrong. Please try again later.";
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
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="container">
            <h2>Register</h2>
            <p>Please enter the following information to create an account</p>
            <hr>

            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>" placeholder="Enter email here">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($fn_err)) ? 'has-error' : ''; ?>">
                <label>First Name</label>
                <input type="text" name="first_name" class="form-control" value="<?php echo $fn; ?>" placeholder="Enter first name here">
                <span class="help-block"><?php echo $fn_err; ?></span>
            </div>    
            
            <div class="form-group <?php echo (!empty($ln_err)) ? 'has-error' : ''; ?>">
                <label>Last Name</label>
                <input type="text" name="last_name" class="form-control" value="<?php echo $ln; ?>" placeholder="Enter last name here">
                <span class="help-block"><?php echo $ln_err; ?></span>
            </div>  

            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>" placeholder="Enter password here">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>" placeholder="Repeat password here">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>

            <div class="form-group">
                <br><button type="submit">Register</button>
            </div>
        </div>
    </form>
</div>

</body>
</html>