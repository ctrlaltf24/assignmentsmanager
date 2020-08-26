<?php
function getStudentsClass($conn,$class){
    if($results=$conn->query("SELECT firstName,lastName,email,`key` FROM `users` WHERE `classKey` LIKE \"%;$class;%\" ORDER BY `firstName`")){
        $users=array();
        while($row=$results->fetch_assoc()){
            array_push($users,$row);
        }
        $results->close();
        return $users;
    } else {
        log_error("failed to get users","",$conn->error);
    }
}
function getAssignmentClass($conn,$class){
    if($results=$conn->query("SELECT `assignmentKeys` FROM `classes` WHERE `key`=$class")){
        $keys=array();
        while($row=$results->fetch_assoc()){
            $keys=explode(";",$row["assignmentKeys"]);
        }
        $results->close();
        $assignments=array();
        foreach ($keys as $key => $value) {
            if($value===""){
                unset($keys[$key]);
            } else {
                if($results=$conn->query("SELECT * FROM `assignments` WHERE `key`=$value")){
                    while($row=$results->fetch_assoc()){
                        $assignments[$value]=$row;
                    }
                    $results->close();
                }
            }
        }
        return $assignments;
    } else {
        log_error("failed to get classes","",$conn->error);
    }
}
function getQuestionAssignmnet($conn,$assignment){
    if($results=$conn->query("SELECT `questions` FROM `assignments` WHERE `key`=$assignment LIMIT 1")){
        while($row=$results->fetch_assoc()){
            $questions = explode(";", $row["questions"]);
            foreach (array_keys($questions, "", true) as $key) {
                unset($questions[$key]);
            }
            return $questions;
        }
        $results->close();
    } else {
        log_error("failed to get assignments","",$conn->error);
    }
}
