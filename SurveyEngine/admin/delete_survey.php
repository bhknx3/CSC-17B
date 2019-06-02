<style type="text/css">
    body {text-align: center;}
</style>

<?php
$page_title = 'Delete Survey';
include ('../includes/header.php');
require_once "../config.php";
echo '<div class="page-header"><h2><b>Delete Survey</b></h2></div>';

// Check for a valid survey unique ID, through GET or POST:
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} elseif (isset($_POST['id'])) { // Form submission.
    $id = $_POST['id'];
} else { // No valid ID, kill the script.
    echo '<p class="error">This page has been accessed in error.</p>';
    exit();
}

//Only if form has been submitted on the page
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Delete record if yes is chosen
    if ($_POST['sure'] == 'Yes') {
        //Query to get survey ID
        $q = "SELECT `survey_id`, `unique_id` FROM `survey`.`entity_survey` AS `entity_survey` WHERE `unique_id` = '$id'";
        $r = mysqli_query($link, $q);
        if (mysqli_num_rows($r) == 1) {
            $row = mysqli_fetch_array($r, MYSQLI_NUM);
            $surveyID = $row[0];    //Get survey ID
        } else {
            echo '<h4 class="text-center" style="color:green;">The survey could not be found.</h4>';
            echo '<a href="admin.php" class="btn btn-default">Go back to Admin Panel</button>';
        }
        
        //Array to hold answers, questions for later deletion
        $ansArray = array();
        $qstnArray = array();

        //Delete xref_survey_question
        $q2 = "SELECT `xref_survey_question`.`survey_id`, `xref_survey_question`.`question_id` FROM `survey`.`xref_survey_question` AS `xref_survey_question`, `survey`.`entity_question` AS `entity_question`, `survey`.`entity_survey` AS `entity_survey` WHERE `xref_survey_question`.`question_id` = `entity_question`.`question_id` AND `xref_survey_question`.`survey_id` = `entity_survey`.`survey_id` AND `xref_survey_question`.`survey_id` = '$surveyID'";
        $r2 = mysqli_query($link, $q2);
        
        $numQstn = mysqli_num_rows($r2);

        for ($i=0; $i<$numQstn; $i++) {
            mysqli_data_seek($r2, $i);
            $rowQstn = $r2->fetch_assoc();
            $qstnID = $rowQstn['question_id'];   //Get question ID for each row

            $q3 = "SELECT `xref_question_answer`.`question_id`, `xref_question_answer`.`answer_id` FROM `survey`.`xref_question_answer` AS `xref_question_answer`, `survey`.`entity_question` AS `entity_question`, `survey`.`entity_answer` AS `entity_answer` WHERE `xref_question_answer`.`question_id` = `entity_question`.`question_id` AND `xref_question_answer`.`answer_id` = `entity_answer`.`answer_id` AND `xref_question_answer`.`question_id` = '$qstnID'";
            $r3 = mysqli_query($link, $q3);

            if (mysqli_num_rows($r3) > 0) {
                $numAns = mysqli_num_rows($r3);

                for ($j=0; $j<$numAns; $j++) {
                    mysqli_data_seek($r3, $j);
                    $rowAns = $r3->fetch_assoc();
                    $ansID = $rowAns['answer_id'];   //Get question ID for each row

                    array_push($ansArray, $ansID);

                    //Delete xref_answer_user rows
                    $query_delete_xref_answer_user = "DELETE FROM xref_answer_user WHERE answer_id = '$ansID'";
                    mysqli_query($link, $query_delete_xref_answer_user);
                }
            }
            array_push($qstnArray, $qstnID);

            //Delete xref_question_answer rows
            $query_delete_xref_question_answer = "DELETE FROM xref_question_answer WHERE question_id = '$qstnID'";
            mysqli_query($link, $query_delete_xref_question_answer);
        }

        //Delete xref_survey_question rows
        $query_delete_xref_survey_question = "DELETE FROM xref_survey_question WHERE survey_id = '$surveyID'";
        mysqli_query($link, $query_delete_xref_survey_question);

        //Delete entity_answer
        for ($i=0; $i<count($ansArray); $i++) {
            $query_delete_answer = "DELETE FROM entity_answer WHERE answer_id = '$ansArray[$i]'";
            mysqli_query($link, $query_delete_answer);
        }

        //Delete entity_question
        for ($i=0; $i<count($qstnArray); $i++) {
            $query_delete_question = "DELETE FROM entity_question WHERE question_id = '$qstnArray[$i]'";
            mysqli_query($link, $query_delete_question);
        }

        //Delete_xref_user_survey rows
        $query_delete_xref_user_survey = "DELETE FROM xref_user_survey WHERE survey_id = '$surveyID'";
        mysqli_query($link, $query_delete_xref_user_survey);

        //Delete entity_survey row
        $query_delete_survey = "DELETE FROM entity_survey WHERE survey_id = '$surveyID'";
        mysqli_query($link, $query_delete_survey);

        echo '<h4 class="text-center" style="color:green;">The survey has been deleted.</h4>';
        echo '<a href="admin.php" class="btn btn-default">Go back to Admin Panel</button>';
    } else {
        echo '<h4 class="text-center">The survey has NOT been deleted.</h4>';
        echo '<a href="admin.php" class="btn btn-default">Go back to Admin Panel</button>';
    }
}
//Show form
else {
    $sql = "SELECT `survey_id`, `unique_id`, `title`, `description` FROM `survey`.`entity_survey` AS `entity_survey` WHERE `unique_id` = '$id'";
    $rs = mysqli_query($link,$sql);

    if (mysqli_num_rows($rs) == 1) {
        //Get product information:
        $row = mysqli_fetch_array ($rs, MYSQLI_NUM);

        // Display the record being deleted:
        echo "<div class='text-center'>";
        echo "<h3>Survey title: $row[2]</h3>
              <h3>Description: $row[3]</h3>";
                  
        echo "<br>Are you sure you want to delete this survey?<br><br>";

        // Create the form:
        echo '<form action="delete_survey.php" method="post">
              <input type="radio" name="sure" value="Yes"> Yes<br>
              <input type="radio" name="sure" value="No" checked="checked"> No<br><br>
              <input type="submit" name="submit" value="Submit">
              <input type="hidden" name="id" value="' . $id . '">
              </form>';
        echo "</div>";

    } else {
            echo '<p class="error">Error, something went wrong. Survey not found.</p>';
    }
}
?>