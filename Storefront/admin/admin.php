<?php
$page_title = 'Admin Panel';
include ('../includes/header.php');
require_once "../config.php";
echo '<div class="page-header"><h2><b>Admin Panel</b></h2></div>';

//Deny access to any non-admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo '<h4 class="error text-center">Error: Access denied</h4>';
    exit;
}
?>

<div class="text-center">
    <a href="inventory.php" style="width:150px;" class="btn btn-primary">View Inventory</a><br><br>
    <a href="view_user.php" style="width:150px;" class="btn btn-info">View Users</a><br><br>

    <div class="settings">
        <h3><span class="glyphicon glyphicon-cog"></span> Settings</h3>
        <a href="../reset_password.php" class="btn btn-warning">Reset password</a>
    </div>
</div>

</body>
</html>