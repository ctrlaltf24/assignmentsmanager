<?php
require "../resources/startsWithEndsWith.php";
require_once "../resources/gClient.php";

if(isset($_GET['code'])){
    $token=$gClient->fetchAccessTokenWithAuthCode($_GET['code']);
}

$oAuth = new Google_Service_Oauth2($gClient);
$userData = $oAuth->userinfo_v2_me->get();
//echo $userData["verifiedEmail"];
if($userData["verifiedEmail"]===true){
    require_once "../resources/connect.php";
    require_once "../resources/connectAdmin.php";
    $conn->query("DELETE FROM `token` WHERE `expire`<".time());
    $conn->query("INSERT INTO `token`(`token`, `email`, `expire`, `ip`) VALUES (\"".$_COOKIE["TOKEN"]."\",\"".$userData["email"]."\",".(time()+31*24*60*60).",\""."0.0.0.0"."\")");
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
    }
}
header("Location: index.php");
