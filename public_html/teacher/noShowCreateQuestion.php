<?php
require_once "../template/ui.php";
require_once "../../resources/connect.php";
require_once "../template/form.php";

if(!isset($_GET["question_type"])){
    if(!isset($questionKey)) {
        $results = $conn->query("SELECT * FROM question_types WHERE 1 ORDER BY name");
        if ($results) {
            $results->data_seek(0);
            while ($row = $results->fetch_assoc()) {
                $display = "";
                foreach (array("name" => "|value|", "description" => " (|value|)") as $key => $value) {
                    if ($row[$key] != "") {
                        $display .= str_replace("|value|", $row[$key], $value);
                    }
                }
                echo "<a href='?question_type=" . $row["name"] . "'>$display</a><br>";
            }
        } else {
            return "ERROR";
        }
        $results->close();
        exit();
    } else {
        $results = $conn->query("SELECT `questionType` FROM questions WHERE `key`=".$questionKey);
        if ($results) {
            $results->data_seek(0);
            while ($row = $results->fetch_assoc()) {
                $_GET["question_type"]=$row["questionType"];
            }
            $results->close();
        }
    }
}
$extraID="";
if(isset($_GET["count"])){
    $extraID="-".$_GET["count"];
}
if(isset($questionKey)){
    $extraID="-key-".$questionKey;
}
if(!isset($_GET["assignment"])&&isset($assignmentKey)){
    $_GET["assignment"]=$assignmentKey;
}
//echo template_header(false,$logged_in,$is_teacher);
if(!isset($_GET["parent-question"])){
	echo '<div class="mdl-grid">';
}
?>
    <div class="mdl-cell mdl-cell--12-col">
        <form id="question-form<?php echo $extraID?>" class="question-form<?php echo $extraID?>" action="<?php echo "https://".$_SERVER['HTTP_HOST']."/teacher/postQuestion.php".(isset($questionKey)?"?key=".$questionKey:""); ?>" method="post">
            <script>$( document ).ready(function() {$("#question-form<?php echo $extraID?> input,#question-form<?php echo $extraID?> textarea").on("click keydown blur",function() {checkSave($(".question-form<?php echo $extraID?>"),$(this));});});</script>
            <input value="<?php echo $_GET["question_type"]?>" style="display: none;" name="questionType">
            <input class="question-order" name="subQuestions" style="display:none;">
            <div class="demo-card-wide mdl-card mdl-shadow--2dp" style="z-index: inherit;overflow: inherit;">
                <?php /*<div class="mdl-card__title mdl-grid" style="width: 100%;">
                    <h2 class="mdl-card__title-text mdl-cell mdl-cell--12-col">Create Question<div class="mdl-card__subtitle-text" style="padding-top: 16px;padding-left: 8px;"><?php echo $_GET["question_type"];?></div></h2>
                </div>*/?>
                <div class="mdl-card__supporting-text">
                    <div class='mdl-grid' id='main-form'>
                        <div class="mdl-cell mdl-cell--12-col">
                            <?php
								echo template_textArea("name$extraID","name","Question");
							    $path = "https://".$_SERVER['HTTP_HOST']."/";
                            ?>
                        </div>
                        <?php
                        if($_GET["question_type"]=="Multiple Choice"){?>
                            <div class="mdl-cell mdl-cell--12-col">
                                <?php
                                echo template_recursiveInput("possibleAnswers$extraID","possibleAnswers","possibleAnswersZ","Option","style=\"margin-left: 32px;\"");
                                echo template_textField("answer$extraID","answer");
                                ?>
                            </div>
                        <?php
                        } else if ($_GET["question_type"]=="True or False") { ?>
                            <input value=";True;False;" name="possibleAnswers" style="display: none;">
                            <div class="mdl-cell mdl-cell--6-col">
                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-1<?php echo $extraID?>" style="float: right;">
                                    <input type="radio" id="option-1<?php echo $extraID?>" class="mdl-radio__button" name="answer" value="True" checked>
                                    <span class="mdl-radio__label">True</span>
                                </label>
                            </div>
                            <div class="mdl-cell mdl-cell--6-col">
                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-2<?php echo $extraID?>">
                                    <input type="radio" id="option-2<?php echo $extraID?>" class="mdl-radio__button" name="answer" value="False">
                                    <span class="mdl-radio__label">False</span>
                                </label>
                            </div>
                            <?php
                        } else if ($_GET["question_type"]=="Fill in the the Blank"){
                            echo template_textField("answer$extraID","answer");
                        }
                        ?>
                    </div>
                    <div class='mdl-grid' style="padding: 0px 8px;max-height: 0px;" id='extra-form'>
                        <div class="mdl-cell mdl-cell--6-col">
                            <?php
                            echo template_textField("level$extraID","level","",false,'onkeyup="$(this).val($(this).val().toUpperCase());"'.(isset($_GET["level"])?"value='".$_GET["level"]."'":""));
                            echo template_options_SQL($conn,"SELECT DISTINCT level FROM questions WHERE level!=\"\" ORDER BY level","level$extraID","level");
                            echo template_textField("subject$extraID","subject","",true,(isset($_GET["subject"])?"value='".$_GET["subject"]."'":""));
                            echo template_options_SQL($conn,"SELECT DISTINCT name FROM subjects ORDER BY name","subject$extraID","name",array());
                            echo template_textField("chapter$extraID","chapter","",true,'onclick="$(\'#chapter'.$extraID.'-dropdown\').click()"'.(isset($_GET["chapter"])?"value='".$_GET["chapter"]."'":""));
                            echo template_options_sql_xml($conn,"SELECT chapter,subject FROM assignments GROUP BY chapter UNION SELECT chapter,subject FROM questions GROUP BY chapter ORDER BY chapter","chapter$extraID","chapter",array("subject"=>"subject$extraID"));
                            echo template_textField("concept$extraID","concept","",false,'onclick="$(\'#concept'.$extraID.'-dropdown\').click()"'.(isset($_GET["concept"])?"value='".$_GET["concept"]."'":""));
                            echo template_options_sql_xml($conn,"SELECT chapter,subject,concept FROM assignments GROUP BY concept UNION SELECT chapter,subject,concept FROM questions GROUP BY concept ORDER BY concept","concept$extraID","concept",array("subject"=>"subject$extraID","chapter"=>"chapter$extraID"));
                            echo template_textField("topic$extraID","topic","",false,'onclick="$(\'#topic'.$extraID.'-dropdown\').click()"');
                            echo template_options_sql_xml($conn,"SELECT chapter,subject,concept,topic FROM questions GROUP BY topic ORDER BY topic","topic$extraID","topic",array("subject"=>"subject$extraID","chapter"=>"chapter$extraID"));
                            ?>
                        </div>
                        <div class="mdl-cell--6-col mdl-cell">
                            <?php
                            echo template_textField("points$extraID","points","",true,"value='10' onkeyup='$(this).val($(this).val().replace(/([^0-9])/g,\"\"))'");
                            echo template_textField("assignment$extraID","assignment","",true,(isset($_GET["assignment"])?"value='".$_GET["assignment"]."'":""),true,(isset($_GET["assignment"])?"style='display:none;'":""),"",(isset($_GET["assignment"])?"style='display:none;'":""));
                            echo template_options_SQL($conn,"SELECT * FROM assignments ORDER BY name","assignment$extraID","key",array("name"=>"|value|"));
                            echo template_textField("units$extraID","units","",false);
                            echo "<h5>Hints</h5>";
                            echo template_recursiveInput("hints$extraID","hints","h1nts","Hint",'style="margin-left: 32px;"',false);
                            ?>
                            <input name="parent-question" style="display: none;" value="<?php echo (isset($_GET["parent-question"])?$_GET["parent-question"]:"")?>">
                        </div>
                        <div class="mdl-cell mdl-cell--12-col">
                        	<a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect disable-until-save" disabled="" id="question-sub-question-button<?php echo $extraID?>">Create Sub Question</a>
                    	</div>
                    </div>
                </div>
                <div class="mdl-card__menu-wide">
	                <div>
						<arrow class='mdl-button mdl-js-button mdl-button--icon disable-until-save' disabled="" onclick="if(!$(this).is('[disabled]')){$(this).parent().parent().parent().parent().parent().parent().next().after($(this).parent().parent().parent().parent().parent().parent());updateOrder($(this).parent().parent().parent().parent().parent().parent().parent());}">
	                        <i class='material-icons'>arrow_downward</i>
	                    </arrow>
	                    <arrow class='mdl-button mdl-js-button mdl-button--icon disable-until-save' disabled="" onclick="if(!$(this).is('[disabled]')){$(this).parent().parent().parent().parent().parent().parent().prev().before($(this).parent().parent().parent().parent().parent().parent());updateOrder($(this).parent().parent().parent().parent().parent().parent().parent());}">
	                        <i class='material-icons'>arrow_upward</i>
	                    </arrow>
	                    <?php
	                    	echo template_textButtons("buttons$extraID", "name$extraID");
	                    ?>
	                </div>
	                <span class="mdl-card__title-text"></span>
                    <arrow class='mdl-button mdl-js-button mdl-button--icon' onclick="toggleElement($(this).parent().parent().find('.mdl-card__supporting-text #extra-form'),$(this).find('i'));">
                        <i class='material-icons'>keyboard_arrow_down</i>
                    </arrow>
                </div>
                <div id="question-sub-question-tooltip<?php echo $extraID?>" class="mdl-tooltip mdl-tooltip--large question-sub-question-tooltip" data-mdl-for="question-sub-question-button<?php echo $extraID?>">Please start filling out the question before adding sub questions.</div>
                <ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect question-sub-question-dropdown" style="display:none;" for="question-sub-question-button<?php echo $extraID?>">
					<?php
			        $results=$conn->query("SELECT * FROM question_types WHERE 1 ORDER BY name");
			        if($results) {
			            $results->data_seek(0);
			            while ($row = $results->fetch_assoc()) {
			            	echo '<li class="mdl-menu__item" onclick="addEditableQuestion('."'".$row["name"]."'".',true,this)">'.$row["name"].'</li>';
			            }
			        } else {
			            return "ERROR";
			        }
			        ?>
				</ul>
            </div>
            <?php
            if (isset($questionKey)) {
                echo '<script>$( window ).on( "load", function()';
                echo '{';
                //dealing with text boxes
                $rowy=array();
                $subQuestions=array();
                $results=$conn->query("SELECT `name`,`answer`,`level`,`concept`,`points`,`subject`,`units`,`chapter`,`concept`,`topic`,`subQuestions` FROM questions WHERE `key`=".$questionKey);
                if($results) {
                    while ($row = $results->fetch_assoc()) {
                        $rowy = $row;
                    }
                    $subQuestions=explode(";",$rowy["subQuestions"]);
                    foreach ($rowy as $key => $value2) {
                    	echo '$("#' . $key .$extraID. '").val("' . str_replace("\r","",str_replace("\n","",str_replace("
","\\n",$value2))) . '");';
                        if($value2!=""){
                            echo '$("#' . $key . $extraID.'").parent().addClass("is-dirty").removeClass("is-invalid");';
                            echo '$("#' . $key . $extraID.'").parent().parent().parent().parent().parent().find(".disable-until-save").removeAttr("disabled");';
                            echo '$("#' . $key . $extraID.'").parent().parent().parent().parent().parent().find(".question-sub-question-dropdown").show();';
                            echo '$("#' . $key . $extraID.'").parent().parent().parent().parent().parent().find(".question-sub-question-tooltip").hide();';
                        }
                    }
                } else {
                    echo $conn->error;
                }
                //dealing with recursive inputs
                $results->close();
                unset($rowy);
                $results=$conn->query("SELECT `subQuestions`,`hints`,`possibleAnswers` FROM questions WHERE `key`=".$questionKey);
                if($results) {
                    while ($row = $results->fetch_assoc()) {
                        $rowy = $row;
                    }
                    $subQuestions=explode(";",$rowy["subQuestions"]);
                    foreach ($rowy as $key => $value) {
                        $args=explode(";",$value);
                        $i=0;
                        foreach ($args as $key2=>$value2){
                            if($value2!="") {
                                echo '$(".'.$key.'-key-'.$questionKey.'").click();';
                                echo '$($(".'.$key.'-key-'.$questionKey.'")['.$i.']).val("'.str_replace("
","\\n",$value2).'");';
                                $i++;
                            }
                        }
                        echo '$(".'.$key.'-key-'.$questionKey.'").parent().removeClass("is-invalid").removeClass("is-focused");';
                        unset($i);
                    }
                    $results->close();
                    unset($rowy);
                } else {
                    echo $conn->error;
                }
                echo '});</script>';
            }
            ?>
        </form>
    </div>
<?php
//ECHO SUB QUESTIONS
if(isset($showSubQuestions)&&$subQuestions!=null&&is_array($subQuestions)&&count($subQuestions)>1){
	$_GET["parent-question"]=$questionKey;
	echo "<div id=sub-questions class='mdl-cell mdl-cell--12-col' style='width: calc(100% - 64px);'>";
	foreach ($subQuestions as $value) {
		if($value!=""&&$value!=$questionKey){
			echo '<div class="mdl-grid" id="question-'.$value.'" style="width: 100%; padding-left: 5%;">';
			$questionKey=$value;
			$subQuestions=null;
			include "./noShowCreateQuestion.php";
			echo "</div>";
		}
	}
	echo "</div></div><div class='mdl-grid'>";
}
if(!isset($_GET["parent-question"])){
	echo '</div>';
}
unset($_GET["question_type"]);
