<?php
function getQuestionKeys($conn,$assignment_key,$is_teacher=false){
    $question_keys=array();
    $result=$conn->query("SELECT `questions`,`key` FROM `assignments` WHERE ".($is_teacher?"":"`disabled`=0 AND ")."`key`=".$assignment_key." LIMIT 1");
    if($result){
        while(!is_bool($result)&&$row = $result->fetch_assoc()){
            $new_keys=explode(";",$row["questions"]);
            foreach ($new_keys as $key=>$value){
                array_push($question_keys,$value);
            }
            $question_keys=array_unique($question_keys);
            $lastLength=count($question_keys)-1;//-1 is to make it trigger at least once
            while($lastLength<count($question_keys)){
                foreach ($question_keys as $key=>$value) {
                    if ($result = $conn->query("SELECT `subQuestions` FROM questions WHERE `key`=" . $value . " LIMIT 1")) {
                        if ($row = $result->fetch_assoc()) {
                            $new_keys=explode(";",$row["subQuestions"]);
                            foreach ($new_keys as $key=>$value){
                                array_push($question_keys,$value);
                            }
                        }
                    }
                }
                $question_keys=array_unique($question_keys);
                $lastLength=count($question_keys);
            }
        }
        if(!is_bool($result)){
        	$result->close();
        }
        //this is a wierd error
    } else {
    	echo "That isn't an assignment key.";
    }
    foreach ($question_keys as $key => $value) {
         if($value===""){
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
          }
     }
     return $totalPoints;
}
function stripFieldNames($str){
    if($str==""||$str==null){
        return "empty";
    }
    return str_replace("(","",str_replace(")","",str_replace(" ","_",str_replace(":","",$str))));
}
