<?php
require_once "../../../staging_resources/connect.php";
require_once "../template/ui.php";
$showUI = isset($_GET["hideUI"])&&!$_GET["hideUI"]||!isset($_GET["hideUI"]);
echo template_header($showUI,$logged_in,$is_teacher,$user["email"]);
if(!$logged_in||!$is_teacher){
    echo "not logged in or not a teacher.<br>";
    exit();
}
if($showUI){echo "<div class=\"mdl-grid\" style=\"width:75%\"";}
if(isset($_FILES["fileToUpload"])) {
    for ($i=0;$i<count($_FILES["fileToUpload"]["name"]);$i++){
        $target_dir = "../assets/images/teachers/".$user["key"]."/";
        if(!is_dir($target_dir)) {
            mkdir($target_dir);
        }
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"][$i]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"][$i]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".<br>";
                $uploadOk = 1;
            } else {
                echo "File is not an image.<br>";
                $uploadOk = 0;
            }
        }
// Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.<br>";
            $uploadOk = 0;
        }
// Check file size
        if ($_FILES["fileToUpload"]["size"][$i] > 10000000) {
            echo "Sorry, your file is too large.<br>";
            $uploadOk = 0;
        }
// Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
            $uploadOk = 0;
        }
// Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.<br>";
// if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$i], $target_file)) {
                echo "The file " . basename($_FILES["fileToUpload"]["name"][$i]) . " has been uploaded.<br>";
                echo "<img style=\"width:100%\" src=\"$target_dir". basename($_FILES["fileToUpload"]["name"][$i])."\" />";
            } else {
                log_error("uploading file","",$_FILES["fileToUpload"]["tmp_name"][$i]." ".$target_file);
            }
        }
    }
} else {
    print_r($_FILES);
    echo "You need to post an image.<br>";
}
if($showUI){echo "</div>";}
