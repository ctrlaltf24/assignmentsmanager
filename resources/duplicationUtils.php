<?php
require_once '../../resources/sqlArray.php';
function duplicate_class($conn,$user,$classID) {
    foreach (sql_to_array($conn,"SELECT * FROM classes WHERE `key`=".$_POST["class"]." LIMIT 1") as $oldClassRow) {
        // generate a new unique class code
        $found=true;
        while($found){
            $classCode=substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(8/strlen($x)) )),1,8);
            if($result = $conn->query("SELECT name FROM classes WHERE classCode=\"".$classCode."\"")){
                if($result->num_rows===0){
                    $found=false;
                }
            }
        }
        $newAssignmentKeys=duplicate_assignments($conn,$user,$oldClassRow["assignmentKeys"]);
        //there should only be one entry here
        if(!$conn->query("INSERT INTO `classes`(`name`, `year`, `period`, `subject`, `teacherKey`, `assignmentKeys`, `day`,`classCode`) VALUES (\"".$oldClassRow["name"]."\",\"".$_POST["year"]."\",\"".$_POST["period"]."\",\"".$oldClassRow["subject"]."\",\"".$user["key"]."\",\";".join(";",$newAssignmentKeys).";\",\"".$_POST["day"]."\",\"$classCode\")")){
            echo "FAILED<br>";
            echo "INSERT INTO `classes`(`name`, `year`, `period`, `subject`, `teacherKey`, `assignmentKeys`, `day`,`classCode`) VALUES (\"".$oldClassRow["name"]."\",\"".$_POST["year"]."\",\"".$_POST["period"]."\",\"".$oldClassRow["subject"]."\",\"".$user["key"]."\",\";".join(";",$newAssignmentKeys).";\",\"".$_POST["day"]."\",\"$classCode\")";
            exit();
        }
        return $conn->insert_id;
    }
}
function duplicate_assignments($conn,$user,$assignmentIDs) {
    $newKeys=[];
    foreach (explode(';',$assignmentIDs) as $oldAssignmentKey) {
        if ($oldAssignmentKey==""){
            continue;
        }
        array_push($newKeys,duplicate_assignment($conn,$user,$oldAssignmentKey));
    }
    return $newKeys;
}
function duplicate_assignment($conn,$user,$assignmentID) {
    //go through assignments and create them one by one
    foreach (sql_to_array($conn,"SELECT * FROM assignments WHERE `key`=".$assignmentID." LIMIT 1") as $oldAssignmentRow) {
        //found an assignment, duplicating...
        
        $newQuestionKeys=duplicate_questions($conn,$user,$oldAssignmentRow["questions"]);
        
        if(!$conn->query("INSERT INTO `assignments`(`name`, `subject`, `chapter`, `concept`, `timeAccessible`, `timeHide`, `timeDue`, `disabled`, `questions`, `randomizeOrder`, `infiniteTries`,`teacherKey`) VALUES (\"".$oldAssignmentRow["name"]."\",\"".$oldAssignmentRow["subject"]."\",\"".$oldAssignmentRow["chapter"]."\",\"".$oldAssignmentRow["concept"]."\",".$oldAssignmentRow["timeAccessible"].",".$oldAssignmentRow["timeHide"].",".$oldAssignmentRow["timeDue"].",".$oldAssignmentRow["disabled"].",\";".join(";",$newQuestionKeys).";\",".$oldAssignmentRow["randomizeOrder"].",".$oldAssignmentRow["infiniteTries"].",\"".$user["key"]."\")")){
            echo "FAILED.<br>";
            echo "INSERT INTO `assignments`(`name`, `subject`, `chapter`, `concept`, `timeAccessible`, `timeHide`, `timeDue`, `disabled`, `questions`, `randomizeOrder`, `infiniteTries`,`teacherKey`) VALUES (\"".$oldAssignmentRow["name"]."\",\"".$oldAssignmentRow["subject"]."\",\"".$oldAssignmentRow["chapter"]."\",\"".$oldAssignmentRow["concept"]."\",".$oldAssignmentRow["timeAccessible"].",".$oldAssignmentRow["timeHide"].",".$oldAssignmentRow["timeDue"].",".$oldAssignmentRow["disabled"].",\";".join(";",$newQuestionKeys).";\",".$oldAssignmentRow["randomizeOrder"].",".$oldAssignmentRow["infiniteTries"].",\"".$user["key"]."\")";
            exit();
        }
        return $conn->insert_id;
        
    }
}
function duplicate_questions($conn,$user,$questionIDs) {
    $newKeys=[];
    foreach (explode(';',$questionIDs) as $questionID) {
        if ($questionID==""){
            continue;
        }
        array_push($newKeys,duplicate_question($conn,$user,$questionID));
    }
    return $newKeys;
}
function duplicate_question($conn,$user,$questionID) {
    foreach (sql_to_array($conn,"SELECT * FROM questions WHERE `key`=".$questionID." LIMIT 1") as $oldQuestionRow) {
        $newQuestionKeys=duplicate_questions($conn,$user,$oldQuestionRow["subQuestions"]);
        if(!$conn->query("INSERT INTO `questions`(`name`, `questionType`, `possibleAnswers`, `units`, `answer`, `hints`, `level`, `chapter`, `concept`, `subject`, `topic`, `points`, `subQuestions`,`teacherKey`) VALUES (\"" . $oldQuestionRow["name"] . "\",\"" . $oldQuestionRow["questionType"] . "\",\"" . $oldQuestionRow["possibleAnswers"] . "\",\"" . $oldQuestionRow["units"] . "\",\"" . $oldQuestionRow["answer"] . "\",\"" . $oldQuestionRow["hints"] . "\",\"" . $oldQuestionRow["level"] . "\",\"" . $oldQuestionRow["chapter"] . "\",\"" . $oldQuestionRow["concept"] . "\",\"" . $oldQuestionRow["subject"] . "\",\"" . $oldQuestionRow["topic"] . "\",\"" . $oldQuestionRow["points"] . "\",\";".join(";",$newQuestionKeys).";\",".$user["key"].")")){
            echo "FAILED<br>";
            echo "";
            exit();
        }
        return $conn->insert_id;
    }
}