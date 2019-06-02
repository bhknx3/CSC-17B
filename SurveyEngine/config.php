<?php
//This file contains the database access information. 
//This file also establishes a connection to MySQL and selects the database.

//Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'survey');

//Attempt to connect to MySQL database
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//Check connection
if ($link === false){
    die ("Could not connect to MySQL: " . mysqli_connect_error());
}

//Set encoding
mysqli_set_charset($link, 'utf8');
?>