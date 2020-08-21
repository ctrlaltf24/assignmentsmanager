<?php
require_once "./resources/googleAuth/vendor/autoload.php";
$gClient = new Google_Client();
$gClient->setClientId("");
$gClient->setClientSecret("");
$gClient->setApplicationName("");
$gClient->setRedirectUri("");
$gClient->addScope("https://www.googleapis.com/auth/userinfo.email");
