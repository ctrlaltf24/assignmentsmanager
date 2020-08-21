<?php
require_once "../../resources/connect.php";
require_once "../template/form.php";
if(!isset($subject)){
    $subject=$_GET["subject"];
}
$chapters = array();
$results=$conn->query("SELECT DISTINCT chapter FROM assignments WHERE subject=\"".$subject."\"");
$results->data_seek(0);
while ($row = $results->fetch_assoc()) {
    array_push($chapters,$row["chapter"]);
}
$results->close();
$results=$conn->query("SELECT DISTINCT chapter FROM questions WHERE subject=\"".$subject."\"");
$results->data_seek(0);
while ($row = $results->fetch_assoc()) {
    array_push($chapters,$row["chapter"]);
}
$results->close();
$chapters=array_unique($chapters);
foreach ($chapters as $value) {
    echo template_option($value);
}