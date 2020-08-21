<?php
require_once "./resources/googleAuth/vendor/autoload.php";
$gClient = new Google_Client();
$gClient->setClientId("103448050402-69rs2lg13faa9vmf2bea2mno903ttvia.apps.googleusercontent.com");
$gClient->setClientSecret("pfakNwM8lVacZ5Vfqe6sXffY");
$gClient->setApplicationName("Assignment Manager");
$gClient->setRedirectUri("https://assignmentsmanager.com/postlogin.php");
$gClient->addScope("https://www.googleapis.com/auth/userinfo.email");
