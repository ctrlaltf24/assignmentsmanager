<?php
require_once "../../resources/connect.php";
require_once "../template/form.php";
if(!isset($questionKey)) {
    if (isset($_GET["questionKey"])) {
        $questionKey = $_GET["questionKey"];
    } else {
        echo "no questionKey";
        exit();
    }
}
if(!isset($assignmentKey)){
    if (isset($_GET["assignmentKey"])) {
        $assignmentKey = $_GET["assignmentKey"];
    } else {
        echo "no assignmentKey";
        exit();
    }
}
if(!isset($teacherKey)){
    if (isset($_GET["teacherKey"])) {
        $teacherKey = $_GET["teacherKey"];
    } else {
        echo "no teacherKey";
        exit();
    }
}
if(!isset($hint_number)){
    if (isset($_GET["number"])) {
        $hint_number = $_GET["number"];
    } else {
        echo "no hint number";
        exit();
    }
}
if(!$logged_in) {
    echo "Login. I see you found this address and figured out how the hints were retrieved.";
    exit();
}
require_once "../../resources/authFunctions.php";
require_once "../../resources/questionFormat.php";
if(questionInAssignment($assignmentKey,$questionKey,$conn,$is_teacher)) {
    if(canViewAssignment($assignmentKey,$is_teacher,$conn,$user)) {
        $results = $conn->query("SELECT `hints` FROM questions WHERE `key`=$questionKey LIMIT 1");
        while ($row = $results->fetch_assoc()) {
            $hints = explode(";", $row["hints"]);
            foreach (array_keys($hints, "", true) as $key) {
                unset($hints[$key]);
            }
            if (sizeof($hints)+1 != $hint_number) {
                $result=$conn->query("SELECT `variables` FROM `variables` WHERE `email`=\"".$user["email"]."\" AND `question`=$questionKey");
                $vars=array();
                while($row=$result->fetch_assoc()){
                    foreach (explode("|",$row["variables"]) as $key => $value){
                        if($value!="") {
                            $vars[explode(";", $value)[0]] = explode(";", $value)[1];
                        }
                    }
                }
                echo format_text_tilde_codes($hints[$hint_number+1],$vars,$teacherKey);//STarts at 1
                $result=$conn->query("SELECT `hint` FROM `hints` WHERE `email`=\"".$user["email"]."\" AND `assignmentKey`=$assignmentKey AND `question`=$questionKey AND `hint`=".($hint_number+1));
                $found=false;
                while($result->fetch_assoc()){
                    $found = true;
                }
                $result->close();
                if(!$found) {
                    $conn->query("INSERT INTO `hints`(`email`, `assignmentKey`, `question`, `hint`, `time`) VALUES (\"" . $user["email"] . "\",$assignmentKey,$questionKey," . ($hint_number + 1) . "," . time() . ")");
                }
            } else {
                echo "No more hints";
            }
        }
        $results->close();
    } else {
        echo "Question not in that assignment silly.";
    }
} else {
    echo "Permission Denied";
}
