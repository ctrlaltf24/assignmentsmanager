<?php
function template_list($items,$icon=null){
    $output= "<ul class=\"mdl-list\">";
    foreach ($items as $value) {
        $output.="<li class=\"mdl-list__item\">
    <span class=\"mdl-list__item-primary-content\">
      ".(($icon!=null)?"<i class=\"material-icons mdl-list__item-icon\">".$icon."</i>":"").$value."
    </span>
  </li>";
  }
  $output .= "</ul>";
    return $output;
}
function template_card($title,$description="",$read_more="",$extraButton="",$extraDiv=""){
    return "
<div class=\"mdl-card mdl-shadow--2dp\" $extraDiv>
  <div class=\"mdl-card__title mdl-grid\" style=\"width: 100%;\">
    <h2 class=\"mdl-card__title-text mdl-cell mdl-cell--12-col\">".$title."</h2>
  </div>
  <div class=\"mdl-card__supporting-text\">
    ".$description."
  </div>".($read_more!=""?"
  <div class=\"mdl-card__actions mdl-card--border\">
    $read_more
  </div>":"").($extraButton!=""?"
  <div class=\"mdl-card__menu\">
    $extraButton
  </div>":"")."
</div>";
}
function template_chip($text,$extraSpan,$extraInnerSpan="",$contact=false,$contactText="",
	$contactInnerSpan="",$color="blue"){
	return "<span class=\"mdl-chip ".($contact?'mdl-chip--contact':'')."\" $extraSpan>
		    ".($contact?"<span class=\"mdl-chip__contact mdl-color--$color mdl-color-text--white\" $contactInnerSpan>$contactText</span>":"")."
		    <span class=\"mdl-chip__text\" $extraInnerSpan>$text</span>
		</span>";
}
function template_ripple_a($display,$extra_a="",$extra_class=""){
    return "<a class=\"mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect $extra_class\" ".$extra_a.">
      ".$display."
    </a>";
}
function template_button($display,$extra_button=""){
    return "<button class=\"mdl-button mdl-js-button mdl-button--raised mdl-button--accent\"".$extra_button.">
      $display
    </button>";
}

function template_assignment($key_in_arr,$key,$name, $concept, $chapter, $timeAccessible, $timeHide, $timeDue, $disabled,$completed=false,$percent_complete=0,$points=0,$max_points=0){
    $id=stripFieldNames($name.$key_in_arr);
    return template_card($name." (Chapter $chapter) ($concept)".($disabled?" DISABLED":""),"<div id=\"p-$id\" class=\"mdl-progress mdl-js-progress\"></div>"."<script>document.querySelector('#p-$id').addEventListener('mdl-componentupgraded', function() {
    this.MaterialProgress.setProgress($percent_complete);
});</script>",template_ripple_a("Go to assignment",'href=noShowAssignment.php?key='.$key).($max_points!=0?"<span class=\"mdl-chip\">
    <span class=\"mdl-chip__text\">Points $points/$max_points</span>
</span>":""),"","","","style=\"margin-bottom: 16px;\"");
    //todo: make it hide and stuff if disabled
}

