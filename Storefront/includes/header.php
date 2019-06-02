<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet">
    <link href="/Storefront/stylesheet.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            
            <a class="navbar-brand" href="/Storefront/home.php">
                <img id="logo" src="/Storefront/img/logo.png" style="position:relative; top:-15px;">
            </a>
            
            <ul class="nav navbar-nav navbar-right">
                <?php
                if (isset($_SESSION['loggedin'])) {
                    if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
                        echo "<li><a href='/Storefront/account.php'><span class='glyphicon glyphicon-user'></span> Account</a></li>";
                    } else {
                         echo "<li><a href='/Storefront/admin/admin.php'><span class='glyphicon glyphicon-user'></span> Admin</a></li>";
                    }
                    echo "<li><a href='/Storefront/logout.php'><span class='glyphicon glyphicon-log-out'></span> Log Out</a></li>";
                } else {
                    echo "<li><a href='/Storefront/register.php'><span class='glyphicon glyphicon-user'></span> Register</a></li>";
                    echo "<li><a href='/Storefront/login.php'><span class='glyphicon glyphicon-log-in'></span> Log In</a></li>";
                }
                ?>
                <li><a href="/Storefront/view_cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> Cart<span class="badge badge-light"><?php echo (isset($_SESSION['cart'])) ? count($_SESSION['cart']) : 0; ?></span></a></li>
            </ul>
        </div>
    </nav>    