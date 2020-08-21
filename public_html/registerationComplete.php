<?php
require_once "template/ui.php";
require_once "../resources/connect.php";
echo template_header(true,$logged_in,$is_teacher);
//if($logged_in) {
    if(isset($_POST["first_name"])&&isset($_POST["last_name"])&&isset($_POST["class_code"])) {
        if($result=$conn->query("SELECT `key` FROM `classes` WHERE `classCode`=\"".$_POST["class_code"]."\"")) {
            while ($row = $result->fetch_assoc()) {
                $classKey = $row["key"];
            }
        }
        if(isset($classKey)){
            if (!$conn->query("INSERT INTO `users`(`email`, `firstName`, `lastName`, `classKey`) VALUES (\"" . $user["email"] . "\",\"" . $_POST["first_name"] . "\",\"" . $_POST["last_name"] . "\",\";".$classKey.";\")")) {
                echo "<h2 onload=\"window.location='index.php';\">Failure</h2>";
            } else {
                $col_id = $conn->insert_id;
                echo "<h2 onload=\"window.location='index.php';\">Success</h2>";
                header("Location: index.php");
            }
        } else {
            echo "<h2>Error, please use a valid class code. You can go back to the <a href='register.php'>register page</a>.";
        }
    } else {
        echo "<h2>Error, please use the <a href='register.php'>register page</a>.";
    }
/*} else {
    echo "<h2>You are not logged in, please <a href='login.php'>login</a>.</h2>";
}*/
template_footer();
