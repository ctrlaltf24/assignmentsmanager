<?php
//https://secure.php.net/manual/en/function.session-create-id.php
if(isset($_COOKIE["demo"])&&$_COOKIE["demo"]){
    $demo=true;
}
session_start([
    'cookie_secure'=>true,
    'cookie_httponly'=>true,
    'cookie_lifetime'=>0,
    'sid_length'=>128,
    'use_strict_mode'=>true
]);
$conn=null;
$user=null;
require_once "logError.php";
if(isset($demo)&&$demo){
    require_once "connectDemo.php";
} else {
    require_once "connectRaw.php";
}
require_once "auth.php";
if(isset($demo)&&$demo){
    //do none of those checks
} else {
    if(isset($logged_in)&&$logged_in) {
        if(isset($is_teacher)&&$is_teacher){
            require_once "connectAdmin.php";
        } else {
            if (strstr($_SERVER["REQUEST_URI"], "teacher")) {//if the directory starts with teacher, bust them
    	           if(strpos($_SERVER["REQUEST_URI"], "teacher")!=strpos($_SERVER["REQUEST_URI"], "teacherKey")){
                    	$conn->query("INSERT INTO nosy_students (`email`, `attemptedAcssesPage`, `time`) VALUES (\"" . $user["email"] . "\",\"" . urldecode($_SERVER["REQUEST_URI"]) . "\"," . time() . ")");
                    	echo "<h1>Access Denied<br>If you are a teacher and not a nosy student who thought I wouldn't protect these pages, login <a href='../login.php'>here</a></h1>";
                    	exit();
    	           }
            }
        }
    } else {
        if (strstr($_SERVER["REQUEST_URI"], "teacher")||strstr($_SERVER["REQUEST_URI"], "studnet")) {//if the directory starts with teacher, bust them
            echo "<h1>Access Denied<br>Looks like you are not logged in, don't worry I got u, just click <a href='../login.php'>here</a> to login. Have a good day!</h1>";
            exit();
        }
    }
}
require_once "safePOSTAndGet.php";