function template_table($data,$header,$id,$HTMLClasses=null,$HTMLHeaderClasses=null){
    $output= '<div id="'.$id.'"><table style="width:100%;overflow:scroll" class="mdl-data-table mdl-js-data-table mdl-shadow--2dp"><thead><tr>';
    foreach ($header as $key3 => $value3){
        $output.="<th".(isset($HTMLHeaderClasses[$key3])?" class= '$HTMLHeaderClasses[$key3]'":"").">$value3</th>";
    }
    /*$output.='</tr></thead></table><div style="max-height:650px;overflow-y:scroll;"><table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp"><thead style="height:0px;"><tr>';
	foreach ($header as $key3 => $value3){
        $output.="<th".(isset($HTMLHeaderClasses[$key3])?" class= '$HTMLHeaderClasses[$key3]'":"").">$value3</th>";
    }*/
    $output.='</tr></thead><tbody>';
    foreach ($data as $key => $value) {
        $output.="<tr".(isset($HTMLClasses[$key])?" class=".$HTMLClasses[$key]:"").">";
        foreach ($header as $key3 => $value3){
            if(array_key_exists($key3,$value)){
                $output.="<td".(isset($HTMLHeaderClasses[$key3])?" class= '$HTMLHeaderClasses[$key3]'":"").">".$value[$key3]."</td>";
            } else {
                $output.="<td".(isset($HTMLHeaderClasses[$key3])?" class= '$HTMLHeaderClasses[$key3]'":"")."></td>";
            }
        }
        $output.="</tr>";
    }

    $output.="</tbody></table></div>";//<script>function addStyleString(str) {var node = document.createElement('style');node.innerHTML = str;document.body.appendChild(node);}for(var i=1;i<".(count($header)+1).";i++){\$(\$('#$id table')[0]).find('th:nth-of-type('+i+')').width(($($('#$id table')[1]).find('th:nth-of-type('+i+')').width()-36)+'px');addStyleString('#$id table td:nth-of-type('+i+'){width:'+($($('#$id table')[1]).find('td:nth-of-type('+i+')').width())+'px}');}$($('#$id table thead')[1]).remove();</script>";
    return $output;
}

