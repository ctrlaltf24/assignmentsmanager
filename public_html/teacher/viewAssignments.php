<?php
require_once "../template/ui.php";
require_once "../../resources/connect.php";
require_once "../template/miscElements.php";
require_once "../template/form.php";
require_once '../../resources/score_manager.php';
require_once '../../resources/sqlArray.php';
echo template_header(true, $logged_in, $is_teacher);?>
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--10-col-desktop mdl-cell--12-col-tablet mdl-cell--1-offset-desktop">
            <?php
            echo template_filters($conn,$user);
            require_once "../../resources/classFunctions.php";
            $header=array("Student Name"=>"Student Name");
            $max_points=array();
            $data=array();
            $HTMLClasses=array();
            $HTMLHeaderClasses=array();
            require_once "../../resources/assignmentFunctions.php";
            $header=array("Checkbox"=>"","Chapter"=>"Chapter","Name"=>"Name","Time Accessible"=>"Time Accessible","Time Due"=>"Time Due","Time Hide"=>"Time Hide","Edit"=>"Edit","Responces"=>"Responces");
            require_once "../../resources/assignmentFunctions.php";
            foreach (sql_to_array($conn,"SELECT * FROM assignments WHERE `teacherKey`=".$user["key"]." ORDER BY `key` DESC") as $row) {
                $data[$row["key"]]["Checkbox"]=template_checkbox($row["key"]."-checkbox","",false);
                $data[$row["key"]]["Chapter"]=$row["chapter"];
                $data[$row["key"]]["Name"]=$row["name"];
                $data[$row["key"]]["Time Accessible"]=$row["timeAccessible"];
                $data[$row["key"]]["Time Due"]=$row["timeDue"];
                $data[$row["key"]]["Time Hide"]=$row["timeHide"];
                $data[$row["key"]]["Edit"]=template_ripple_a("Edit","href=createAssignment.php?key=".$row["key"]);
                $data[$row["key"]]["Responces"]=template_ripple_a("Responces","href=noShowViewAssignment.php?key=".$row["key"]);
                $HTMLClasses[$row["key"]]="\"filter-subject-".stripFieldNames($row["subject"])." filter-chapter-".stripFieldNames($row["chapter"])." filter-concept-".stripFieldNames($row["concept"]);
                foreach (sql_to_array($conn,"SELECT `key` FROM classes WHERE assignmentKeys LIKE '%;".$row["key"].";%'","key") as $class){
                    $HTMLClasses[$row["key"]].=" filter-class-".$class;
                }
                $HTMLClasses[$row["key"]].="\"";
            }
            echo template_table($data,$header,"assignment-table",$HTMLClasses,$HTMLHeaderClasses);
            ?>
        </div>
    </div>
<?php
echo template_footer();
/*
Add log_error check if this is uncommented
$results = $conn->query("SELECT * FROM assignments WHERE `teacherKey`=".$user["key"]." ORDER BY `timeAccessible`,`chapter` DESC");
if ($results) {
$results->data_seek(0);
$data=array();
while ($row = $results->fetch_assoc()) {
array_push($data,array($row["chapter"],$row["name"],$row["timeAccessible"],template_ripple_a("Edit","href=createAssignment.php?key=".$row["key"])));
} else {
    log_error("failed to get sql","",$conn->error);
}
echo template_table($data,array("Chapter","Name","Time Accessible","Edit"));
echo "<script>$('.mdl-table').css()</script>";
}
*/
