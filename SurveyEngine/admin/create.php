<?php
$page_title = 'Survey Created';
include ('../includes/header.php');
require_once ('../config.php');

//Check if user is admin
if(!isset($_SESSION["admin"]) && $_SESSION["admin"] !== true) {
    header("location: home.php");
    exit;
}
?>

<style>
    body {text-align:center;}
</style>

<SCRIPT LANGUAGE="JavaScript">
    function copyToClipboard(text) {
        window.prompt("Copy to clipboard: Ctrl+C, Enter", text);
    }
</SCRIPT>
    
<div class="page-header">
    <h1>Survey Creation Success</h1>
</div>

<?php
// Decode the survey object
$obj = json_decode($_COOKIE["survey"]);

////Output example of decoded object
//echo "Survey&nbspTitle: " , $obj->title , "<br>";
//echo "Description: " , $obj->desc , "<br><br>";
//
//$counter = 0;   //Counter for question number
//foreach ($obj->qstnArr as $qstn) {
//    $counter++;
//    echo $qstn->qstn , "<br>";
//    foreach ($qstn->answers as $ans) {
//        echo '<input type="radio" name=q' . $counter . ' value=' . $ans . '>' . $ans . "<br>";
//    }
//    echo "<br>";
//}

//Primary keys for table insertions are all auto-incremented
//Insert survey title and description into database as well as unique id identifier
$string = bin2hex(random_bytes(16));    //Create random unique hash/string
$query = "INSERT INTO `entity_survey` (title, description, unique_id) VALUES ('$obj->title', '$obj->desc', '$string')";
mysqli_query($link,$query);   //Input query
$surveyID = mysqli_insert_id($link);

$adminID = $_SESSION["id"];
//Insert xref_admin_survey entry
$query = "INSERT INTO `xref_user_survey` (user_id, survey_id) VALUES ('$adminID', '$surveyID')";
mysqli_query($link, $query);

//For each question
foreach ($obj->qstnArr as $qstn) {
    //Insert question into database
    $query = "INSERT INTO `entity_question` (question) VALUES ('$qstn->qstn')";
    mysqli_query($link, $query);
    $qstnID = mysqli_insert_id($link);
    
    //Insert xref_survey_question entry
    $query = "INSERT INTO `xref_survey_question` (survey_id, question_id) VALUES ('$surveyID', '$qstnID')";
    mysqli_query($link, $query);
    
    //For each answer to a question
    foreach ($qstn->answers as $ans) {
        //Insert answer into database
        $query = "INSERT INTO `entity_answer` (answer) VALUES ('$ans')";
        mysqli_query($link, $query);
        $ansID = mysqli_insert_id($link);
        
        //Insert xref_question_answer entry
        $query = "INSERT INTO `xref_question_answer` (question_id, answer_id) VALUES ('$qstnID', '$ansID')";
        mysqli_query($link, $query);
    }
}

//Link to survey invitation
$copyLink = 'http://' . $_SERVER['HTTP_HOST'] . "/SurveyEngine/survey.php?id="  . $string;

//Output message
echo "<p>Survey has been created!</p>";
echo "<p>You can view your surveys at your account page.</p><br>";
echo "<p>Send the following survey invite link to any participants:</p>";
echo "<button id='reg' style='max-width:100px;' onclick='copyToClipboard(\"$copyLink\")'>Copy</button>";

mysqli_close($link);
?>