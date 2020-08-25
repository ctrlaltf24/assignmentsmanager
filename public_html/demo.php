<?php
if(isset($_GET["reset"])){
    setcookie ("TOKEN");//reset cookie
} else {
    if(isset($_COOKIE["demo"])&&$_COOKIE["demo"]){
        setcookie ("demo","");
    } else {
        setcookie ("demo","true");
    }
}
?>
<meta http-equiv="refresh" content="0; url=../">
