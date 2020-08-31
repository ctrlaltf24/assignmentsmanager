<?php
require_once "../template/ui.php";
require_once "../../resources/connect.php";
$showUI = isset($_GET["hideUI"])&&!$_GET["hideUI"]||!isset($_GET["hideUI"]);
echo template_header($showUI,$logged_in,$is_teacher,$user["email"]);
if(!$logged_in||!$is_teacher){
    echo "not logged in or not a teacher.";
    exit();
}
if($showUI){echo "<div class=\"mdl-grid\" style=\"width:75%\">";}
?>
<div class="mdl-cell mdl-cell--12-col">
    <h5>Upload Images</h5>
    <form id="imgSubmit" action="./postImage.php?hideUI=true" method="post" enctype="multipart/form-data">
        <input class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent" type="file" accept=".png, .jpg, .jpeg, .gif" multiple="multiple" name="fileToUpload[]" />
        <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent">Submit</button>
        <p id="imgSubmit-output"></p>
    </form>
</div>
<?php
if($showUI){echo "</div>";}

echo template_footer($showUI,$logged_in,$is_teacher);