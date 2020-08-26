<?php
    require_once "miscElements.php";
    require_once "../../resources/questionFormat.php";
function template_user_question($name, $possibleAnswersArr,$answer,$units,$hints,$level,$subject,$chapter,$concept,$topic,$points,$questionType,$subQuestionsArr,$conn,$questionNumber,$question_key,$assignment_key,$randomize,$showAnswer,$proposedAnswer,$vars,$teacherKey) {
    $output = "
        <div class=\"mdl-grid\">
            <div class=\"mdl-cell mdl-cell--12-col\">";
    if($questionType==="True or False"){
        $possibleAnswersArr = array("True","False");
    }
    if (!$showAnswer) {
        if($proposedAnswer!=null){
            if($questionType==="Free Response Question"){
                 $output .= "<h4 style='color:green'>Thanks for responding! \"$proposedAnswer\"</h4>";
            } else {
                $output.="<h4 style='color:red'>Nope, the answer wasn't \"$proposedAnswer\", try again. You can do this!</h4>";
            }
        }
        $first = true;
        if($questionType==="Multiple Choice"||$questionType==="True or False"){
            foreach ($possibleAnswersArr as $key => $value) {
                //print_r(format_text_tilde_codes($value,$vars,0,$teacherKey,false));
                $output .= "<label class=\"mdl-radio mdl-js-radio mdl-js-ripple-effect".($first ? " is-checked" : "") . "\" for=\"option-$questionNumber-" . $key . "\">
          <input type=\"radio\" ".($first ? " checked" : "") . " id=\"option-$questionNumber-" . $key . "\" class=\"mdl-radio__button\" name=\"" . $questionNumber . "\" value=\"" . $value . "\" " . ($first ? "checked" : "") . ">
          <span class=\"mdl-radio__label\">".format_text_tilde_codes($value,$vars,$teacherKey)."</span>
        </label><br>";
                $first = false;
            }
        } else if ($questionType==="Fill in the the Blank"){
            $output .= "<div class=\"mdl-textfield mdl-js-textfield\">
    <input class=\"mdl-textfield__input\" type=\"text\" rows= \"3\" id=\"question-$questionNumber\" name=\"question - $questionNumber\">
    <label class=\"mdl-textfield__label\" for=\"question-$questionNumber\">Answer" . ($units != null ? " in $units" : "") . "</label>
  </div>";
        } else if ($proposedAnswer==null){
            $output .= "<div class=\"mdl-textfield mdl-js-textfield mdl-textfield--floating-label\">
    <input class=\"mdl-textfield__input\" type=\"text\" id=\"question-$questionNumber\" name=\"question - $questionNumber\">
    <label class=\"mdl-textfield__label\" for=\"question-$questionNumber\">Answer" . ($units != null ? " in $units" : "") . "</label>
  </div>";
        }
    } else {
        $items = array();
        if($questionType==="Multiple Choice"||$questionType==="True or False"){
            $answerResolved=format_text_tilde_codes($answer,$vars,$teacherKey);
            foreach ($possibleAnswersArr as $key => $value) {
                $temp = "";
                $value=format_text_tilde_codes($value,$vars,$teacherKey);
                if ($value === $answerResolved&&($showAnswer||$proposedAnswer===$answerResolved)) {
                    $temp .= "<b><span style='color:green;'>".$value."</span></b>";
                } else if ($value===$proposedAnswer) {
                    $temp .= "<b><span style='color:red';>".$value."</span></b>";
                } else {
                    $temp = $value;
                }
                array_push($items, $temp);
            }
        } else if ($questionType==="Free Response Question"){
             $output .= "<h4 style='color:green'>Thanks for responding! \"$proposedAnswer\"</h4>";
        } else if ($questionType==="Fill in the the Blank"){
            $temp = "";
            if ($showAnswer||$proposedAnswer==format_text_tilde_codes($answer, $vars,$teacherKey)) {
                $temp .= "<b><span style='color:green';>" . format_text_tilde_codes($answer, $vars,$teacherKey) . "</span></b>";
                array_push($items, $temp);
            }
            $temp = "";
            if (($proposedAnswer != format_text_tilde_codes($answer, $vars,$teacherKey))) {
                $temp .= "<b><span style='color:red';>".$proposedAnswer."</span></b>";
                array_push($items, $temp);
            }
        }
        $output .= template_list($items);
    }
    $output .= "</div><div class=\"mdl-cell mdl-cell--12-col hints-div\">";
    $hintsReached=0;
    if(!$results = $conn->query("SELECT `hintsReached` FROM responces WHERE `question`=$question_key AND `assignmentKey`=$assignment_key LIMIT 1")){
        log_error("failed to get responces","",$conn->error);
    } else {
        while ($row = $results->fetch_assoc()) {
            $hintsReached=$row["hintsReached"];
            break;
        }
    }
    if(!$results = $conn->query("SELECT `hints` FROM questions WHERE `key`=$question_key LIMIT 1")){
        log_error("failed to get questions","",$conn->error);
    }
    $results->data_seek(0);
    $hintsHtml = "";
    while ($row = $results->fetch_assoc()) {
        $hints = explode(";", $row["hints"]);
        foreach (array_keys($hints, "", true) as $key) {
            unset($hints[$key]);
        }
        $i = 0;
        $hintShown=false;
        foreach ($hints as $key => $value) {
            if (!$showAnswer&&$i<=$hintsReached) {
                $hintsHtml .= template_ripple_a("Hint " . ($i + 1), "style='float:left;' ".($i!=0?"disabled=true":"")." onclick=\"if(!$(this).is('[disabled]'))"."{var element=this;"."$.get('../fetch/hint.php?questionKey=$question_key&assignmentKey=$assignment_key&teacherKey=$teacherKey&number=$i',function(data)" . '{' . "$(element).parent().parent().find('.hints-div').children()[".($i)."].style.display='block';$(element).parent().parent().find('.hints-div').children()[".($i)."].after(data);$(element).attr('disabled','true');if($(element).parent().length>".($i-1)."){"."$($(element).parent().children()[".($i+1)."]).removeAttr('disabled');}});".'}'."\"");
                $output .= "<br style=\"display: none;\">";
            } else {
                if(!$hintShown){
                    $output.="<h4>Hints</h4>";
                    $hintShown=true;
                }
                $output .= "<p>".format_text_tilde_codes($value,$vars,$teacherKey)."</p>";
            }
            $i++;
        }
    }
    $output.="</div>
        </div>";
    return ($showAnswer?"<div style='margin-bottom:14px'>":("<form action=\"postQuestion.php?assignmentKey=$assignment_key&questionKey=$question_key&questionNumber=$questionNumber&teacherKey=$teacherKey\" method=\"post\">")).template_card(str_replace("\\r\\n","<br>",$questionNumber.". ".format_text_tilde_codes($name,$vars,$teacherKey).($level!=""?(" (".$level.")"):"")),$output,($showAnswer?"":($hintsHtml.template_button("Submit","style='float:right;'")))).($showAnswer?"</div>":("</form>"));
}
function template_user_key($question_key,$conn,$user,$questionNumber,$assignment_key,$randomize,$infinite_tries,$teacherKey,$showAnswer=false,$proposedAnswer="",$showSubQuestions=true){
    //check to see if the answer has already been submitted, if so re-call this function with correct arguments
    if($proposedAnswer===""){
        if($results=$conn->query("SELECT `answer`,`correct` FROM `responces` WHERE `email`=\"".$user["email"]."\" AND `assignmentKey`=$assignment_key AND `question`=$question_key ORDER BY `timeTaken` DESC LIMIT 1")){
            $correct=false;
            $answer="";
            while($row=$results->fetch_assoc()){
                $correct=$row["correct"];
                $answer=$row["answer"];
            }
            $results->close();
            if($answer!=""){
                if($infinite_tries&&!$correct){
                    return template_user_key($question_key,$conn,$user,$questionNumber,$assignment_key,$randomize,$infinite_tries,$teacherKey,false,$answer,true);
                } else {
                    return template_user_key($question_key,$conn,$user,$questionNumber,$assignment_key,$randomize,$infinite_tries,$teacherKey,true,$answer,true);
                }
            }
        } else {
            log_error("failed to get responces","SELECT `answer`,`correct` FROM `responces` WHERE `email`=\"".$user["email"]."\" AND `assignmentKey`=$assignment_key AND `question`=$question_key ORDER BY `timeTaken` DESC LIMIT 1",$conn->error);
        }
    }
    if($results=$conn->query("SELECT * FROM questions WHERE `key`=$question_key LIMIT 1")) {
        $rowy=null;
        while ($row = $results->fetch_assoc()) {
            $rowy=$row;
        }
        $results->close();
        if($rowy!=null){
            if(!$result=$conn->query("SELECT `variables` FROM `variables` WHERE `email`=\"".$user["email"]."\" AND `question`=$question_key")){
                log_error("failed to get variables","",$conn->error);
            }
            $vars=array();
            while($row=$result->fetch_assoc()){
                foreach (explode("|",$row["variables"]) as $key => $value){
                    if($value!="") {
                        $vars[explode(";", $value)[0]] = explode(";", $value)[1];
                    }
                }
            }
            $vars=format_text_tilde_codes($rowy["name"],$vars,$teacherKey,$question_key,true,$conn,$user)[1];
            $result->close();
            $possibleAnswersArr = explode(";", $rowy["possibleAnswers"]);
            foreach (array_keys($possibleAnswersArr, "", true) as $key) {
                unset($possibleAnswersArr[$key]);
            }
            if ($randomize) {
                shuffle($possibleAnswersArr);
            }
            foreach ($possibleAnswersArr as $key => $value) {
                $possibleAnswersArr[$key] = $value .($rowy["units"]!=""?" ". $rowy["units"]:"");
            }
            $hintsArr = explode(";", $rowy["units"]);
            foreach (array_keys($hintsArr, "", true) as $key) {
                unset($hintsArr[$key]);
            }
            $subQuestionsArr = explode(";", $rowy["subQuestions"]);
            foreach (array_keys($subQuestionsArr, "", true) as $key) {
                unset($subQuestionsArr[$key]);
            }
            $subQuestionsHtml = "";
            $alphabet = range('a', 'z');
            $i=0;
            foreach ($subQuestionsArr as $key => $value) {
                if (is_numeric($value)) {
                    $subQuestionsHtml .= template_user_key($value, $conn,$user, $alphabet[$i], $assignment_key, $randomize,$infinite_tries,$teacherKey);
                    $i++;
                } else if ($value!="") {
                    log_error("Not a valid subquestion","",$key.">".$value);
                }
            }
            $subQuestionsHtml="<div style='margin-left:32px;'>".$subQuestionsHtml."</div>";
            if(!$showSubQuestions){
                $subQuestionsHtml="";
            }
            return template_user_question($rowy["name"],$possibleAnswersArr,$rowy["answer"],$rowy["units"],$rowy["hints"],$rowy["level"],$rowy["subject"],$rowy["chapter"],$rowy["concept"],$rowy["topic"],$rowy["points"],$rowy["questionType"],$subQuestionsArr,$conn,$questionNumber,$question_key,$assignment_key,$randomize,$showAnswer,$proposedAnswer,$vars,$teacherKey).$subQuestionsHtml;
        }
    } else {
        log_error("failed to get question","",$conn->error);
    }
    return "";
}
