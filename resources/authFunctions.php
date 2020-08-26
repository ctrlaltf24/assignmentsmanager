<?php
require_once("assignmentFunctions.php");
function questionInAssignment($assignment_key,$question_key,$conn,$is_teacher=false){
    if(in_array($question_key,getQuestionKeys($conn,$assignment_key,$is_teacher))&&$question_key!=""){
        return true;
    } else {
        return false;
    }

}
function canViewAssignment($assignment_key,$is_teacher=false,$conn,$user){
    if($is_teacher){
        $return=false;
        if($results=$conn->query("SELECT * FROM assignments WHERE `key`=$assignment_key AND `teacherKey`=".$user['key'])){
            while($row=$results->fetch_assoc()){
                $return=true;
            }
            $results->close();
        } else {
            log_error("failed to get assignments","",$conn->error);
        }
        if($return){
            return true;
        }
    } else {
        //TODO: do check to make sure the assignemnt is open and part of the user's class
        $found =false;
        if($result=$conn->query("SELECT * FROM assignments WHERE `key`=".$assignment_key." AND `disabled`=0 LIMIT 1")) {
            while ($row = $result->fetch_assoc()) {
                $found =true;
            }
        } else {
            log_error("failed to get assignments","",$conn->error);
        }
        if($found==false){
            return false;
        }
        $confirmed_is_from_class = false;
        $scheduleName="default";
        $keys=explode(";",$user["classKey"]);
        foreach (array_keys($keys, "", true) as $key) {
            unset($keys[$key]);
        }
        foreach ($keys as $key => $value){
            if($result=$conn->query("SELECT `key`,`assignmentKeys`,`teacherKey` FROM classes WHERE `key`=".$value)) {//gets assignmentKeys that the user is in per class
                while ($row = $result->fetch_assoc()) {
                    $assignment_keys=explode(";",$row["assignmentKeys"]);
                    foreach (array_keys($assignment_keys, "", true) as $key) {
                        unset($assignment_keys[$key]);
                    }
                    if(in_array($assignment_key,$assignment_keys)){
                        if($result=$conn->query("SELECT `scheduleName` FROM teachers WHERE `key`=".$row["teacherKey"]." LIMIT 1")) {
                            while ($row = $result->fetch_assoc()) {
                                $scheduleName = $row["scheduleName"];
                            }
                        } else {
                            log_error("failed to get teachers","",$conn->error);
                        }
                        $confirmed_is_from_class=true;
                    }
                }
                $result->close();
            } else {
                log_error("failed to get classes","",$conn->error);
                return false;
            }
        }
        if(!$confirmed_is_from_class){
            echo "You are not in the class that this assignment is from.";
            exit();
        } else {
            unset($confirmed_is_from_class);
        }
        //Now test to see if the assignment is open and not hidden yet
        /*
        require_once "date.php";
        $time=convertFromUnixEpoch($conn,time(),$scheduleName);
        if(!is_numeric($time)){
            echo "Days need to be setup for your teacher.";
            exit();
        }
        if($result=$conn->query("SELECT `timeAccessible`,`timeHide` FROM assignments WHERE `disabled`=0 AND `timeAccessible`<$time AND `timeHide`>$time AND `key`=".$assignment_key)) {
            if($row = $result->fetch_row()){
                return true;
            } else {
                echo "You cant access that assignment anymore/yet.";
            }
        } else {
            log_error("failed to get assignments","",$conn->error);
        }
        $result->close();
        */
        return true;
    }
    return false;
}
