<?php
require_once "../template/ui.php";
require_once "../../../staging_resources/connect.php";
require_once "../template/miscElements.php";
require_once "../template/form.php";
require_once '../../../staging_resources/score_manager.php';
require_once '../../../staging_resources/sqlArray.php';
echo template_header(true, $logged_in, $is_teacher);?>
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--10-col-desktop mdl-cell--12-col-tablet mdl-cell--1-offset-desktop">
<?php
	echo template_filters($conn,$user,array("subject"=>true,"class"=>false,"chapter"=>true,"concept"=>true,"values"=>true));
    ?>
    <script>$(window).on('load',function(){
        $("div#filters,.mdl-tabs__tab").on('click',function () {
            reloadAverages();
        });
        reloadAverages();
        var lastRun = Date.now();
        function reloadAverages(delayed){
            if(Date.now()-lastRun<500){
                if(!delayed) {
                    setTimeout(function () {
                        reloadAverages(true)
                    }, 1000);
                }
                return;
            }
            var sum=0;
            var count=0;
            var spans= "";
            $("tr.averageCol").find("td:not(.averageRow):not(:first-of-type)").each(function(){
                sum=0;
                count=0;
                spans="";
                $(this).find("span").each(function(){
                    if($(".filter-class-"+$(this).attr('class').replace("hidden-class-","")+"-hidden")[0]==null){
                        sum+=parseFloat($(this).text())*parseInt($(this).attr("count"));
                        count+=parseInt($(this).attr("count"));
                    }
                    spans+=$(this)[0].outerHTML;
                });
                if(count!==0) {
                    $(this).text((Math.round(sum / count)));
                    $(this)[0].innerHTML+=spans+"<div class=\"bar1\" style=\"width: "+(Math.round(sum / count))+"%\"></div>";
                } else {
                    $(this).text(0);
                    $(this)[0].innerHTML+=spans+"<div class=\"bar1\" style=\"width: "+(0)+"%\"></div>";
                }
            });
            $("td.averageRow").each(function(){
                sum=0;
                count=0;
                $(this).parent().find("div.bar1:visible").each(function(){
                    count++;
                    sum += parseInt($(this).prop("style")["width"].replace("%",""));
                });
                if(count!==0) {
                    $(this).text(Math.round(sum / count));
                } else {
                    $(this).text(0);
                }
            });
            lastRun=Date.now();
        }
        });</script>
    <?php
    require_once "../../../staging_resources/classFunctions.php";
    require_once "../../../staging_resources/assignmentFunctions.php";
    $header=array("Student Name"=>"Student Name","Average"=>"Average");
    $max_points=array();
    $dataPoints=array();
    $dataPercent=array();
    $HTMLClasses=array();
    $HTMLHeaderClasses=array("Average"=>"averageRow");
    $sums=array();
    $year=date("Y");
    if(date("m")>=5){// School is out
        if (date("m")>=9) { //School has started again
            $year=date("Y");
        } else { // This is in between years
            $year=date("Y")." OR `year`=".(date("Y")-1);
        }
    } else { // This is from the year before.
        $year=date("Y")-1;
    }
    $dataPoints["Average"]["Student Name"]="Average";
    $dataPercent["Average"]["Student Name"]="Average";
    foreach (sql_to_array($conn,"SELECT `key` FROM `classes` WHERE (`year`=$year) AND `teacherKey`=".$user["key"]." ORDER BY `key` DESC","key") as $irrelivant => $classKey) {
    	$users=getStudentsClass($conn,$classKey);
        $assignments=getAssignmentClass($conn,$classKey);
        foreach ($assignments as $key => $value) {
            $max_points[$key]=getMaxPoints($conn,$key,$is_teacher);
            if($results=$conn->query("SELECT subject,chapter,concept FROM assignments WHERE `key`=".$key." LIMIT 1")){
                while($row=$results->fetch_assoc()){
                    $HTMLHeaderClasses[$value["key"]]="filter-subject-".stripFieldNames($row["subject"])." "."filter-chapter-".stripFieldNames($row["chapter"])." "."filter-concept-".stripFieldNames($row["concept"])." filter-class-".$classKey;
                }
            } else {
                log_error("failed to get assignments","",$conn->error);
            }
            $header[$value["key"]]=$value["name"]." (".$max_points[$key]." Points)<span style=display:none class=col-total-points>".$max_points[$key]."</span>";
        }
        $HTMLClasses[$identifier].=" filter-class-".$classKey;
        foreach ($users as $keyU => $valueU) {
            $identifier="".$valueU["lastName"]." ".$valueU["firstName"]." ".$valueU["email"];
            $HTMLClasses[$identifier].=" filter-class-".$classKey;
            $dataPoints[$identifier]["Student Name"]=$valueU["firstName"]." ".$valueU["lastName"];
            $dataPercent[$identifier]["Student Name"]=$valueU["firstName"]." ".$valueU["lastName"];
            foreach ($assignments as $keyA => $valueA) {
                if(!isset($sums[$valueA["key"]])){
                    $sums[$valueA["key"]]=array("points"=>0,"percent"=>0,"count"=>0);
                }
                if($results=$conn->query("SELECT `points`,`percentCompleted` FROM user_assignments WHERE `email`=\"".$valueU["email"]."\" AND `assignmentKey`=".$valueA["key"]." LIMIT 1")){
                    while($row=$results->fetch_assoc()){
			            $dataPoints[$identifier][$valueA["key"]]='<span class="filter-data-type-bars" style="background-color: #FF3D00;width: '.(100*$row["points"]/$max_points[$keyA]).'%;height: 100%;float: left;text-align: center;border-radius: '.((100*$row["points"]/$max_points[$keyA])==100?'15px 15px 15px 15px':'15px 0px 0px 15px').';color: white;"><p>'.$row["points"].'</p></span><span class="filter-data-type-bars" style="width: '.(100-100*$row["points"]/$max_points[$keyA]).'%;background-color: #FFCCBC;height: 100%;float: right;border-radius: '.((100*$row["points"]/$max_points[$keyA])==0?'15px 15px 15px 15px':'0px 15px 15px 0px').';"></span><p class="filter-data-type-text" style="display:none">'.$row["points"].'</p>';
    			        $dataPercent[$identifier][$valueA["key"]]='<span class="filter-data-type-bars" style="background-color: #FF3D00;width: '.$row["percentCompleted"].'%;height: 100%;float: left;text-align: center;border-radius: '.($row["percentCompleted"]==100?'15px 15px 15px 15px':'15px 0px 0px 15px').';color: white;"><p>'.$row["percentCompleted"].'%</p></span><span class="filter-data-type-bars" style="width: '.(100-$row["percentCompleted"]).'%;background-color: #FFCCBC;height: 100%;float: right;border-radius: '.($row["percentCompleted"]==0?'15px 15px 15px 15px':'0px 15px 15px 0px').';"></span><p class="filter-data-type-text">'.$row["percentCompleted"].'%</p>';
                        if(!isset($sums[$valueA["key"]][$classKey])){
                            $sums[$valueA["key"]][$classKey]=array("points"=>0,"percent"=>0,"count"=>0);
                        }
                        $sums[$valueA["key"]][$classKey]["points"]+=100*$row["points"]/$max_points[$keyA];
                        $sums[$valueA["key"]][$classKey]["percent"]+=$row["percentCompleted"];
                        $sums[$valueA["key"]][$classKey]["count"]++;
                        $sums[$valueA["key"]]["points"]+=100*$row["points"]/$max_points[$keyA];
                        $sums[$valueA["key"]]["percent"]+=$row["percentCompleted"];
                        $sums[$valueA["key"]]["count"]++;
                    }
                    $results->close();
                } else {
                    log_error("failed to get assignment cache for user","",$conn->error);
                }
            }
        }
        /*
        $sum=0;
        $count=0;
        foreach ($dataPercent["Average"] as $val){
            $sum+=(int) $val;
            $count++;
        }
        $dataPercent["Average"]["Average"]=round($sum / $count * 100) / 100;
        $sum=0;
        $count=0;
        foreach ($dataPoints["Average"] as $val){
            $sum+=(int) $val;
            $count++;
        }
        $dataPoints["Average"]["Average"]=round($sum / $count * 100) / 100;*/
    }
    foreach ($sums as $key => $value){
        $dataPoints["Average"][$key]="";
        $dataPercent["Average"][$key]="";
        foreach ($value as $classKey => $classValue) {
            if ($classValue["count"] != 0) {
                $dataPoints["Average"][$key] .= "<span style='display: none;' class='hidden-class-$classKey' count='".$classValue["count"]."'>".(round($classValue["points"] / $classValue["count"]))."</span>";
                $dataPercent["Average"][$key] .= "<span style='display: none;' class='hidden-class-$classKey' count='".$classValue["count"]."'>".(round($classValue["percent"] / $classValue["count"]))."</span>";
            }
        }
        if($value["count"]!=0) {
            $dataPoints["Average"][$key] .= (round($value["points"] / $value["count"])) . "<div class=\"bar1\" style=\"width: " . (round($value["points"] / $value["count"])) . "%\"></div>";
            $dataPercent["Average"][$key] .= (round($value["percent"] / $value["count"])) . "<div class=\"bar1\" style=\"width: " . (round($value["percent"] / $value["count"])) . "%\"></div>";
        } else {
            $dataPoints["Average"][$key] .= (0) . "<div class=\"bar1\" style=\"width: " . (0) . "%\"></div>";
            $dataPercent["Average"][$key] .= (0) . "<div class=\"bar1\" style=\"width: " . (0) . "%\"></div>";
        }
    }
    $HTMLClasses["Average"]="averageCol";
    function cmp_idendifiers($a,$b){
        if ($a=="Average"){
            return 0.1;
        } else if ($b=="Average"){
            return 1;
        } else {
            return strcmp($a,$b);
        }
    }
    uksort($dataPoints,"cmp_idendifiers");
    uksort($dataPercent,"cmp_idendifiers");
    echo template_tabs(array("Percent Complete"=>template_table($dataPercent,$header,"percent-table",$HTMLClasses,$HTMLHeaderClasses),"Points"=>template_table($dataPoints,$header,"points-table",$HTMLClasses,$HTMLHeaderClasses)));
echo "</div></div>";
echo template_footer();
