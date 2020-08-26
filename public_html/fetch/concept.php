<?php
require_once "../../resources/connect.php";
require_once "../template/form.php";
if(!isset($chapter)){
    $chapter=$_GET["chapter"];
}
$concepts = array();
if(!$results=$conn->query("SELECT DISTINCT concept FROM assignments WHERE chapter=\"".$chapter."\"")){
    log_error("failed to get concepts","",$conn->error);
}
$results->data_seek(0);
while ($row = $results->fetch_assoc()) {
    array_push($concepts,$row["concept"]);
}
$results->close();
if(!$results=$conn->query("SELECT DISTINCT concept FROM questions WHERE chapter=\"".$chapter."\"")){
    log_error("failed to get concepts","",$conn->error);
}
$results->data_seek(0);
while ($row = $results->fetch_assoc()) {
    array_push($concepts,$row["concept"]);
}
$results->close();
$concepts=array_unique($concepts);
foreach ($concepts as $value) {
    echo template_option($value);
}