function template_tabs($data){
    $output='<div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect"><div class="mdl-tabs__tab-bar">';
    $first=true;
    foreach ($data as $key => $value) {
        $output.='<a href="#'.str_replace(" ","",$key).'-panel" class="mdl-tabs__tab'.($first?' is-active':'').'">'.$key.'</a>';
        $first=false;
    }
    $output.='</div>';
    $first=true;
    foreach ($data as $key => $value) {
        $output.='<div class="mdl-tabs__panel'.($first?' is-active':'').'" id="'.str_replace(" ","",$key).'-panel" style="overflow-x: scroll;">'.$value.'</div>';
        $first=false;
    }
    $output.='</div>';
    return $output;
}
function template_filters($conn,$user,$fields=NULL,$year=-1,$includeUnspecified=false){
    if($fields===NULL){
        $fields=array("subject"=>true,"class"=>false,"chapter"=>true,"concept"=>true);
    }
    if ($year==-1){
        if(date("m")>=5){// School is out
            if (date("m")>=9) { //School has started again
                $year=date("Y");
            } else { // This is in between years
                $year=date("Y")." OR `year`=".(date("Y")-1);
            }
        } else { // This is from the year before.
            $year=date("Y")-1;
        }
    }
    echo "<div class=\"mdl-card mdl-shadow--2dp demp-card\" style=\"padding:16px;\" id=\"filters\">
                <div class=\"mdl-card__title mdl-grid\" style=\"width: 100%;\">
                    <h2 class=\"mdl-card__title-text mdl-cell mdl-cell--12-col\">Filters</h2>
                </div>
                <div class=\"mdl-card__supporting-text mdl-grid\" style=\"padding-top: 0px;padding-bottom: 0px;\" onclick=\"\" id=\"filter-grid\">";
    			foreach ($fields as $field => $val){
                        echo "<div class=\"mdl-cell--".floor(12/sizeof($fields))."-col\">";
                        echo "<h5>".ucwords($field)."</h5>";
                        echo template_ripple_a("Select ALL","style='width: calc(50% - 32px);' id=filter-$field-all onclick='$(this).parent().find(\"input:not(:checked)\").click().click().prop(\"checked\",true).parent().addClass(\"is-checked\");'");
                        if($val==false){
                        	echo "<script>window.onload=function(){"."$('#filter-$field-none').click();};</script>";
                        }
                        echo template_ripple_a("None","style='width: calc(30% - 32px);' id=filter-$field-none onclick='$(this).parent().find(\"input:checked\").click().click().prop(\"checked\",false).parent().removeClass(\"is-checked\");'".($val?"":" onload='alert(\"test\");$(this).click();'"));
                        switch ($field) {
                            case "class":
                                foreach (sql_to_array($conn,"SELECT name,period,`key`,`year` FROM `classes` WHERE `teacherKey`=".$user["key"]." AND (`year`= $year) ORDER BY `key`") as $key => $row) {
                                    echo template_checkbox("filter-class-".stripFieldNames($row["key"]),(($year==date("Y")||$year==date("Y")-1)?"":($row["year"]." ")).$row["name"]." (P".$row["period"].")");
                                }
                                if($includeUnspecified) {
                                    echo template_checkbox("filter-class-unspecified","Unspecified Class");
                                }
                                break;
                            case "subject":
                                foreach (sql_to_array($conn,"SELECT DISTINCT subject FROM `assignments` WHERE `teacherKey`=".$user["key"],"subject") as $key => $value) {
                                    if($value==""){$value="empty";}
                                    echo template_checkbox("filter-subject-".stripFieldNames($value),$value);
                                }
                                break;
                            case "chapter":
                                foreach (sql_to_array($conn,"SELECT DISTINCT chapter FROM `assignments` WHERE `teacherKey`=".$user["key"],"chapter") as $key => $value) {
                                    if($value==""){$value="empty";}
                                    echo template_checkbox("filter-chapter-".stripFieldNames($value),$value,true);
                                }
                                break;
                            case "concept":
                                foreach (sql_to_array($conn,"SELECT DISTINCT concept FROM `assignments` WHERE `teacherKey`=".$user["key"],"concept") as $key => $value) {
                                    if($value==""){$value="empty";}
                                    echo template_checkbox("filter-concept-".stripFieldNames($value),$value);
                                }
                                break;
                            case "values":
                                echo template_checkbox("filter-data-type-bars","Progress Bars",true);
                                echo template_checkbox("filter-data-type-text","Values",false);
                                echo "<script>$(window).on('load',function(){"."$(\"#filter-data-type-text\").click().click();});</script>";
                                break;
                        }
                        echo "</div>";
                    }
                        //for some reason the click event fires twice so in order to compensate I created a loop of four values so every two steps would arive on designated values
                        echo '<script>$("#filter-grid .mdl-checkbox").click(function (){ 
                            if($("."+this.htmlFor).hasClass(this.htmlFor+"-almost-hide")) {
                                $("."+this.htmlFor).addClass(this.htmlFor+"-hidden").removeClass(this.htmlFor+"-almost-hide").css("display","none");
                                $("."+this.htmlFor).each(function(){
                                    if($(this)[0].getAttribute("hiding")!=null){
                                        $(this)[0].setAttribute("hiding",parseInt($(this)[0].getAttribute("hiding"))+1);
                                    } else {
                                        $(this)[0].setAttribute("hiding",1);
                                    }
                                });
                            } else if ($("."+this.htmlFor).hasClass(this.htmlFor+"-hidden")){
                                $("."+this.htmlFor).addClass(this.htmlFor+"-almost-show").removeClass(this.htmlFor+"-hidden");
                            } else if ($("."+this.htmlFor).hasClass(this.htmlFor+"-almost-show")){
                                $("."+this.htmlFor).removeClass(this.htmlFor+"-almost-show");
                                $("."+this.htmlFor).each(function(){
                                    if($(this)[0].getAttribute("hiding")!=null){
                                        $(this)[0].setAttribute("hiding",parseInt($(this)[0].getAttribute("hiding"))-1);
                                    } else {
                                        $(this)[0].setAttribute("hiding",0);
                                    }
                                    if($(this)[0].getAttribute("hiding")=="0"){
                                        $(this).css("display","");
                                    }
                                });
                            } else {
                                $("."+this.htmlFor).addClass(this.htmlFor+"-almost-hide");
                            }
                        });</script>';
                echo "</div>
                <div class=\"mdl-card__menu\">
                    <arrow class='mdl-button mdl-js-button mdl-button--icon' onclick=\"toggleElement($(this).parent().parent().find('.mdl-card__supporting-text'),$(this).find('i'));\">
                        <i class='material-icons'>keyboard_arrow_down</i>
                    </arrow>
                </div>
            </div>";
}
function stripFieldNames($str){
    if($str==""||$str==null){
        return "empty";
    }
    // removing special charecters and replacing them with a description to avoid collisions in ids
    return str_replace(array(" ","(",")",".","#","&",";",",","/","\\","=","[","]","{","}"),array("space","openparenthasis","closedparethisis","period","hastag","amp","semicolon","amp","comma","slash","backslash","equals","openbracket","closedbracket","opencurly","closecurley"),$str);
}
