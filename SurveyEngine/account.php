<?php
$page_title = 'Account';
include ('./includes/header.php');
require_once ('./config.php');

//Check if user or admin, redirect accordingly
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
} elseif (isset($_SESSION["admin"]) && $_SESSION["admin"] === true) {
    header("location: admin.php");
    exit;
} else {
    $userID = $_SESSION["id"];  //Get user ID
}
?>

<style type="text/css">
    body {text-align: center;}
</style>

<div class="page-header">
    <h1>Welcome, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></h1>
</div>

<div class="text-center">
    <h3>Surveys taken:</h3>
    <?php
    //Surveys taken
    $query = "SELECT `entity_survey`.`title`, `entity_survey`.`description` FROM `survey`.`xref_user_survey` AS `xref_user_survey`, `survey`.`entity_survey` AS `entity_survey`, `survey`.`entity_user` AS `entity_user` WHERE `xref_user_survey`.`survey_id` = `entity_survey`.`survey_id` AND `xref_user_survey`.`user_id` = `entity_user`.`user_id` AND `entity_user`.`user_id` = '$userID'";
    $rs = mysqli_query($link, $query);  //Get result set
    $numSurvey = mysqli_num_rows($rs);  //Get number of surveys taken

    if ($numSurvey > 0) {
        echo "<table border='1' align='center'>";
        echo "<tr><th>".'Survey Title'."</th>";
        echo "<th>".'Description'."</th></tr>";
        while($re = mysqli_fetch_array($rs, MYSQLI_BOTH)){
            echo "<tr><td>".$re['title']."</td>";
            echo "<td>".$re['description']."</td></tr>";
        }
        echo "</table>";   
    } else {
        echo "No surveys found";
    }
    ?>

    <div class="settings">
        <h3><span class="glyphicon glyphicon-cog"></span> Settings</h3>
        <a href="reset_password.php" class="btn btn-warning">Reset password</a>
    </div>
</div>

</body>
</html>