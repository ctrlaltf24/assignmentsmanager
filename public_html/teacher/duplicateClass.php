<?php
require_once "../template/ui.php";
require_once "../../resources/connect.php";
require_once "../template/form.php";
$showUI = isset($_GET["hideUI"])&&!$_GET["hideUI"]||!isset($_GET["hideUI"]);
echo template_header($showUI,$logged_in,$is_teacher,$user["email"]);
if(!$logged_in||!$is_teacher){
    echo "not logged in or not a teacher.";
    exit();
}
if($showUI){echo "<div class=\"mdl-grid\" style=\"width:75%\">";}
?>
<div class="mdl-cell mdl-cell--12-col">
    <h5>Duplicate Class</h5>
    <form action="./postDuplicateClass.php" method="post">
        <?php
        echo template_textField("","class","Class ID");
        echo template_options_SQL($conn,"SELECT `key` FROM classes","class","key");
        echo template_textField("","period","Period");
        echo template_textField("","year","Year");
        echo template_textField("","day","Day");
        ?>
        <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent">Submit</button>
    </form>
</div>
<?php
if($showUI){echo "</div>";}

echo template_footer($showUI,$logged_in,$is_teacher);