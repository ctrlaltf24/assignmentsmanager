<?php
require_once "../template/ui.php";
require_once "../../resources/connect.php";
echo template_header(true, $logged_in, $is_teacher);

//get assignemnt key and display questions one by one
if(isset($_GET["key"])){
    $save_results = true;
    require_once "../../resources/authFunctions.php";
    echo "<div class=\"mdl-grid\">
            <div class=\"mdl-cell mdl-cell--8-col-desktop mdl-cell--12-col-tablet mdl-cell--2-offset-desktop\">";
    if(canViewAssignment($_GET["key"],$is_teacher,$conn,$user)){
        //TODO: write website
        require_once "../template/questionUser.php";
        if($result=$conn->query("SELECT `questions`,`randomizeOrder`,`infiniteTries`,`teacherKey` FROM assignments WHERE `key`=".$_GET["key"]." LIMIT 1")) {//gets assignmentKeys that the user is in per class
            $randomize = false;
            $questions_html = array();
            while ($row = $result->fetch_assoc()) {
                $randomize = $row["randomizeOrder"];
                $questions = explode(";", $row["questions"]);
                foreach (array_keys($questions, "", true) as $key) {
                    unset($questions[$key]);
                }
                if ($randomize) {
                    shuffle($questions);
                }
                $i=0;
                foreach ($questions as $key=>$value) {
                    array_push($questions_html, template_user_key($value,$conn,$user,$i+1,$_GET["key"],$randomize,$row["infiniteTries"],$row["teacherKey"]));
                    $i++;
                }
            }
            if (count($questions_html) > 0) {
                foreach ($questions_html as $key => $item) {
                    echo $item;
                }
            } else {
                echo "There are no questions in this assignment.";
            }
        } else {
            log_error("failed to get assignments","",$conn->error);
        }
    } else {
        log_error("Permission denied. This assignment is locked, not open yet, does not belong to your, or is not in the class you are enrolled in.","");
    }
    echo "</div></div>";
} else {
    log_error("ERROR please use correct link");
}

echo template_footer();
echo "<div style=\"display:none;position: absolute;z-index: 3;width: 75%;height: 75%;margin: 6.25% 12.5%;box-shadow: 0 16px 240px 4px rgba(0,0,0,.5),0 6px 30px 5px rgba(0,0,0,.5),0 8px 10px -5px rgba(0,0,0,.5);background-color: rgba(0,0,0,1);\" id=\"fireworks\"><h1 style=\"color: white;position: relative;top: -97%;text-align: center;font-size: 7em;\">Nicely done!</h1></div>";