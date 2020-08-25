<?php
include "../template/ui.php";
require_once "../../resources/connect.php";
require_once "../template/questionAdmin.php";
require_once "../template/miscElements.php";
if(isset($_GET["key"])){
    //handle like an update
    $query="";
    foreach ($_POST as $key => $value) {
        $key=str_replace(";","",$key);
        $key=str_replace(";","",$key);
        $query.="`".$key."`=\"".$value."\", ";
    }
    $query=preg_replace('/(, (?!.*, ))/', '', $query);
    //TODO: ADD TEACHER PERMISSION CHECK RIGHT HERE! is this needed due to being in a teacher folder though?
    if($query===""){
        log_error("Nothing given","query is empty",$conn->error);
    }
    if(!$conn->query("UPDATE `questions` SET ".$query." WHERE `key`=".$_GET["key"])){
        log_error("question update","database error",$conn->error);
    } else {
        $col_id=$conn->insert_id;
        echo $col_id;
    }
} else {
    if (isset($_POST["name"])) {
        if(!isset($_POST["possibleAnswers"])){$_POST["possibleAnswers"]="";}
        if(!isset($_POST["answer"])){$_POST["answer"]="";}
        if (!$conn->query("INSERT INTO `questions`(`name`, `questionType`, `possibleAnswers`, `units`, `answer`, `hints`, `level`, `chapter`, `concept`, `subject`, `topic`, `points`, `subQuestions`,`teacherKey`) VALUES (\"" . $_POST["name"] . "\",\"" . $_POST["questionType"] . "\",\"" . $_POST["possibleAnswers"] . "\",\"" . $_POST["units"] . "\",\"" . $_POST["answer"] . "\",\"" . $_POST["hints"] . "\",\"" . $_POST["level"] . "\",\"" . $_POST["chapter"] . "\",\"" . $_POST["concept"] . "\",\"" . $_POST["subject"] . "\",\"" . $_POST["topic"] . "\",\"" . $_POST["points"] . "\",\"\",".$user["key"].")")) {
            log_error("insert","main database",$conn->error);
        }
        $insertId = $conn->insert_id;
        echo $insertId;
        if (isset($_POST["parent-question"]) && $_POST["parent-question"] != "") {
            if (!$result = $conn->query("SELECT * FROM `questions` WHERE `key`=" . $_POST["parent-question"])) {
                log_error("target assignment","database",$_POST["parent-question"]." <-key,error-> ".$conn->error);
            }
            $previousSub = null;
            while ($row = $result->fetch_assoc()) {
                $previousSub = ($row["subQuestions"] == null ? ";" : $row["subQuestions"]);
            }
            if ($previousSub === null) {
                log_error("target assignment","database",$_POST["parent-question"]." <-key,");
            }
            if (!$conn->query("UPDATE `questions` SET `subQuestions`=\"" . $previousSub . $insertId . ";\" WHERE `key`=" . $_POST["parent-question"])) {
                log_error("update subquestion","database",$conn->error);
            }
        } else if (isset($_POST["assignment"]) && $_POST["assignment"] != "") {
            if (!$result = $conn->query("SELECT * FROM `assignments` WHERE `key`=" . $_POST["assignment"])) {
                log_error("post assignment","database",$conn->error);
            }
            $previousQuestions = null;
            while ($row = $result->fetch_assoc()) {
                $previousQuestions = ($row["questions"] == null ? ";" : $row["questions"]);
            }
            if ($previousQuestions === null) {
                log_error("get target assignment","find",$_POST["assignment"]);
            }
            if (!$conn->query("UPDATE `assignments` SET `questions`=\"" . $previousQuestions . $insertId . ";\" WHERE `key`=" . $_POST["assignment"])) {
                log_error("updating assignments","database",$conn->error);
            }
        }
    } else {
        log_error("improper arguments","no name","");
    }
}
