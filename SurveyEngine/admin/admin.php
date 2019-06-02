<SCRIPT LANGUAGE="JavaScript">
    function copyToClipboard(text) {
        window.prompt("Copy to clipboard: Ctrl+C, Enter", text);
    }
</SCRIPT>

<?php
$page_title = 'Admin Page';
include ('../includes/header.php');
require ('../config.php');   //Connect DB

//If not an admin, redirect to user home page
if(!isset($_SESSION["admin"]) && $_SESSION["admin"] !== true) {
    header("location: /SurveyEngine/home.php");
    exit;
} else {
    $adminID = $_SESSION["id"];  //Get user ID
}
?>
    
<div class="page-header">
    <h1><b>Admin Panel</b></h1>
</div>

<div class="text-center">
    <a href="create.html" style="width:150px;" class="btn btn-primary">Create Survey</a><br><br>
    <a href="view_user.php" style="width:150px;" class="btn btn-info">View Users</a><br><br>

    <div id="createdSurveys" style="margin-top:5%;">
        <h4>View created surveys:</h4>
        <?php
        //Survey list
        $query = "SELECT `entity_user`.`user_id`, `entity_survey`.`title`, `entity_survey`.`unique_id` FROM `survey`.`xref_user_survey` AS `xref_user_survey`, `survey`.`entity_survey` AS `entity_survey`, `survey`.`entity_user` AS `entity_user` WHERE `xref_user_survey`.`survey_id` = `entity_survey`.`survey_id` AND `xref_user_survey`.`user_id` = `entity_user`.`user_id` AND `entity_user`.`user_id` = '$adminID' AND `entity_user`.`type` = '0'";
        $rs = mysqli_query($link, $query);  //Get result set
        $numSurvey = mysqli_num_rows($rs);  //Get number of surveys created

        if ($numSurvey > 0) {
            echo "<table border='1' align='center'>";
            echo "<tr><th>".'Survey Title'."</th>";
            echo "<th>".'Link to Results'."</th>";
            echo "<th>".'Survey Invite Link'."</th>";
            echo "<th>".'Delete Survey'."</th></tr>";
            while($re = mysqli_fetch_array($rs, MYSQLI_BOTH)){
                $surveyID = $re['unique_id'];
                $string = 'http://' . $_SERVER['HTTP_HOST'] . "/SurveyEngine/survey.php?id="  . $surveyID;
                echo "<tr><td>" . $re['title'] . "</td>";
                echo "<td> <a href='./result.php?id=$surveyID'>Link</a> </td>";    
                echo "<td> <button id='reg' onclick='copyToClipboard(\"$string\")'>Copy</button> </td>";
                echo '<td> <a href="delete_survey.php?id=' . $surveyID . '">Delete</a></td></tr>';
            }
            echo "</table>";   
        }
        else {
            echo "None";
        }
        ?>
    </div>

    <div class="settings">
        <h3><span class="glyphicon glyphicon-cog"></span> Settings</h3>
        <a href="../reset_password.php" class="btn btn-warning">Reset password</a>
    </div>        
</div>

</body>
</html>