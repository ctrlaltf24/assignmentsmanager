<?php
require_once "../template/ui.php";
require_once "../../../staging_resources/connect.php";
require_once "../../../staging_resources/startsWithEndsWith.php";
require_once "../../../staging_resources/date.php";

$_POST["timeAccessible"]=-1;
$_POST["timeHide"]=-1;
$_POST["timeDue"]=-1;

//handle the date resolving to # of class
if(isset($_POST["dateOpen"])&&isset($_POST["timeOpen"])){
	$_POST["timeAccessible"] = convertDateTimeToCustom($conn,$_POST["dateOpen"],$_POST["timeOpen"],$user["scheduleName"],($user["twoDaySchedule"]?2:1));
}
unset($_POST["dateOpen"]);
unset($_POST["timeOpen"]);
if(isset($_POST["dateClose"])&&isset($_POST["timeClose"])){
	$_POST["timeHide"]		= convertDateTimeToCustom($conn,$_POST["dateClose"],$_POST["timeClose"],$user["scheduleName"],($user["twoDaySchedule"]?2:1));
}
unset($_POST["dateClose"]);
unset($_POST["timeClose"]);
if(isset($_POST["dateDue"])&&isset($_POST["timeDue"])){
	$_POST["timeDue"]		= convertDateTimeToCustom($conn,$_POST["dateDue"],$_POST["timeDue"],$user["scheduleName"],($user["twoDaySchedule"]?2:1));
}
unset($_POST["dateDue"]);
//NOTE CHECKBOKES ARE NOT SET IF FALSE
if(isset($_GET["key"])){
    //handle like an update
    $query="";
    foreach ($_POST as $key => $value) {
        if (!startsWith($key, "class_")) {
            $key = str_replace(";", "", $key);
            $key = str_replace(";", "", $key);
            $query .= "`" . $key . "`=\"" . $value . "\", ";
        }
    }
    $query=preg_replace('/(, (?!.*, ))/', '', $query);
    //TODO: ADD TEACHER PERMISSION CHECK RIGHT HERE!
    //TODO: ADD EXTRA HANDLERS FOR DATE AND REMOING OF ASSIGNM,ENTS HERE
    $col_id = $_GET["key"];
    if($query!="") {
        if (!$conn->query("UPDATE `assignments` SET " . $query . " WHERE `key`=" . $_GET["key"])) {
            log_error("update assignment","databse",$conn->error);
        } else {
            echo $col_id;
        }
    }
} else {
    //handle like an insert
	if(!$conn->query("INSERT INTO `assignments`(`name`, `subject`, `chapter`, `concept`, `timeAccessible`, `timeHide`, `timeDue`, `disabled`, `questions`, `randomizeOrder`, `infiniteTries`,`teacherKey`) VALUES (\"".$_POST["name"]."\",\"".$_POST["subject"]."\",\"".$_POST["chapter"]."\",\"".$_POST["concept"]."\",".$_POST["timeAccessible"].",".$_POST["timeHide"].",".$_POST["timeDue"].",".(isset($_POST["disabled"])&&$_POST["disabled"]==="on"?1:0).",\"\",".(isset($_POST["randomizeOrder"])&&$_POST["randomizeOrder"]==="on"?1:0).",".(isset($_POST["infiniteTries"])&&$_POST["infiniteTries"]==="on"?1:0).",\"".$user["key"]."\")")){
        $col_id=$conn->insert_id;
        log_error("new assignment","databse",$conn->error);
    } else {
        $col_id=$conn->insert_id;
        echo $col_id;
    }
}
//TODO: handle unchecking of check boxes
foreach ($_POST as $key => $value) {
    if(startsWith($key,"class_")){
        if($value==1) {
            if (!$result = $conn->query("SELECT * FROM `classes` WHERE `key`=" . str_replace("class_", "", $key))) {
                log_error("finding class","databse",$conn->error);
            }
            $previousAssignments = null;
            while ($row = $result->fetch_assoc()) {
                $previousAssignments = ($row["assignmentKeys"] == null ? ";" : $row["assignmentKeys"]);
            }
            if ($previousAssignments === null) {
                log_error("get target class","databse","class key ".str_replace("class_", "", $key));
            }
            if (!$conn->query("UPDATE `classes` SET `assignmentKeys`=\"" . $previousAssignments . $col_id . ";\" WHERE `key`=" . str_replace("class_", "", $key))) {
                log_error("update class","databse",$conn->error);
            }
        } else {
            if (!$result = $conn->query("SELECT * FROM `classes` WHERE `key`=" . str_replace("class_", "", $key))) {
                log_error("find class","databse","with key ".str_replace("class_", "", $key)." ",$conn->error);
            }
            $previousAssignments = null;
            while ($row = $result->fetch_assoc()) {
                $previousAssignments = ($row["assignmentKeys"] == null ? ";" : $row["assignmentKeys"]);
            }
            if ($previousAssignments === null) {
                log_error("find class","databse","with key ".str_replace("class_", "", $key));
            }
            $previousAssignments=str_replace("$col_id;","",$previousAssignments);
            if (!$conn->query("UPDATE `classes` SET `assignmentKeys`=\"" . $previousAssignments . "\" WHERE `key`=" . str_replace("class_", "", $key))) {
                log_error("update class","databse",$conn->error);
            }
        }
    }
}