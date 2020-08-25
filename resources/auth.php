<?php
$is_teacher = false;
$logged_in=false;
$res = $conn->query("SELECT * FROM token WHERE token = \"" . $_COOKIE["TOKEN"] . "\" LIMIT 1");
$conn->query("UPDATE token SET `expire`=".(time()+31*24*60*60)." WHERE  token = \"" . $_COOKIE["TOKEN"] . "\"");
while ($row = $res->fetch_assoc()) {
    $user = $row;
}
if(isset($user["email"])&&$user["email"]!=""){
//if ($user!=null&&$user["ip"] === $_SERVER['REMOTE_ADDR']) {//the school apis are wierd, the last number changes over time with no noticible cause.
    if ($conn->query("SELECT email FROM teachers WHERE email = \"" . $user["email"] . "\"")->num_rows) {
        $is_teacher = true;
        $res = $conn->query("SELECT * FROM teachers WHERE email = \"" . $user["email"] . "\"");
        $res->data_seek(0);
        $row= $res->fetch_assoc();
        foreach($row as $key=>$value){
            $user[$key]=$value;
        }
    } else {
        $is_teacher = false;
        if ($res = $conn->query("SELECT classKey FROM users WHERE `email` = \"" . $user["email"] . "\"")) {
            $res->data_seek(0);
            if($row = $res->fetch_assoc()){
				foreach ($row as $key => $value) {
					$user[$key] = $value;
				}
			}
        } else {
            echo "ERR NOT USER PLS REgister";
        }
    }
    $logged_in = true;
} else {
    $logged_in = false;
    if(isset($demo)&&$demo){
        $res = $conn->query("SELECT * FROM users WHERE email = \"" . $_COOKIE["TOKEN"]."\"");
        while ($row = $res->fetch_assoc()) {
            $user=$row;
        }
        if(!isset($user)){
            $conn->query("INSERT INTO `users`(`email`, `firstName`, `lastName`, `classKey`) VALUES (\"" . $_COOKIE["TOKEN"] . "\",\"John\",\"Doe\",\";\")");
            $conn->query("INSERT INTO `teachers` (`key`, `email`, `twoDaySchedule`, `scheduleName`, `classKey`) VALUES (".$conn->insert_id.", '".$_COOKIE["TOKEN"]."', '1', '', '')");
            $res = $conn->query("SELECT * FROM users WHERE email = \"" . $_COOKIE["TOKEN"]."\"");
            while ($row = $res->fetch_assoc()) {
                $user=$row;
            }
            $found=true;
            while($found){
                $classCode=substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(8/strlen($x)) )),1,8);
                if($result = $conn->query("SELECT name FROM classes WHERE classCode=\"".$classCode."\"")){
                    if($result->num_rows===0){
                        $found=false;
                    }
                }

            }
            $conn->query("INSERT INTO `classes`(`name`, `year`, `period`, `subject`, `teacherKey`, `assignmentKeys`, `day`,`classCode`) VALUES (\"Example Class\",\"2018\",\"2\",\"Math\",\"".$user["key"]."\",\"\",\"1\",\"$classCode\")");
            $classKey=$conn->insert_id;
            $conn->query("UPDATE users SET `classKey`=\";$classKey;\" WHERE `key`=".$user["key"]);
            $conn->query("INSERT INTO `assignments` (`key`, `teacherKey`, `name`, `subject`, `chapter`, `concept`, `timeAccessible`, `timeHide`, `timeDue`, `disabled`, `questions`, `randomizeOrder`, `infiniteTries`, `extraDeleteLater`) VALUES (NULL, '".$user["key"]."', 'This is an exmaple assignment', 'Math', '2.1', 'Electromag', '-1', '-1', '-1', '0', '', '1', '1', 'Open 2018-04-24: 01:00, Due: 2018-04-24: 01:00, Close: 2018-04-24: 01:00')");
            $assignmentKey=$conn->insert_id;
            $conn->query("UPDATE classes SET `assignmentKeys`=$assignmentKey WHERE `key`=".$classKey);
            $conn->query("INSERT INTO `questions` (`name`, `questionType`, `possibleAnswers`, `units`, `answer`, `subQuestions`, `hints`, `level`, `chapter`, `concept`, `subject`, `topic`, `points`, `key`) VALUES ('There are ____ moons orbiting Jupiter.', 'Fill in the the Blank', '', '', '69', '', ';There are more than 50;Another hint? There are less than 100;', '1', '1', 'Electromag', 'Astronomy', '', '5', NULL)");
            $questionKeys="";
            $questionKeys.=";".$conn->insert_id;
            $conn->query("INSERT INTO `questions` (`name`, `questionType`, `possibleAnswers`, `units`, `answer`, `subQuestions`, `hints`, `level`, `chapter`, `concept`, `subject`, `topic`, `points`, `key`) VALUES ('How well do you like pancakes? These things: ~{img;buttermilk_pancake_image[1].jpg}~', 'Free Response Question', '', '', '', '', ';This is your opinoin not mine.;', '0', '0', 'Getting to know you', 'Misc', 'Pancakes', '5', NULL)");
            $questionKeys.=";".$conn->insert_id;

            $conn->query("INSERT INTO `questions` (`name`, `questionType`, `possibleAnswers`, `units`, `answer`, `subQuestions`, `hints`, `level`, `chapter`, `concept`, `subject`, `topic`, `points`, `key`) VALUES ('What is the name of this website?', 'Multiple Choice', ';AssignmentsManager;AssignmentManager;Pls no;Google', '', 'AssignmentsManager', '', ';the answer is within your grasp;It is on the top of your screen;', '', '0', 'General', 'Misc', '', '5', NULL)");
            $questionKeys.=";".$conn->insert_id;

            $conn->query("INSERT INTO `questions` (`name`, `questionType`, `possibleAnswers`, `units`, `answer`, `subQuestions`, `hints`, `level`, `chapter`, `concept`, `subject`, `topic`, `points`, `key`) VALUES ('Is the answer to this question true or false?', 'True or False', ';True;False;', '', 'True', '', ';You have multiple guesses, use them!;', '', '0', 'General', 'Misc', '', '5', NULL)");
            $questionKeys.=";".$conn->insert_id;

            $conn->query("INSERT INTO `questions` (`name`, `questionType`, `possibleAnswers`, `units`, `answer`, `subQuestions`, `hints`, `level`, `chapter`, `concept`, `subject`, `topic`, `points`, `key`) VALUES ('What is ~{num|nummy1|1|20|5}~ + ~{num|nummy2|1|7|1}~ ', 'Fill in the the Blank', '', '', '~{alg|+|nummy1|nummy2}~', '', '', '', '2.1', 'Electromag', 'Math', '', '5', NULL)");
            $questionKeys.=";".$conn->insert_id.";";
            $conn->query("UPDATE `assignments` SET `questions`=\"$questionKeys\" WHERE `key`=$assignmentKey");
            mkdir("../public_html/assets/images/teachers/demo/".$user["key"]);
            copy("../public_html/assets/images/teachers/demo/buttermilk_pancake_image[1].jpg","../public_html/assets/images/teachers/demo/".$user["key"]."/buttermilk_pancake_image[1].jpg");
        }
        $logged_in = true;
        $is_teacher=true;
    }
}
//}
