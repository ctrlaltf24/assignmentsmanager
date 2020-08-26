<?php
require_once '../../resources/BBCodeParser/BBCodeParser.php';
function format_text_tilde_codes($question,$vars,$teacher_id,$question_number=0,$set_val=false,$conn=null,$user=null){
    if(!is_array($vars)){
        $vars=array();
    }
    //$arr= preg_split("/~{(.*?)}~/g", $question,-1,PREG_SPLIT_DELIM_CAPTURE);
    $question = htmlspecialchars($question);
    foreach ($vars as $key => $value) {
        $val = 1;
        $question=str_replace("~[" . $key . "]~", $value, $question, $val);
        $question=str_replace("~{" . $key . "}~", $value, $question, $val);
    }
    preg_match_all("/~{(.*?)}~/", $question, $arr);
    foreach ($arr[1] as $key => $value) {
        if (strstr($value, ";")||strstr($value, "|")) {
            if(strstr($value, ";")){
                $args = explode(";", $value);
            } else if(strstr($value, "|")){
                $args = explode("|", $value);
            }
            $val = 1;
            switch ($args[0]) {
                case "img"://~{img;src}~
                    if(in_array("/",str_split($args[1]))) {
                        $question = str_replace("~{" . $value . "}~", "</h2><img class=\" mdl-cell mdl-cell--12-col\" src=\"$args[1]\"><h2 class=\"mdl-card__title-text mdl-cell mdl-cell--12-col\">", $question, $val);
                    } else {
                        $question = str_replace("~{" . $value . "}~", "</h2><img class=\" mdl-cell mdl-cell--12-col\" src=\"../assets/images/teachers/$teacher_id/$args[1]\"><h2 class=\"mdl-card__title-text mdl-cell mdl-cell--12-col\">", $question, $val);
                    }
                    break;
                case "num"://~{num;name;start;end;increments}~
                    if(array_key_exists($args[1],$vars)){
                        $question = str_replace("~{" . $value . "}~", $vars[$args[1]], $question, $val);
                    } else {
                        if ($set_val) {
                            if(count($args) === 4){
                                $output = rand($args[2], $args[3]);
                            } else {
                                $output = rand($args[2], $args[3]);
                                $output-=$output%$args[4];
                            }
                            $vars[$args[1]] = $output;
                        } else {
                            $output = $vars[$args[1]];
                        }
                        $question = str_replace("~{" . $value . "}~", $output, $question, $val);
                        $question = str_replace("~{" . $args[1] . "}~", $output, $question, $val);
                        $question = str_replace("~[" . $args[1] . "]~", $output, $question, $val);
                    }
                    break;
                case "link"://~{link;text;url}~
                    $question = str_replace("~{" . $value . "}~", "<a href=\"$args[2]\">$args[1]</a>", $question, $val);
                    break;
                case "alg"://~{alg;operation;val1;val2}~
                    //handle later
                    break;
                default:
                    echo $args[1] . "EERRR test<br>";
                    break;
            }
        }
    }
    preg_match_all("/~{(.*?)}~/", $question, $arr);
    foreach ($arr[1] as $key => $value) {
        if (strstr($value, ";")||strstr($value, "|")) {
            if(strstr($value, ";")){
                $args = explode(";", $value);
            } else if(strstr($value, "|")){
                $args = explode("|", $value);
            }
            $val = 1;
            switch ($args[0]) {
                case "alg":
                    if(!is_numeric($args[2])){
                        $args[2]=$vars[$args[2]];
                    }
                    if(!is_numeric($args[3])){
                        $args[3]=$vars[$args[3]];
                    }
                    switch ($args[1]) {
                        case "*":
                            $question = str_replace("~{" . $value . "}~", $args[2] * $args[3], $question, $val);
                            break;
                        case "+":
                            $question = str_replace("~{" . $value . "}~", $args[2] + $args[3], $question, $val);
                            break;
                        case "/":
                            $question = str_replace("~{" . $value . "}~", $args[2] / $args[3], $question, $val);
                            break;
                    }
                    break;
            }
        }
    }
    $question=(new HTML_BBCodeParser())->qparse($question);
    if ($set_val) {
        $var_out = "";
        foreach ($vars as $key => $value){
            $var_out.=$key.";".$value."|";
        }
        if($var_out!="") {
            if(!$conn->query("DELETE FROM `variables` WHERE `email`=\"".$user["email"]."\" AND `question`=$question_number")){
                log_error("delete variable","",$conn->error);
            }
            if ($conn->query("INSERT INTO `variables`(`email`, `question`, `time`, `variables`) VALUES (\"" . $user["email"] . "\"," . $question_number . "," . time() . ",\"" . $var_out . "\")")) {
            } else {
                log_error("submit variable","",$conn->error);
            }
        }
        return array($question, $vars);
    } else {
        foreach ($vars as $key => $value){
            $question = str_replace("~[" . $key . "]~", $value, $question, $val);
        }
        return $question;
    }
}
