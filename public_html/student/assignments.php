<?php
require_once "../template/ui.php";
require_once "../../resources/connect.php";
require_once "../../resources/assignmentFunctions.php";
require_once "../template/miscElements.php";
echo template_header(true, $logged_in, $is_teacher);
$classes = explode(";",$user["classKey"]);
echo "
    <div class=\"mdl-grid\">
        <div class=\"mdl-cell mdl-cell--8-col-desktop mdl-cell--12-col-tablet mdl-cell--2-offset-desktop\">";
foreach ($classes as $key=>$value) {
    if ($value!=null){
        if ($result = $conn->query("SELECT `assignmentKeys`,`name`,`period` FROM `classes` WHERE `key`=" . $value." LIMIT 1")) {
            while ($row = $result->fetch_assoc()) {
                echo "<h4>".$row["name"]." (Period ".$row["period"].")</h4><div style='margin-left: 32px;'><h4>Uncompleted Assignments</h4>";
                $keys = explode(";", $row["assignmentKeys"]);
                arsort($keys);
                $completedAssignmentsHtml="";
                foreach ($keys as $key2 => $value2) {
                    if ($value2 != null) {
                        if($results = $conn->query("SELECT `name`, `subject`, `chapter`, `concept`, `timeAccessible`, `timeHide`, `timeDue`, `disabled`,`assignments`.`key`,`user_assignments`.`percentCompleted`,`user_assignments`.`points` FROM `assignments` INNER JOIN `user_assignments` ON `assignments`.`key` = `user_assignments`.`assignmentKey` AND \"".$user["email"]."\"=`user_assignments`.`email` WHERE `assignments`.`key`=" . $value2.($is_teacher?"":" AND `disabled`=0")." LIMIT 1")) {
                            $found=false;
                            while ($row2 = $results->fetch_assoc()) {
                                $found=true;
                                if($row2["percentCompleted"]==100){
                                     $completedAssignmentsHtml.= template_assignment($row["period"],$value2,$row2["name"], $row2["concept"], $row2["chapter"], $row2["timeAccessible"], $row2["timeHide"], $row2["timeDue"], $row2["disabled"],true,$row2["percentCompleted"],$row2["points"],getMaxPoints($conn,$value2,$is_teacher));
                                } else {
                                    echo template_assignment($row["period"],$value2,$row2["name"], $row2["concept"], $row2["chapter"], $row2["timeAccessible"], $row2["timeHide"], $row2["timeDue"], $row2["disabled"],false,$row2["percentCompleted"],$row2["points"],getMaxPoints($conn,$value2,$is_teacher));
                                }
                            }
                            if(!$found){//the assignment has not been started that is why the user_assignments failed.
                                if($results = $conn->query("SELECT `name`, `subject`, `chapter`, `concept`, `timeAccessible`, `timeHide`, `timeDue`, `disabled`,`key` FROM `assignments` WHERE `key`=" . $value2.($is_teacher?"":" AND `disabled`=0")." LIMIT 1")) {
                                    while ($row2 = $results->fetch_assoc()) {
                                        echo template_assignment($row["period"],$value2,$row2["name"], $row2["concept"], $row2["chapter"], $row2["timeAccessible"], $row2["timeHide"], $row2["timeDue"], $row2["disabled"]);
                                    }
                                } else {
                                    log_error("find uncompleted assignment","databse","key $value2 isTeacher ".($is_teacher?"true":" false"));
                               }
                            }
                        } else {
                            log_error("find uncompleted assignment","databse","key $value2 isTeacher ".($is_teacher?"true":" false"). " error ".$conn->error);
                        }
                    }
                }
                echo "<h4>Completed Assignments</h4>$completedAssignmentsHtml</div>";
            }
        } else {
            log_error("find uncompleted assignment","databse","key $value ". " error ".$conn->error);
        }
    }
}
echo "</div></div>";
echo template_footer();
