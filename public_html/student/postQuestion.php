<?php
require_once "../template/questionUser.php";
require_once "../../resources/authFunctions.php";
require_once "../../resources/connect.php";
require_once "../../resources/questionFormat.php";
//TODO: fix permission check
if(!$is_teacher){
    if(!questionInAssignment($_GET['assignmentKey'],$_GET['questionKey'],$conn,$is_teacher)){
        echo "Incorrect arguments.";
        $conn->query("INSERT INTO nosy_students (`email`, `attemptedAcssesPage`, `time`) VALUES (\"" . $user["email"] . "\",\"" . urldecode($_SERVER["REQUEST_URI"])." Tried changing the question off of the assignment" . "\"," . time() . ")");
        exit();
    }
    if(!canViewAssignment($_GET['assignmentKey'],$is_teacher,$conn,$user)){
        echo "Incorrect arguments.";
        $conn->query("INSERT INTO nosy_students (`email`, `attemptedAcssesPage`, `time`) VALUES (\"" . $user["email"] . "\",\"" . urldecode($_SERVER["REQUEST_URI"])." Tried acssesing a page that they didnt have acsess to." . "\"," . time() . ")");
        exit();
    }
}
$answer = "";
foreach ($_POST as $key => $value){
    $answer=$value;
}
if($result = $conn->query("SELECT * FROM `responces` WHERE `email`=\"".$user["email"]."\" AND `assignmentKey`=".$_GET['assignmentKey']." AND `question`=".$_GET['questionKey']." AND `correct`=1 ORDER BY `questionAttempt` DESC LIMIT 1")){
    while ($row = $result->fetch_assoc()) {
        echo template_user_key($_GET['questionKey'],$conn,$user,$_GET["questionNumber"],$_GET["assignmentKey"],false,false,-1,true,$answer,false);
        exit();
    }
}
$result=$conn->query("SELECT * FROM `assignments` WHERE `key`=".$_GET['assignmentKey']." LIMIT 1");
$infinite_tries=false;
$randomize=false;
$teacherKey=null;
while ($row = $result->fetch_assoc()) {
    $infinite_tries=$row["infiniteTries"];
    $randomize=$row["randomizeOrder"];
    $teacherKey=$row["teacherKey"];
}
$result->close();
if(!$infinite_tries){
     if($result = $conn->query("SELECT * FROM `responces` WHERE `email`=\"".$user["email"]."\" AND `assignmentKey`=".$_GET['assignmentKey']." AND `question`=".$_GET['questionKey']." LIMIT 1")){
          while ($row = $result->fetch_assoc()) {
              echo template_user_key($_GET['questionKey'],$conn,$user,$_GET["questionNumber"],$_GET["assignmentKey"],$randomize,$infinite_tries,$teacherKey,false,$answer,false);
              exit();
          }
     }
}
$questionAttempt=0;
$result = $conn->query("SELECT * FROM `responces` WHERE `email`=\"".$user["email"]."\" AND `assignmentKey`=".$_GET['assignmentKey']." AND `question`=".$_GET['questionKey']." ORDER BY `questionAttempt` DESC LIMIT 1");
while ($row = $result->fetch_assoc()) {
    $questionAttempt = $row["questionAttempt"]+1;
}
$result->close();
$result = $conn->query("SELECT * FROM `hints` WHERE `email`=\"".$user["email"]."\" AND `assignmentKey`=".$_GET['assignmentKey']." AND `question`=".$_GET['questionKey']." ORDER BY `hint` DESC LIMIT 1");
$hintsReached=0;
while ($row = $result->fetch_assoc()) {
    $hintsReached = $row["hint"];
}
$result->close();
$var_out="";
$result = $conn->query("SELECT `variables` FROM `variables` WHERE `email`=\"".$user["email"]."\" AND `question`=".$_GET['questionKey']);
while ($row = $result->fetch_assoc()) {
    $var_out=$row["variables"];
}
$result->close();
$result=$conn->query("SELECT * FROM `questions` WHERE `key`=".$_GET['questionKey']." LIMIT 1");
$question=array();
while ($row = $result->fetch_assoc()) {
    $question=$row;
}
$result->close();
$result=$conn->query("SELECT `variables` FROM `variables` WHERE `email`=\"".$user["email"]."\" AND `question`=".$_GET['questionKey']);
$vars=array();
while($row=$result->fetch_assoc()){
    foreach (explode("|",$row["variables"]) as $key => $value){
        if($value!="") {
            $vars[explode(";", $value)[0]] = explode(";", $value)[1];
        }
    }
}
$result->close();
$correct=(format_text_tilde_codes($answer,$vars,$_GET['teacherKey'])==format_text_tilde_codes($question["answer"],$vars,$_GET['teacherKey']))||$question["questionType"]=="Free Response Question";
require_once '../../resources/score_manager.php';
if(!$is_teacher){
    if($conn->query("INSERT INTO `responces`(`email`, `assignmentKey`, `question`, `questionAttempt`, `questionVariables`, `answer`, `hintsReached`, `timeElapsed`, `timeTaken`,`points`,`correct`) VALUES (\"".$user['email']."\",".$_GET['assignmentKey'].",".$_GET['questionKey'].",".$questionAttempt.",\""."$var_out"."\",\"".$answer."\",".$hintsReached.",".(isset($_GET["timeTaken"])&&is_numeric($_GET["timeTaken"])?$_GET["timeTaken"]:0).",".time().",".($correct?score_calculate($questionAttempt,$hintsReached,$question["points"]):0).",".($correct?1:0).")")){
        score_assignment_update($conn,$user["email"],$_GET['assignmentKey'],$infinite_tries);
        if($correct){//correct answer
            echo template_user_key($_GET['questionKey'],$conn,$user,$_GET["questionNumber"],$_GET["assignmentKey"],$randomize,$infinite_tries,$teacherKey,true,$answer,false);
        } else if ($infinite_tries) {//infinite tries check
            echo template_user_key($_GET['questionKey'],$conn,$user,$_GET["questionNumber"],$_GET["assignmentKey"],$randomize,$infinite_tries,$teacherKey,false,$answer,false);
        } else {//incorrect only one try
            echo template_user_key($_GET['questionKey'],$conn,$user,$_GET["questionNumber"],$_GET["assignmentKey"],$randomize,$infinite_tries,$teacherKey,true,$answer,false);
        }
    } else {
        log_error("submit","databse"," error ".$conn->error);
    }
} else {
    if($correct){//correct answer
        echo template_user_key($_GET['questionKey'],$conn,$user,$_GET["questionNumber"],$_GET["assignmentKey"],$randomize,$infinite_tries,$teacherKey,true,$answer,false);
    } else if ($infinite_tries) {//infinite tries check
        echo template_user_key($_GET['questionKey'],$conn,$user,$_GET["questionNumber"],$_GET["assignmentKey"],$randomize,$infinite_tries,$teacherKey,false,$answer,false);
    } else {//incorrect only one try
        echo template_user_key($_GET['questionKey'],$conn,$user,$_GET["questionNumber"],$_GET["assignmentKey"],$randomize,$infinite_tries,$teacherKey,true,$answer,false);
    }
}
//TODO: update questionAttempt
//TODO: add points per question
