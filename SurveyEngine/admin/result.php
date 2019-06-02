<?php
$page_title = 'Survey Results';
include ('../includes/header.php');
require_once ('../config.php');

// Use GET to get the survey id
if (!isset($_GET['id'])) {
    echo '<h3 class="error text-center">Survey results not found</h3>';
    exit;
} else {
    $surveyID = $_GET["id"];
}

$query = "SELECT `entity_survey`.`title`, `entity_survey`.`description`, `entity_question`.`question` FROM `survey`.`xref_survey_question` AS `xref_survey_question`, `survey`.`entity_survey` AS `entity_survey`, `survey`.`entity_question` AS `entity_question` WHERE `xref_survey_question`.`survey_id` = `entity_survey`.`survey_id` AND `xref_survey_question`.`question_id` = `entity_question`.`question_id` AND `entity_survey`.`unique_id` = '$surveyID'";
$qstnRS = mysqli_query($link, $query);
$numQstn = mysqli_num_rows($qstnRS);

//If survey result page is valid
if ($numQstn > 0) {
    mysqli_data_seek($qstnRS, 0);   //Seek data
    $row = $qstnRS->fetch_assoc();  //Fetch data
    $title = $row['title'];         //Get survey title
    $desc = $row['description'];    //Get survey description
}
else {
    $error = "No results found.";
}
?>

<div class="page-header">
    <h1>Survey Results</h1>
</div>

<div class="text-center" id="result">
    <?php
    if ($numQstn > 0) {
        //Header for page 
        echo "<h3>Title: " . $title . "</h3>";
        echo "<h4>Description: " . $desc . "</h4><br><br>";
        
        //Table headers
        echo "<table border='0' align='center'>";
        echo "<tr><th>" . 'Question' . "</th>";
        echo "<th>"     . 'Answer'   . "</th>";
        echo "<th>"     . 'Count'    . "</th></tr>";

        //Output question, answer, and tally for each answer from users
        //For each question
        for ($i=0; $i<$numQstn; $i++) {
            mysqli_data_seek($qstnRS, $i);  //Seek data
            $rowQ = $qstnRS->fetch_assoc(); //Fetch data
            $qstn = $rowQ['question'];      //Get question from row

            //Output question
            echo "<tr><td>" . $qstn . "</td>";

            //Query answer for the question
            $query = "SELECT `entity_survey`.`unique_id`, `entity_question`.`question`, `entity_answer`.`answer` FROM `survey`.`xref_survey_question` AS `xref_survey_question`, `survey`.`entity_survey` AS `entity_survey`, `survey`.`entity_question` AS `entity_question`, `survey`.`xref_question_answer` AS `xref_question_answer`, `survey`.`entity_answer` AS `entity_answer` WHERE `xref_survey_question`.`survey_id` = `entity_survey`.`survey_id` AND `xref_survey_question`.`question_id` = `entity_question`.`question_id` AND `xref_question_answer`.`question_id` = `entity_question`.`question_id` AND `xref_question_answer`.`answer_id` = `entity_answer`.`answer_id` AND `entity_survey`.`unique_id` = '$surveyID' AND `entity_question`.`question` = '$qstn'";
            $ansRS = mysqli_query($link, $query);
            $numAns = mysqli_num_rows($ansRS);

            //For each answer
            for ($j=0; $j<$numAns; $j++) {
                if ($j>0) { echo "<td></td>"; } //Output nothing in table for the question column

                mysqli_data_seek($ansRS, $j);   //Seek data
                $rowA = $ansRS->fetch_assoc();  //Fetch data
                $answer = $rowA['answer'];      //Get answer from row

                //Output answer
                echo "<td>" . $answer . "</td>";

                $query = "SELECT `entity_survey`.`unique_id`, `entity_user`.`username`, `entity_question`.`question`, `entity_answer`.`answer` FROM `survey`.`xref_answer_user` AS `xref_answer_user`, `survey`.`entity_user` AS `entity_user`, `survey`.`entity_answer` AS `entity_answer`, `survey`.`xref_question_answer` AS `xref_question_answer`, `survey`.`entity_question` AS `entity_question`, `survey`.`xref_survey_question` AS `xref_survey_question`, `survey`.`entity_survey` AS `entity_survey`, `survey`.`xref_user_survey` AS `xref_user_survey` WHERE `xref_answer_user`.`user_id` = `entity_user`.`user_id` AND `xref_answer_user`.`answer_id` = `entity_answer`.`answer_id` AND `xref_question_answer`.`question_id` = `entity_question`.`question_id` AND `xref_question_answer`.`answer_id` = `entity_answer`.`answer_id` AND `xref_survey_question`.`survey_id` = `entity_survey`.`survey_id` AND `xref_survey_question`.`question_id` = `entity_question`.`question_id` AND `xref_user_survey`.`survey_id` = `entity_survey`.`survey_id` AND `xref_user_survey`.`user_id` = `entity_user`.`user_id` AND `entity_survey`.`unique_id` = '$surveyID' AND `entity_question`.`question` = '$qstn' AND `entity_answer`.`answer` = '$answer'";
                $countRS = mysqli_query($link, $query);
                $count = mysqli_num_rows($countRS);

                //Output count for answer
                echo "<td>" . $count . "</td></tr>";
 
            }
            echo "<tr><td></td><td></td><td></td></tr>";
        }
        echo "</table>";
    } else {
        echo "<div id='error'>" . $error . "</div>";
    }
    ?>
</div>

</body>
</html>