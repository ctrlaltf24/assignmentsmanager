<?php
require_once "../../../staging_resources/connect.php";
require_once "../template/form.php";
if(!isset($subject)){
    $subject=$_GET["subject"];
}
$chapters = array();
if(!$results=$conn->query("SELECT DISTINCT chapter FROM assignments WHERE subject=\"".$subject."\"")){
    log_error("failed to get chapters","",$conn->error);
}
$results->data_seek(0);
while ($row = $results->fetch_assoc()) {
    array_push($chapters,$row["chapter"]);
}
$results->close();
if(!$results=$conn->query("SELECT DISTINCT chapter FROM questions WHERE subject=\"".$subject."\"")){
    log_error("failed to get chapters","",$conn->error);
}
$results->data_seek(0);
while ($row = $results->fetch_assoc()) {
    array_push($chapters,$row["chapter"]);
}
$results->close();
$chapters=array_unique($chapters);
foreach ($chapters as $value) {
    echo template_option($value);
}