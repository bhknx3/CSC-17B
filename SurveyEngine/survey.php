<?php
//This file needs survey.js (located in header)
$page_title = 'Take Survey';
include ('./includes/header.php');
require_once ('./config.php');

if (!isset($_GET['id'])) {
    echo '<h2 class="error text-center">Error: Survey not found</h2>';
    exit;
} elseif (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    $_SESSION["redirect_to"] = $_GET['id'];
    header("location: login.php");
    exit;
} else {
    $id = $_GET["id"];
    $_SESSION["surveyID"] = $id;
}

//Query the survey id
$query = "SELECT `unique_id`, `title`, `description` FROM `survey`.`entity_survey` AS `entity_survey` WHERE `unique_id` = '$id'";
$rs = mysqli_query($link, $query);

//Check if the query returned a row or if it nothing exists
if (mysqli_num_rows($rs) > 0) {
    $row = $rs->fetch_assoc();
    $title = $row['title'];
    $desc = $row['description'];
}
else {
    echo '<h2 class="error text-center">Error: Survey not found</h2>';
    exit;
}
?>

<h2 class="page-header">Survey</h2>

<form id="survey" action="submit.php" method="post">
    <span id="error"></span>
    <?php
    //Output survey title and description
    echo "<h2 id='title'>" . $title . "</h2><br>";
    echo "<p id='desc'>" . $desc . "</p>";

    //Get questions
    $query = "SELECT `entity_question`.`question_id`, `entity_question`.`question` FROM `survey`.`xref_survey_question` AS `xref_survey_question`, `survey`.`entity_survey` AS `entity_survey`, `survey`.`entity_question` AS `entity_question` WHERE `xref_survey_question`.`survey_id` = `entity_survey`.`survey_id` AND `xref_survey_question`.`question_id` = `entity_question`.`question_id` AND `entity_survey`.`unique_id` = '$id'";
    $qstnRS = mysqli_query($link, $query);  //Question result set
    $numQstn = mysqli_num_rows($qstnRS);    //Get number of questions

    //For each question
    echo "<hr>";

    for ($i=0; $i<$numQstn; $i++) {
        $num = $i + 1;  //Counter
        
        //Output question
        mysqli_data_seek($qstnRS, $i);  //Seek row
        $row = $qstnRS->fetch_assoc();  //Fetch row
        echo "<div class='qstn'> <p id='q$num'>" . $row["question"] . "<br><br>";

        //Output answers to question
        $qstnID = $row["question_id"];
        $ansQuery = "SELECT `entity_answer`.`answer`, `entity_question`.`question_id`, `entity_answer`.`answer_id` FROM `survey`.`xref_question_answer` AS `xref_question_answer`, `survey`.`entity_question` AS `entity_question`, `survey`.`entity_answer` AS `entity_answer` WHERE `xref_question_answer`.`question_id` = `entity_question`.`question_id` AND `xref_question_answer`.`answer_id` = `entity_answer`.`answer_id` AND `entity_question`.`question_id` = $qstnID";
        $ansRS = mysqli_query($link, $ansQuery);
        $numAns = mysqli_num_rows($ansRS);

        //For each answer
        for ($ans=0; $ans<$numAns; $ans++) {
            $cntrAns = $ans + 1;
            mysqli_data_seek($ansRS, $ans);  //Seek row
            $rowA = $ansRS->fetch_assoc(); //Fetch row
            $ansID = $rowA["answer_id"];
            echo "<input type='radio' name=q" . $qstnID . " id='q". $qstnID ."a". $ansID ."' value='" . $ansID . "'></input>
                  <label for='q". $qstnID ."a". $ansID ."'>" . $rowA["answer"] . "</label><br>";
        }
        echo "</div>";       
        echo "<hr>";
    }
    echo "</div>";
    echo '<button onclick="validForm()" type="button">Submit</button>';
    ?>
</form>

</body>
</html>