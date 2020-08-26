<?php
require "../../staging_resources/startsWithEndsWith.php";
require_once "../../staging_resources/gClient.php";

if(isset($_GET['code'])){
    $token=$gClient->fetchAccessTokenWithAuthCode($_GET['code']);
}

$oAuth = new Google_Service_Oauth2($gClient);
$userData = $oAuth->userinfo_v2_me->get();
//echo $userData["verifiedEmail"];
if($userData["verifiedEmail"]===true){
    require_once "../../staging_resources/connect.php";
    require_once "../../staging_resources/connectAdmin.php";
    if(!$conn->query("DELETE FROM `token` WHERE `expire`<".time())){
        log_error("failed to delete tokens","",$conn->error);
    }
    if(!$conn->query("INSERT INTO `token`(`token`, `email`, `expire`, `ip`) VALUES (\"".$_COOKIE["TOKEN"]."\",\"".$userData["email"]."\",".(time()+31*24*60*60).",\""."0.0.0.0"."\")")){
        log_error("failed to insert tokens","",$conn->error);
    }
    if ($result=$conn->query("SELECT * FROM users WHERE email = \"" . $userData["email"] . "\"")) {
        while ($row = $result->fetch_assoc()) {
            $found=true;
        }
        if(!isset($found)||$found!=true) {
            header("Location: register.php");
            exit();
        } else {
            header("Location: index.php");
            exit();
        }
    } else {
        log_error("failed to get user","",$conn->error);
    }
}
header("Location: index.php");
