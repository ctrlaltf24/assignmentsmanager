<?php
require_once '../template/ui.php';
require_once '../template/miscElements.php';
require_once "../../resources/connect.php";
$showUI = isset($_GET["hideUI"])&&!$_GET["hideUI"]||!isset($_GET["hideUI"]);
echo template_header($showUI,$logged_in,$is_teacher,$user["email"]);
if(!$logged_in||!$is_teacher){
    echo "not logged in or not a teacher.<br>";
    exit();
}
$target_dir = "../assets/images/teachers/".$user["key"]."/";


 ?>
<div class="mdl-grid" <?php if($showUI){echo "style=\"width:75%\">";} ?>
    <?php
    $dir = new DirectoryIterator(dirname($target_dir."../"));
    foreach ($dir as $fileinfo) {
        if (!$fileinfo->isDot()) {
            echo "<div class=\"mdl-cell mdl-cell--6-col\">".template_card($fileinfo->getFilename(),"<img src=\"$target_dir".$fileinfo->getFilename()."\" style=\"width:100%\">")."</div>";
        }
    }
?>
</div>
<?php
echo template_footer($showUI,$logged_in,$is_teacher);
