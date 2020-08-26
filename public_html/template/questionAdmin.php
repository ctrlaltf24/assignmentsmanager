<?php
function template_admin_multiple_choice_question($name, $possibleAnswers,$answer,$units,$hints,$level,$subject,$chapter,$concept,$topic,$points,$questionType,$parentQuestion,$conn){
    $possibleAnswersArr = explode(";",$possibleAnswers);
    foreach (array_keys($possibleAnswersArr, "", true) as $key) {
        unset($possibleAnswersArr[$key]);
    }
    foreach (array_keys($possibleAnswersArr, $answer, true) as $key) {
        $possibleAnswersArr[$key] = "<i class=\"material-icons mdl-list__item-icon\">check</i><b>".$possibleAnswersArr[$key]."</b>";
    }
    foreach ($possibleAnswersArr as $key => $value) {
        $possibleAnswersArr[$key] = $value.$units;
    }
    $hintsArr = explode(";",$hints);
    foreach (array_keys($hintsArr, "", true) as $key) {
        unset($hintsArr[$key]);
    }
    $parentQuestionHtml="";
    if(isset($parentQuestion)&&is_numeric($parentQuestion)) {
        $parentQuestionHtml .= template_admin_key($parentQuestion,$conn)."<br>";
    }
    return template_card(str_replace("\\r\\n","<br>",$name),"<div class=\"mdl-grid\"><div class=\"mdl-cell mdl-cell--12-col\"><h5>Question Type: $questionType</h5>".(($points!="")?"<h5>Points: ".$points."</h5>":"")."</div></div><div class=\"mdl-grid\"><div class=\"mdl-cell mdl-cell--4-col\">"."<h5>Answers</h5>".template_list($possibleAnswersArr)."</div><div class=\"mdl-cell mdl-cell--4-col\"><h5>Hints</h5>".template_list($hintsArr).(($level!="")?"</div><div class=\"mdl-cell mdl-cell--4-col\"><h5>Level: ".$level."</h5>":"")."<h5>Subject: ".$subject."</h5>"."<h5>Chapter: ".$chapter."</h5>"."<h5>Concept: ".$concept."</h5>".(($topic!="")?"<h5>Topic: ".$topic."</h5>":"")."</div></div>".(($parentQuestion!="")?("<div class=\"mdl-grid\"><div class=\"mdl-cell mdl-cell--12-col\"><h2>Parent Question (for refrence)</h2>".$parentQuestionHtml."</div></div>"):""),"Edit");
}
function template_admin_POST($conn){
    switch ($_POST["questionType"]){
        case "Multiple Choice":
            return template_admin_multiple_choice_question($_POST["name"],$_POST["possibleAnswers"],$_POST["answer"],$_POST["units"],$_POST["hints"],$_POST["level"],$_POST["subject"],$_POST["chapter"],$_POST["concept"],$_POST["topic"],$_POST["points"],$_POST["questionType"],$_POST["parent-question"],$conn);
            break;
            //TODO: add other types
    }
    return "";
}
function template_admin_key($key,$conn){
    if(!$results=$conn->query("SELECT * FROM questions WHERE `key`=$key LIMIT 1")){
        log_error("failed to get questions","",$conn->error);
    }
    if($results) {
        $results->data_seek(0);
        while ($row = $results->fetch_assoc()) {
            switch ($row["questionType"]){
                case "Multiple Choice":
                    return template_admin_multiple_choice_question($row["name"],$row["possibleAnswers"],$row["answer"],$row["units"],$row["hints"],$row["level"],$row["subject"],$row["chapter"],$row["concept"],$row["topic"],$row["points"],$row["questionType"],$row["subQuestions"]);
                    break;
            }
        }
    } else {
        log_error("failed to get questions","",$conn->error);
    }
    return "";
}
