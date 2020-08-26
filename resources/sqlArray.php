<?php
function sql_to_array($conn,$sql,$rowName=null){
    $output =array();
    if($results=$conn->query($sql)){
        //score_assignment_update($conn,$valueU["email"],$valueA["key"],true);
        while($row=$results->fetch_assoc()){
            if($rowName==null){
                array_push($output,$row);
            } else {
                array_push($output,$row[$rowName]);
            }
        }
        return $output;
    } else {
        log_error("failed to get sql","",$conn->error);
    }
}
