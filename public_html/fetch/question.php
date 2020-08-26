<?php
require_once "../../resources/connect.php";
require_once "../template/form.php";
if(!isset($level)){
    $level=$_GET["level"];
}
if(!isset($subject)){
    $subject=$_GET["subject"];
}
if(!isset($subject)){
    $chapter=$_GET["chapter"];
}
if(!isset($concept)){
    $concept=$_GET["concept"];
}
if(!isset($topic)){
    $topic=$_GET["topic"];
}
$fields = array("level"=>$level,"subject"=>$subject,"concept"=>$concept,"topic"=>$topic);
$whereClause = array();
foreach ($fields as $key => $value){
    if(strlen($value)!=0){
        array_push($whereClause,$key."=\"".$value."\"");
    }
}
if(!$results=$conn->query("SELECT * FROM questions WHERE ".($whereClause==array()?"1":join(" AND ",$whereClause)))){
    log_error("failed to get questions","",$conn->error);
}
$results->data_seek(0);
while ($row = $results->fetch_assoc()) {
    echo template_option($row["key"],$row["name"]." (".$row["questionType"].") = ".$row["answer"].$row["units"].($row["level"]!=""?(" level = ".$row["level"]):""));
}
$results->close();