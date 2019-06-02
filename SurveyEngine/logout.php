<?php
//Initialize session
session_start();
 
//Unset all session variables
$_SESSION = array();
 
//Destroy session
session_destroy();
 
//Redirect
header("location: home.php");
exit;
?>