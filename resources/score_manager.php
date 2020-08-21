<?php
require_once("questionFormat.php");
require_once("assignmentFunctions.php");
function score_question_update($conn,$email,$question,$updateAll=false) {
     $responces=array();
     if($results=$conn->query("SELECT * FROM `responces`".($updateAll?"":" WHERE `email`=\"$email\" AND `question`=$question"))){
           while ($row = $results->fetch_assoc()) {
               $responces[$row["question"]]=$row;
           }
           $results->close();
           foreach ($responces as $key=>$value) {
               $points=0;
               if($results=$conn->query("SELECT * FROM `questions` WHERE `key`=$key LIMIT 1")){
                    while ($row = $results->fetch_assoc()) {
                         $value["max_points"]=$row["points"];
                         if($value["answer"]===$row["answer"]){
                              $value["correct"]=true;
                              $points=score_calculate($value["questionAttempt"],$value["hintsReached"],$value["max_points"]);
                         } else {
                              $value["correct"]=false;
                         }
                    }
               }
               if($result = $conn->query("SELECT * FROM `hints` WHERE `email`=\"".$value["email"]."\" AND `question`=$key ORDER BY `hint` DESC LIMIT 1")){
                    $value["hintsReached"]=0;
                    while ($row = $result->fetch_assoc()) {
                        $value["hintsReached"] = $row["hint"];
                    }
                    $result->close();
               }
               if(!$conn->query("UPDATE `responces` SET `correct`=".($value["correct"]?1:0).", `points`=".$points.",`hintsReached`=".$value["hintsReached"]." WHERE `question`=$key")){
                    echo "UPDATE `responces` SET `correct`=".$value["correct"].", `points`=".$points.",`hintsReached`=".$value["hintsReached"];
               }
           }
     } else {
          echo "Connection Failed";
     }
}
function score_assignment_update($conn,$email,$assignment,$infiniteTries) {
     if($results=$conn->query("DELETE FROM `user_assignments` WHERE `assignmentKey`=$assignment AND `email`=\"$email\"")){
          $questions=getQuestionKeys($conn,$assignment);
          $completedQuestions=0;
          $totalPoints=0;
          foreach ($questions as $key => $value) {
               if($infiniteTries){
                   if($results=$conn->query("SELECT `responces`.`points` FROM `responces` INNER JOIN `questions` ON `responces`.`question`=`questions`.`key` WHERE `assignmentKey`=$assignment AND `email`=\"$email\" AND `question`=$value AND (`correct`=true OR `questions`.`questionType`=\"Free Response Question\") ORDER BY `questionAttempt` DESC LIMIT 1")){
                         while($row=$results->fetch_assoc()){
                              $completedQuestions++;
                              $totalPoints+=$row["points"];
                         }
                    }
               } else {
                    if($results=$conn->query("SELECT * FROM `responces` WHERE `assignmentKey`=$assignment AND `email`=\"$email\" AND `question`=$value ORDER BY `questionAttempt` DESC LIMIT 1")){
                         while($row=$results->fetch_assoc()){
                              $completedQuestions++;
                              $totalPoints+=$row["points"];
                         }
                    }
               }
          }
          $percent_complete=round($completedQuestions/count($questions)*100);
          $conn->query("INSERT INTO `user_assignments`(`email`, `assignmentKey`, `points`, `percentCompleted`) VALUES (\"$email\",$assignment,$totalPoints,$percent_complete)");
     } else {
          echo "delete failed.";
     }
}
function score_calculate($question_attempt,$hints_reached,$max_points){
     //TODO: add a config option on how this is calculated
     return max(array($max_points-$hints_reached-$question_attempt,0));
}
