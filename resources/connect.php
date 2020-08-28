<?php
//https://secure.php.net/manual/en/function.session-create-id.php
if (!isset($_COOKIE["TOKEN"])) { //Generate a new token if one isn't present
    $strong_crypto=true;
    $_COOKIE["TOKEN"]=bin2hex(openssl_random_pseudo_bytes(128,$strong_crypto));
    setcookie("TOKEN",$_COOKIE["TOKEN"],0,"/","",true,true);
} else {
    $_COOKIE["TOKEN"] = htmlspecialchars($_COOKIE["TOKEN"]);
}
$conn=null;
$user=null;
require_once "logError.php";
require_once "connectRaw.php";
$_COOKIE["TOKEN"] = $conn->real_escape_string(htmlspecialchars($_COOKIE["TOKEN"])); // Ensure no token funny business
require_once "auth.php";
if(isset($logged_in)&&$logged_in) {
    if(isset($is_teacher)&&$is_teacher){
        require_once "connectAdmin.php";
    } else {
        if (strstr($_SERVER["REQUEST_URI"], "teacher")) {//if the directory starts with teacher, bust them
                if(strpos($_SERVER["REQUEST_URI"], "teacher")!=strpos($_SERVER["REQUEST_URI"], "teacherKey")){
                    if(!$conn->query("INSERT INTO nosy_students (`email`, `attemptedAcssesPage`, `time`) VALUES (\"" . $user["email"] . "\",\"" . urldecode($_SERVER["REQUEST_URI"]) . "\"," . time() . ")")){
                        log_error("failed to insert into nosey students","",$conn->error);
                    }
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
require_once "safePOSTAndGet.php";
