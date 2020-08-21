<?php
foreach ($_GET as $key => $value) {
    //echo "Key ".$key." = ".$value;
    $_GET[$key] = $conn->real_escape_string(htmlspecialchars(urldecode($value)));
}
foreach ($_POST as $key => $value) {
    $_POST[$key] = $conn->real_escape_string(htmlspecialchars($value));
}
foreach ($_COOKIE as $key => $value) {
    $_COOKIE[$key] = $conn->real_escape_string(htmlspecialchars($value));
}