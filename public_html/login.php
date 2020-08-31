<?php
include "template/ui.php";
require_once "../../staging_resources/connect.php";
require_once "../../staging_resources/startsWithEndsWith.php";
//template header put in later
//$domain = ($_SERVER['HTTP_HOST'] != 'localhost'&&!endsWith($_SERVER['HTTP_HOST'],".localhost")) ? $_SERVER['HTTP_HOST'] : false;
//setcookie("token","asfdasfafajdaesf098sudlj2mqcau5 30126tpio0ju89sflkajlskdjlads",(time()+60*60*1000),"/",$domain,($domain!=false?true:false),true);
if(!$conn->query("DELETE FROM token WHERE expire<".time())){//clear out old entries)
    log_error("failed to delete tokens","",$conn->error);
}
//check to see if the user is already in the database, if so delete them.

echo template_header(true,$logged_in,$is_teacher,$user["email"]);
if($result = $conn->query("SELECT * FROM token WHERE token = \"".$_COOKIE["TOKEN"]."\" AND expire>".time())) {
    if ($result->num_rows != 0) {
        if(!$conn->query("DELETE FROM token WHERE token = \"".$_COOKIE["TOKEN"]."\" AND expire>".time())){
            log_error("failed to delete tokens","",$conn->error);
        }
    }
} else {
    log_error("failed to get tokens","",$conn->error);
}
//add token to temparary token db
// Make sure to add a check if this is uncommented
//$conn->query("INSERT INTO token VALUES (\"".$conn->real_escape_string($_COOKIE['token'])."\",\"".$conn->real_escape_string("shaabanl@hsd.k12.or.us"/*figure out how to get email here*/)."\",".(time()+60*60*1000).",\"".$_SERVER['REMOTE_ADDR']."\")");

require_once "../../staging_resources/gClient.php";
$loginUrl=$gClient->createAuthUrl();
echo "<a href='$loginUrl'>Login with google</a>";
?>
<?php /*
Prompt uses for their name sepratly and check against the google one bc `If a user deletes their account on your system, deletes the association between that account and their account on Google (“disconnects”), or deletes their Google account, you must delete all personal information you obtained from the Google API relating to them.` https://developers.google.com/+/web/policies#personal-information
<!--Google login info-->*/

echo template_footer();
header("Location: ".$loginUrl);
