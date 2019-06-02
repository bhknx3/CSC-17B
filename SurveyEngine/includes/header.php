<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet">
    <link href="/SurveyEngine/stylesheet.css" rel="stylesheet">
    <script type="text/javascript" src="/SurveyEngine/scripts/survey.js"></script>
</head>

<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <ul class="nav navbar-nav">
                <li class="active"><a href="/SurveyEngine/home.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php
                if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
                    echo "<li><a href='/SurveyEngine/register.php'><span class='glyphicon glyphicon-user'></span> Register</a></li>";
                    echo "<li><a href='/SurveyEngine/login.php'><span class='glyphicon glyphicon-log-in'></span> Log in</a></li>";
                } else {
                    if (isset($_SESSION['admin']) && $_SESSION['admin'] == true) {
                        echo "<li><a href='/SurveyEngine/admin/admin.php'><span class='glyphicon glyphicon-user'></span> Account</a></li>";
                    } else {
                        echo "<li><a href='/SurveyEngine/account.php'><span class='glyphicon glyphicon-user'></span> Account</a></li>";
                    }
                    echo "<li><a href='/SurveyEngine/logout.php'><span class='glyphicon glyphicon-log-out'></span> Log Out</a></li>";
                }
                ?>
            </ul>
        </div>
    </nav>