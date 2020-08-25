<?php
include "../template/ui.php";
require_once "../../resources/connect.php";
echo template_header(true,$logged_in,$is_teacher);
//TODO: ADD SOME SORT OF SCRIPT TO MAKE SURE ALL INPUTS ARE LEGIT HERE OR IN createClass

//add subject to subjects if it is not already there
if($result = $conn->query("SELECT name FROM subjects WHERE name=\"".$_POST["subject"]."\"")){
    if($result->num_rows===0){
        if(!$conn->query("INSERT INTO subjects (`name`) VALUES (\"".$_POST["subject"]."\")")){
            log_error("insert subject","database",$conn->error);
        }
    }
} else {
    log_error("subjects find","database",$conn->error);
}
$found=true;
while($found){
    $classCode=substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(8/strlen($x)) )),1,8);
    if($result = $conn->query("SELECT name FROM classes WHERE classCode=\"".$classCode."\"")){
        if($result->num_rows===0){
            $found=false;
        }
    } else {
        log_error("find class","database",$conn->error);
    }

}
if(!$conn->query("INSERT INTO `classes`(`name`, `year`, `period`, `subject`, `teacherKey`, `assignmentKeys`, `day`,`classCode`) VALUES (\"".$_POST["name"]."\",\"".$_POST["year"]."\",\"".$_POST["period"]."\",\"".$_POST["subject"]."\",\"".$user["key"]."\",\"\",\"".$_POST["day"]."\",\"$classCode\")")){
    log_error("insert class","database",$conn->error);
}
echo $conn->error;
template_footer();
