<?php
if(isset($_GET["reset"])){
    session_unset();
    session_destroy();
    session_write_close();
    setcookie(session_name(),'',0,'/');
    session_regenerate_id(true);
} else {
    if(isset($_COOKIE["demo"])&&$_COOKIE["demo"]){
        setcookie ("demo","");
    } else {
        setcookie ("demo","true");
    }
}
?>
<meta http-equiv="refresh" content="0; url=../">
