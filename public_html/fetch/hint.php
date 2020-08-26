<?php
require_once "../../../staging_resources/connect.php";
require_once "../template/form.php";
if(!isset($questionKey)) {
    if (isset($_GET["questionKey"])) {
        $questionKey = $_GET["questionKey"];
    } else {
        log_error("no questionKey","");
    }
}
if(!isset($assignmentKey)){
    if (isset($_GET["assignmentKey"])) {
        $assignmentKey = $_GET["assignmentKey"];
    } else {
        log_error("no assignmentKey","");
    }
}
if(!isset($teacherKey)){
    if (isset($_GET["teacherKey"])) {
        $teacherKey = $_GET["teacherKey"];
    } else {
        log_error("no teacherKey","");
    }
}
if(!isset($hint_number)){
    if (isset($_GET["number"])) {
        $hint_number = $_GET["number"];
    } else {
        log_error("no hint number","");
    }
}
if(!$logged_in) {
    log_error("Login. I see you found this address and figured out how the hints were retrieved.","");
}
require_once "../../../staging_resources/authFunctions.php";
require_once "../../../staging_resources/questionFormat.php";
if(questionInAssignment($assignmentKey,$questionKey,$conn,$is_teacher)) {
    if(canViewAssignment($assignmentKey,$is_teacher,$conn,$user)) {
        if(!$results = $conn->query("SELECT `hints` FROM questions WHERE `key`=$questionKey LIMIT 1")){
            log_error("failed to get hints","",$conn->error);
        }
        while ($row = $results->fetch_assoc()) {
            $hints = explode(";", $row["hints"]);
            foreach (array_keys($hints, "", true) as $key) {
                unset($hints[$key]);
            }
            if (sizeof($hints)+1 != $hint_number) {
                if(!$result=$conn->query("SELECT `variables` FROM `variables` WHERE `email`=\"".$user["email"]."\" AND `question`=$questionKey")){
                    log_error("failed to get variables","",$conn->error);
                }
                $vars=array();
                while($row=$result->fetch_assoc()){
                    foreach (explode("|",$row["variables"]) as $key => $value){
                        if($value!="") {
                            $vars[explode(";", $value)[0]] = explode(";", $value)[1];
                        }
                    }
                }
                echo format_text_tilde_codes($hints[$hint_number+1],$vars,$teacherKey);//STarts at 1
                if(!$result=$conn->query("SELECT `hint` FROM `hints` WHERE `email`=\"".$user["email"]."\" AND `assignmentKey`=$assignmentKey AND `question`=$questionKey AND `hint`=".($hint_number+1))){
                    log_error("failed to get hints","",$conn->error);
                }
                $found=false;
                while($result->fetch_assoc()){
                    $found = true;
                }
                $result->close();
                if(!$found) {
                    if(!$conn->query("INSERT INTO `hints`(`email`, `assignmentKey`, `question`, `hint`, `time`) VALUES (\"" . $user["email"] . "\",$assignmentKey,$questionKey," . ($hint_number + 1) . "," . time() . ")")){
                        log_error("failed to inser hints","",$conn->error);
                    }
                }
            } else {
                echo "No more hints";
            }
        }
        $results->close();
    } else {
        log_error("Question not in that assignment.","");
    }
} else {
    log_error("Permission Denied","");
}
