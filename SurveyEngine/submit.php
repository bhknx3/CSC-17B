<?php
$page_title = 'Survey Submission';
include ('./includes/header.php');
require_once ('./config.php');

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$userID = $_SESSION["id"];          //Store user ID
$survID = $_SESSION["surveyID"];    //Store survey ID
    
//Query server to see if user has already taken survey or not
$query = "SELECT `entity_user`.`user_id`, `entity_survey`.`unique_id` FROM `survey`.`xref_user_survey` AS `xref_user_survey`, `survey`.`entity_survey` AS `entity_survey`, `survey`.`entity_user` AS `entity_user` WHERE `xref_user_survey`.`survey_id` = `entity_survey`.`survey_id` AND `xref_user_survey`.`user_id` = `entity_user`.`user_id` AND `entity_user`.`user_id` = '$userID' AND `entity_survey`.`unique_id` = '$survID'";
$rs = mysqli_query($link, $query);  //Result set to see if user is connected with this particular survey
$nRows = mysqli_num_rows($rs);      //Number of rows from query should return 0 if survey hasn't been taken

//If user has already taken survey, create error message
if ($nRows > 0) {
    $error = "You have already taken this survey.";
    echo "<h3 class='error text-center'>" . $error . "</h3>";
    exit;
}
//Otherwise, if post method was utilized, read form results
else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Query survey PID, question_id
    $query = "SELECT `entity_survey`.`survey_id`, `entity_question`.`question_id`, `entity_question`.`question` FROM `survey`.`xref_survey_question` AS `xref_survey_question`, `survey`.`entity_survey` AS `entity_survey`, `survey`.`entity_question` AS `entity_question` WHERE `xref_survey_question`.`survey_id` = `entity_survey`.`survey_id` AND `xref_survey_question`.`question_id` = `entity_question`.`question_id` AND `entity_survey`.`unique_id` = '$survID'";
    $qstnRS = mysqli_query($link, $query);  //Question result set
    $numQstn = mysqli_num_rows($qstnRS);    //Get number of questions
    
    //Get survey primary key ID
    mysqli_data_seek($qstnRS, 0);   //Seek data
    $row = $qstnRS->fetch_assoc();  //Fetch data
    $survPID = $row['survey_id'];   //Get survey_id primary key to put in xref
 
    //Get answer to each question and input into database using queries
    for ($i=0; $i<$numQstn; $i++) {
        mysqli_data_seek($qstnRS, $i);  //Seek data
        $row = $qstnRS->fetch_assoc();  //Fetch data
        $qstnID = $row["question_id"];  //Get question id (Name value for answers for question in previous form)
        $ansID = $_POST['q'.$qstnID];   //Get answer id value number specified in previous form
        
        //Input entry into xref_answer_user
        $query = "INSERT INTO `xref_answer_user` (answer_id, user_id) VALUES ('$ansID', '$userID')";
        mysqli_query($link, $query);
    }
    
    //Input entry into xref_user_survey
    $query = "INSERT INTO `xref_user_survey` (user_id, survey_id) VALUES ('$userID', '$survPID')";
    mysqli_query($link, $query);
}
?>

<div class="text-center">
    <h3>Your survey has been submitted.</h3>
    <h3>Thank you!</h3>
</div>

</body>
</html>