<?php
require_once "../../../staging_resources/connect.php";
require_once "../../../staging_resources/duplicationUtils.php";
require_once "../template/ui.php";
$showUI = isset($_GET["hideUI"])&&!$_GET["hideUI"]||!isset($_GET["hideUI"]);
echo template_header($showUI,$logged_in,$is_teacher,$user["email"]);
if(!$logged_in||!$is_teacher){
    echo "not logged in or not a teacher.<br>";
    exit();
}
if($showUI){echo "<div class=\"mdl-grid\" style=\"width:75%\"";}
if(isset($_POST["class"])) {
    //Start deep copy of class
    echo duplicate_class($conn,$user,$_POST["class"]);
} else {
    echo "You need to submit an ID.<br>";
}
if($showUI){echo "</div>";}
echo "THIS FILE IS STILL DEBUG. PLS MOVE TO PRODUCTION.";