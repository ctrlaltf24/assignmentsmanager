<?php
//test
require_once "../../resources/connect.php";
require_once "../template/form.php";
require_once "../template/ui.php";
require_once "../template/miscElements.php";
echo template_header(true,$logged_in,$is_teacher);
?>
<div class="mdl-grid" style="width:75%;padding-top:32px;" id="main-container">
    <div class="mdl-cell--12-col mdl-cell">
        <form action="postAssignment.php<?php if(isset($_GET["key"])){echo "?key=".$_GET["key"];}?>" method="post" id="assignment-form" class="assignment-form">
            <script>$( document ).ready(function() {$("#assignment-form input").on("click keydown blur",function() {checkSave($(".assignment-form"),$(this));});});</script>
            <input class="question-order" name="questions" style="display:none;"></input>
            <div class="mdl-card mdl-shadow--2dp" style="z-index: inherit;overflow: inherit;">
                <div class="mdl-card__title mdl-grid" style="width: 100%;">
                    <h2 class="mdl-card__title-text mdl-cell mdl-cell--12-col">Create Assignment</h2>
                </div>
                <div class="mdl-card__supporting-text">
                    <div class='mdl-grid' id='main-form'>
                        <div class="mdl-cell mdl-cell--6-col">
                            <?php
                            echo template_textField("name-assignment","name");
                            ?>
                        </div>
                        <div class="mdl-cell mdl-cell--3-col">
                            <?php
                            echo template_textField("subject-assignment","subject","Subject",true);
                            echo template_options_SQL($conn,"SELECT DISTINCT name FROM subjects ORDER BY name","subject-assignment","name");
                            ?>
                        </div>
                        <div class="mdl-cell mdl-cell--3-col">
                            <?php
                            echo template_textField("chapter-assignment","chapter","",true,'onclick="$(\'#chapter-assignment-dropdown\').click()"');
                            echo template_options_sql_xml($conn,"SELECT chapter,subject FROM assignments GROUP BY chapter ORDER BY chapter","chapter-assignment","chapter",array("subject"=>"subject-assignment"));
                            ?>
                        </div>
                        <div class="mdl-cell mdl-cell--6-col">
                            <h5>Add to Classes:</h5>
                        </div>
                        <div class="mdl-cell mdl-cell--6-col">
                            <?php
                            !$results=$conn->query("SELECT `key`,`name`,`year`,`period`,`subject` FROM classes WHERE teacherKey=\"".$user["key"]."\" ORDER BY year");
                            $results->data_seek(0);
                            while ($row = $results->fetch_assoc()) {
                                echo "<label class=\"mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect\" for=\"class_".$row["key"]."\">
                                <input type=\"checkbox\" id=\"class_".$row["key"]."\" class=\"mdl-checkbox__input\" name=\"class_".$row["key"]."\">
                                <span class=\"mdl-checkbox__label\">".$row["name"]." (Subject ".$row["subject"].", Period ".$row["period"].", Year ".$row["year"].")</span>
                            </label>";
                            }
                            ?>
                        </div>
                    </div>
                    <div class='mdl-grid' style="padding-bottom: 0px;max-height: 0px;" id='extra-form'>
                        <div class="mdl-cell mdl-cell--6-col">
                            <?php
                            echo template_textField("concept-assignment","concept","",false,'onclick="$(\'#concept-assignment-dropdown\').click()"');
                            echo template_options_sql_xml($conn,"SELECT chapter,subject,concept FROM assignments GROUP BY concept ORDER BY concept","concept-assignment","concept",array("subject"=>"subject-assignment","chapter"=>"chapter-assignment"));
                            echo template_checkbox("disabled","Disable Assignment",false);
                            echo template_checkbox("randomizeOrder","Randomize Question Order");
                            echo template_checkbox("infiniteTries","Infinite Tries");
                            ?>
                        </div>
                        <div class="mdl-cell mdl-cell--3-col">
                            <?php
                            echo template_textField("dateOpen","dateOpen","Date to release to class",true,'onclick=\'$("#timeOpen").click();\' pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" type="date" value="'.date("Y-m-d").'"');
                            echo template_textField("dateClose","dateClose","Date to hide from the class",true,'onclick=\'$("#timeClose").click();\' pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" type="date" value="'.date("Y-m-d").'"');
                            echo template_textField("dateDue","dateDue","Date that the assignment is due",true,'onclick=\'$("#timeDue").click();\' pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" type="date" value="'.date("Y-m-d").'"');
                            ?>
                        </div>
                        <div class="mdl-cell mdl-cell--3-col">
                            <?php
                            echo template_textField("timeOpen","timeOpen","Time to release to class",true,'onclick=\'$("#dateOpen").click();\' type="time" value="01:00"');
                            echo template_textField("timeClose","timeClose","Time to hide from the class",true,'onclick=\'$("#dateClose").click();\' type="time" value="01:00"');
                            echo template_textField("timeDue","timeDue","Time that the assignment is due",true,'onclick=\'$("#dateDue").click();\' type="time" value="01:00"');?>
                        </div>
                    </div>
                </div>
                <div class="mdl-card__menu">
                    <arrow class='mdl-button mdl-js-button mdl-button--icon' onclick="toggleElement($(this).parent().parent().find('.mdl-card__supporting-text #extra-form'),$(this).find('i'));">
                        <i class='material-icons'>keyboard_arrow_down</i>
                    </arrow>
                </div>
                <?php
                if (isset($_GET["key"])) {
                    echo '<script>$( window ).on( "load", function()';
                    echo '{';
                    //dealing with text boxes
                    $rowy=array();
                    $results=$conn->query("SELECT `name`,`subject`,`chapter`,`concept` FROM assignments WHERE `key`=".$_GET["key"]);
                    if($results) {
                        while ($row = $results->fetch_assoc()) {
                            $rowy = $row;
                        }
                        foreach ($rowy as $key => $value) {
                            echo '$("#' . $key . '-assignment").val("' . $value . '");';
                            if($value!=""){
                                echo '$("#' . $key . '-assignment-main").addClass("is-dirty").removeClass("is-invalid");';
                            }
                        }
                    } else {
                        echo $conn->error;
                    }
                    $results->close();
                    //dealing with checkboxes
                    $results=$conn->query("SELECT `disabled`,`randomizeOrder`,`infiniteTries` FROM assignments WHERE `key`=".$_GET["key"]);
                    if($results) {
                        while ($row = $results->fetch_assoc()) {
                            $rowy = $row;
                        }
                        foreach ($rowy as $key => $value) {
                            if($value){
                                echo '$("#' . $key . '").prop("checked",true);';
                                echo '$("#' . $key . '").parent().addClass("is-checked");';
                            } else {
                                echo '$("#' . $key . '").prop("checked",false);';
                                echo '$("#' . $key . '").parent().removeClass("is-checked");';
                            }
                        }
                    } else {
                        echo $conn->error;
                    }
                    $results->close();
                    //dealing with assignment list
                    $results=$conn->query("SELECT `key` FROM classes WHERE `assignmentKeys` LIKE '%;".$_GET["key"].";%' AND `teacherKey`=".$user["key"]);
                    if($results) {
                        while ($row = $results->fetch_assoc()) {
                            echo '$("#class_' . $row["key"] . '").prop("checked",true);';
                            echo '$("#class_' . $row["key"] . '").parent().addClass("is-checked");';
                        }
                    } else {
                        echo $conn->error;
                    }
                    $results->close();
                    echo '});</script>';
                }
                ?>
            </div><br />
            <div class="mdl-card mdl-shadow--2dp" style="z-index: inherit;overflow: inherit;">
                <div class="mdl-card__title mdl-grid" style="width: 100%;">
                    <h2 class="mdl-card__title-text mdl-cell mdl-cell--12-col">Help</h2>
                </div>
                <div class="mdl-card__supporting-text">
                    <div class='mdl-grid' id='main-help'>
                        <div class="mdl-cell mdl-cell--6-col">
                            <h5>Adding questions</h5>
                            <p>
                                Input the desired information above in the Create Assignment card. After that is done saving, the + button below will ceome clickable. Click on the + button and select your type of question. Then proceed to fill out each section. All fields on this page autosave, just look for the indicator!
                            </p>
                        </div>
                        <div class="mdl-cell mdl-cell--6-col">
                            <h5>Viewing additional options</h5>
                            <p>
                                Click on the arrow on the top left of the cards to show/hide additional options.
                            </p>
                        </div>
                    </div>
                    <div class='mdl-grid' style="padding-bottom: 0px;max-height: 0px;" id='extra-help'>
                        <div class="mdl-cell mdl-cell--6-col">
                            <h5>Adding Images</h5>s
                            <p>
                                First upload the desired image to the site with the Create Image link above. Then in your question put "~{img|NAME}" where NAME is the exact name of your image. It is helpful to copy this name from the view images page.
                                For example, ~{img|hello.png}~ would be showing an image named hello.png
                            </p>
                        </div>
                        <div class="mdl-cell mdl-cell--6-col">
                            <h5>Adding random numbers</h5>
                            <p>
                                To add a random number follow this form: "~{num|NAME|START|END|INCREMENT}~".
                                Sometimes it is nessasay to add these numbers together to get your answer. In that case use "~{alg|+OR*|VAL1|VAL2}~". You can use variable names from ~{num}~ in your ~{alg}~ statements.
                                Sometimes it is nessasay to use the alues from num again. use "~[NAME]~"
                                For example, ~{num|hello|1|50|5}~ would choose a random number named hello between 1 and 50 that is divisable by 5.

                                I am aware of how confusing this progress is and in future versions it will be easier to use.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mdl-card__menu">
                    <arrow class='mdl-button mdl-js-button mdl-button--icon' onclick="toggleElement($(this).parent().parent().find('.mdl-card__supporting-text #extra-help'),$(this).find('i'));">
                        <i class='material-icons'>keyboard_arrow_down</i>
                    </arrow>
                </div>
            </div>
        </form>
    </div>
    <div class="mdl-cell mdl-cell--10-col mdl-cell--1-offset" id="questions">
        <?php
            if(isset($_GET["key"])) {
                $resultsQuestions = $conn->query("SELECT questions FROM assignments WHERE `key`=" . $_GET["key"]);
                if ($resultsQuestions) {
                    $resultsQuestions->data_seek(0);
                    $found=false;
                    while ($row = $resultsQuestions->fetch_assoc()) {
                        $keys = explode(";", $row["questions"]);
                        foreach ($keys as $keyy => $key) {
                            if ($key != "") {
                            	$found=true;
                                $assignmentKey = $_GET["key"];
                                $questionKey = $key;
                                $showSubQuestions=true;
                                include "./noShowCreateQuestion.php";
                            }
                        }
                    }
                    if(!$found){
                    	echo '<div id="question-placeholder"></div>';
                    }
                    $resultsQuestions->close();
                }
            } else {
            	echo '<div id="question-placeholder"></div>';
            }
        ?>
    </div>
    <script>$( window ).on( "load", function(){$("#questions > div.mdl-grid").each(function(){if(this.innerHTML==""){$(this).remove();}});});</script>
    <div class="mdl-cell mdl-cell--12-col" style="padding: 0px;margin:0px;">
	    <button <?php echo (isset($_GET["key"])?"":"disabled");?> id="add_more_questions" class="mdl-button mdl-js-button mdl-button--icon" style="position: relative;left: -23px;">
	        <i class="material-icons">add</i>
	    </button>
	    <div id="add_more_questions-tooltip" class="mdl-tooltip mdl-tooltip--large" data-mdl-for="add_more_questions">Please add a name to your assignment.<br>It is recommended that you fill everything out before adding questions.</div>
	
	
	    <ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect" for="add_more_questions">
	        <?php
	        echo "<script>var questions=0;</script>";
	        $results=$conn->query("SELECT * FROM question_types WHERE 1 ORDER BY name");
	        if($results) {
	            $results->data_seek(0);
	            while ($row = $results->fetch_assoc()) {
	            	echo '<li class="mdl-menu__item" onclick="addEditableQuestion('."'".$row["name"]."'".',false,null)">'.$row["name"].'</li>';
	            }
	        } else {
	            return "ERROR";
	        }
	        ?>
	    </ul>
	
	    <div class="mdl-grid" style="width:100%;">
	        <div class="mdl-cell mdl-cell--12-col">
	            <button style="float: right;" class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent" onclick=" location.reload(); ">
	                Submit
	            </button>
	        </div>
	    </div>
	</div>
</div>
<?php
echo template_footer();
