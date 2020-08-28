<?php
ini_set('error_log', $_SERVER['DOCUMENT_ROOT']."/../error_log.txt");
function log_error($human_readable,$admin_readable="",$data="") {
    $id=time().hash("sha256",$human_readable.$admin_readable.$data.time());
    $e=new Exception();
    error_log("ERROR ".$id.": when visiting ".$_SERVER[REQUEST_URI]." ".$human_readable." (".$admin_readable.")". $data. " Stack trace: ".$e->getTraceAsString());
    echo("An ERROR has occurred, please tell the website administrator this code: ".$id);
    http_response_code(500);
    exit(500);
}
