<?php
function getQuestionKeys($conn,$assignment_key,$is_teacher=false){
    $question_keys=array();
    if(!$result=$conn->query("SELECT `questions`,`key` FROM `assignments` WHERE ".($is_teacher?"":"`disabled`=0 AND ")."`key`=".$assignment_key." LIMIT 1")){
        log_error("failed to get assignments","",$conn->error);
    } else {
        while(!is_bool($result)&&$row = $result->fetch_assoc()){
            $new_keys=explode(";",$row["questions"]);
            foreach ($new_keys as $key=>$value){
                array_push($question_keys,$value);
            }
            $question_keys=array_unique($question_keys);
            $lastLength=count($question_keys)-1;//-1 is to make it trigger at least once
            while($lastLength<count($question_keys)){
                foreach ($question_keys as $key=>$value) {
                    if(trim($value)==""){
                        continue;
                    }
                    if ($result = $conn->query("SELECT `subQuestions` FROM questions WHERE `key`=" . $value . " LIMIT 1")) {
                        if ($row = $result->fetch_assoc()) {
                            $new_keys=explode(";",$row["subQuestions"]);
                            foreach ($new_keys as $key=>$value){
                                array_push($question_keys,$value);
                            }
                        }
                    } else {
                        log_error("failed to get questions","",$conn->error." ".$key." => ".$value);
                    }
                }
                $question_keys=array_unique($question_keys);
                $lastLength=count($question_keys);
            }
        }
        if(!is_bool($result)){
        	$result->close();
        }
    }
    foreach ($question_keys as $key => $value) {
         if(trim($value)==""){
              unset($question_keys[$key]);
         }
    }
    return $question_keys;
}
function getMaxPoints($conn,$assignment_key,$is_teacher=false){
     $questions=getQuestionKeys($conn,$assignment_key,$is_teacher);
     $totalPoints=0;
     foreach ($questions as $key => $value) {
        if($results=$conn->query("SELECT `points` FROM `questions` WHERE `key`=$value")){
            while($row=$results->fetch_assoc()){
                $totalPoints+=$row["points"];
            }
        } else {
            log_error("failed to get sql","",$conn->error);
        }
     }
     return $totalPoints;
}