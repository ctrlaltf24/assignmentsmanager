<?php
$is_teacher = false;
$logged_in=false;
if(!$res = $conn->query("SELECT * FROM token WHERE token = \"" . $_COOKIE["TOKEN"] . "\" LIMIT 1")){
    log_error("failed to get token","",$conn->error);
}
if(!$conn->query("UPDATE token SET `expire`=".(time()+31*24*60*60)." WHERE  token = \"" . $_COOKIE["TOKEN"] . "\"")){
    log_error("failed to updated token","",$conn->error);
}
while ($row = $res->fetch_assoc()) {
    $user = $row;
}
if(isset($user["email"])&&$user["email"]!=""){
//if ($user!=null&&$user["ip"] === $_SERVER['REMOTE_ADDR']) {//the school apis are wierd, the last number changes over time with no noticible cause.
    if(!$result=$conn->query("SELECT email FROM teachers WHERE email = \"" . $user["email"] . "\"")){
        log_error("failed to get teachers","",$conn->error);
    }
    if ($result->num_rows) {
        $is_teacher = true;
        if(!$res = $conn->query("SELECT * FROM teachers WHERE email = \"" . $user["email"] . "\"")){
            log_error("failed to get teachers","",$conn->error);
        }
        $res->data_seek(0);
        $row= $res->fetch_assoc();
        foreach($row as $key=>$value){
            $user[$key]=$value;
        }
    } else {
        $is_teacher = false;
        if ($res = $conn->query("SELECT classKey FROM users WHERE `email` = \"" . $user["email"] . "\"")) {
            $res->data_seek(0);
            if($row = $res->fetch_assoc()){
				foreach ($row as $key => $value) {
					$user[$key] = $value;
				}
			}
        } else {
            log_error("failed to get user","",$conn->error);
        }
    }
    $logged_in = true;
} else {
    $logged_in = false;
}
//}
