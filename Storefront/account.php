<?php
$page_title = 'Account';
include ('./includes/header.php');
require_once "./config.php";

if(!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>

<div class="page-header">
    <h1>Welcome, <b><?php echo htmlspecialchars($_SESSION["firstname"]); ?></b></h1>
</div>

<div class="text-center">
    <a href="view_order.php" style="width:150px;" class="btn btn-primary">View Orders</a><br><br>

    <div class="settings">
        <h3><span class="glyphicon glyphicon-cog"></span> Settings</h3>
        <a href="/Storefront/reset_password.php" class="btn btn-warning">Reset password</a>
    </div>
</div>