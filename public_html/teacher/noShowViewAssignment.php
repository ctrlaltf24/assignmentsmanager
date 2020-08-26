<?php
//TODO: NOTE THIS ASSUMES ASSIGNMENTS ARE UNIQUE TO TEACHERS
require_once "../template/ui.php";
require_once "../../../staging_resources/connect.php";
require_once "../../../staging_resources/assignmentFunctions.php";
require_once "../../../staging_resources/questionFormat.php";
require_once "../template/miscElements.php";
require_once "../template/form.php";
if(!isset($_GET["key"])){
	echo '<head><meta http-equiv="refresh" conent="0; url=viewAssignments.php" /></head><body><a href="viewAssignments.php">Please choose a key.</a></body>';
	exit();
}
echo template_header(true, $logged_in, $is_teacher);?>
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--10-col-desktop mdl-cell--12-col-tablet mdl-cell--1-offset-desktop">
		<?php
		$questions=getQuestionKeys($conn,$_GET["key"],$is_teacher);
		echo '<script>$( document ).ready(function() {$(".points-input").on("click keydown blur",function() {checkSave($(this).parent().parent().parent().parent().parent().parent().parent().parent(),$(this));});});</script>';
		foreach	($questions as $question){
			$responces=array();
			if(!$result=$conn->query("SELECT * FROM `responces` WHERE `assignmentKey`=".$_GET["key"]." AND `question`=".$question." ORDER BY email,timeTaken")){
				log_error("failed to get responces","",$conn->error);
			}
			while($row=$result->fetch_assoc()){
				if(!isset($responces[$row["email"]])){
					$responces[$row["email"]]=array();
				}
				array_push($responces[$row["email"]],$row);
			}
			if(!$result=$conn->query("SELECT * FROM `questions` WHERE `key`=".$question." AND `teacherKey`=".$user["key"]." LIMIT 1")){
				log_error("failed to get questions","",$conn->error);
			}
			while($row=$result->fetch_assoc()){
				$question=$row;
			}
			$description="<div class='mdl-grid' style='padding:0px;overflow-x:auto'><table style='width:100%'>";
			foreach ($responces as $email=>$responce_email) {
				$description.="<tr>";
				$found=false;
				if(!$result=$conn->query("SELECT `firstName`,`lastName` FROM `users` WHERE `email`='".$email."' LIMIT 1")){
					log_error("failed to get users","",$conn->error);
				}
				while($row=$result->fetch_assoc()){
					$found=true;
					$description.="<td style='width:15%'>".$row["firstName"]." ".$row["lastName"]."</td>";
				}
				if(!$found){
					$description.="<td style='width:15%'>".$email."</td>";
				}
				$points=0;
				foreach ($responce_email as $responce){
					if($responce["points"]>$points){
						$points=$responce["points"];
					}
				}
				$description.="<td style='width:5%'>".template_textField_flat("$email-".$question["key"],"$email-".$question["key"],"Score",false,"value='".$points."'",true,"style='padding:0px'","points-input")."</td>";
				$description.="<td style='width:80%'>";
				foreach ($responce_email as $responce){
					#$description.="<div style='background-color:".($responce["correct"]?"lightgreen":"pink").";text-align:center;border-radius:15px;display:block;float:left;width:".(100/count($responce_email))."%;'>";
					$description.=template_chip($responce['answer'],"style='background-color:".($responce["correct"]?"lightgreen":"pink").";text-align:center;display:block;float:left;min-width:calc(".(100/count($responce_email))."% - 12px);'",'style="text-align: center;"',true,$responce["hintsReached"],"style='float:left'");
					#$description.=$responce['answer'];//TODO:implement format_text_tile_codes
					#$description.="</div>";
				}
				$description.="</td></tr>";
			}
			$description.="</table></div>";
			echo template_card(format_text_tilde_codes($question["name"],null,$user["key"]),$description);
			echo "<br>";
			#print_r($question);
			#echo "<br><br>";
			#print_r($responces);
			#echo "<br><br>";
		}
		?>
	</div>
    </div>
<?php
echo template_footer();
