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
    //TODO: ADD TEACHER PERMISSION CHECK RIGHT HERE!
    if($query===""){
        echo "<h2>Nothing given</h2>";
        exit();
    }
    if(!$conn->query("UPDATE `questions` SET ".$query." WHERE `key`=".$_GET["key"])){
        $col_id=$conn->insert_id;
        echo "<h2>Failure :( in update</h2>";
    } else {
        $col_id=$conn->insert_id;
        echo $col_id;
    }
} else {
    if (isset($_POST["name"])) {
        if(!isset($_POST["possibleAnswers"])){$_POST["possibleAnswers"]="";}
        if(!isset($_POST["answer"])){$_POST["answer"]="";}
        if (!$conn->query("INSERT INTO `questions`(`name`, `questionType`, `possibleAnswers`, `units`, `answer`, `hints`, `level`, `chapter`, `concept`, `subject`, `topic`, `points`, `subQuestions`,`teacherKey`) VALUES (\"" . $_POST["name"] . "\",\"" . $_POST["questionType"] . "\",\"" . $_POST["possibleAnswers"] . "\",\"" . $_POST["units"] . "\",\"" . $_POST["answer"] . "\",\"" . $_POST["hints"] . "\",\"" . $_POST["level"] . "\",\"" . $_POST["chapter"] . "\",\"" . $_POST["concept"] . "\",\"" . $_POST["subject"] . "\",\"" . $_POST["topic"] . "\",\"" . $_POST["points"] . "\",\"\",".$user["key"].")")) {
            echo "Failed main insert $conn->error";
            exit();
        }
        $insertId = $conn->insert_id;
        echo $insertId;
        if (isset($_POST["parent-question"]) && $_POST["parent-question"] != "") {
            if (!$result = $conn->query("SELECT * FROM `questions` WHERE `key`=" . $_POST["parent-question"])) {
            	echo $_POST["parent-question"];
                echo "failed 2 > " . $conn->error;
                exit();
            }
            $previousSub = null;
            while ($row = $result->fetch_assoc()) {
                $previousSub = ($row["subQuestions"] == null ? ";" : $row["subQuestions"]);
            }
            if ($previousSub === null) {
                echo "failed to get target assignment";
                exit();
            }
            if (!$conn->query("UPDATE `questions` SET `subQuestions`=\"" . $previousSub . $insertId . ";\" WHERE `key`=" . $_POST["parent-question"])) {
                echo "Failed updating subQ";
                exit();
            }
        } else if (isset($_POST["assignment"]) && $_POST["assignment"] != "") {
            if (!$result = $conn->query("SELECT * FROM `assignments` WHERE `key`=" . $_POST["assignment"])) {
                echo "failed > " . $conn->error;
                exit();
            }
            $previousQuestions = null;
            while ($row = $result->fetch_assoc()) {
                $previousQuestions = ($row["questions"] == null ? ";" : $row["questions"]);
            }
            if ($previousQuestions === null) {
                echo "failed to get target assignment";
                exit();
            }
            if (!$conn->query("UPDATE `assignments` SET `questions`=\"" . $previousQuestions . $insertId . ";\" WHERE `key`=" . $_POST["assignment"])) {
                echo "Failed updating assignments";
                exit();
            }
        }
    } else {
        echo "nameless";
    }
}
