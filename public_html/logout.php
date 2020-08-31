<?php
include "template/ui.php";
require_once "../resources/connect.php";
require_once "../resources/startsWithEndsWith.php";
//check to see if the user is in the database, if so delete them.
echo template_header(true,$logged_in,$is_teacher);
if($result = $conn->query("SELECT * FROM token WHERE token = \"".$_COOKIE["TOKEN"]."\" AND expire>".time())) {
    if ($result->num_rows != 0) {
        if(!$conn->query("DELETE FROM token WHERE token = \"".$_COOKIE["TOKEN"]."\" AND expire>".time())){
            log_error("failed to delete tokens","",$conn->error);
        }
    }
} else {
    log_error("failed to get tokens","",$conn->error);
}
// Reset google auth token
require_once "../resources/gClient.php";
$gClient->revokeToken();
echo "<a href='index.php'>Log out sucessful</a>";
?>
<?php

echo template_footer();
header("Location: ".$loginUrl);
