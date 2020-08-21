<?php
/**
 * @param $name string text of name
 * @param $display string text to dispaly
 * @param bool $required if the text input is required or note
 * @param string $extraInput extra text on the input function
 * @param $nameAttr bool if name= attrubuite should be set or not.
 * @param $extraDiv string adds stuff to div tag
 *
 * @return string field
 */
function template_textField($id,$name,$display="",$required=true,$extraInput='',$nameAttr=true,$extraDiv="",$extraClass="",$extraBr=""){
    if($id===""){$id=$name;}
    if($display===""){
        $display=ucwords($name," ");
    }
    return "<div class=\"mdl-textfield mdl-js-textfield mdl-textfield--floating-label\" id=\"".$id."-main\" ".$extraDiv.">
                    <input class=\"mdl-textfield__input ".$extraClass."\" id=\"".$id."\" ".($nameAttr?"name=\"".$name."\" ":"").$extraInput.($required?" required":"").">
                    <label class=\"mdl-textfield__label\" for=\"".$id."\">".$display."</label>
                </div><br $extraBr>";
}
function template_textField_flat($id,$name,$display="",$required=true,$extraInput='',$nameAttr=true,$extraDiv="",$extraClass="",$extraBr=""){
    if($id===""){$id=$name;}
    if($display===""){
        $display=ucwords($name," ");
    }
    return "<div class=\"mdl-textfield mdl-js-textfield\" id=\"".$id."-main\" ".$extraDiv.">
                    <input class=\"mdl-textfield__input ".$extraClass."\" id=\"".$id."\" ".($nameAttr?"name=\"".$name."\" ":"").$extraInput.($required?" required":"").">
                    <label class=\"mdl-textfield__label\" for=\"".$id."\">".$display."</label>
                </div><br $extraBr>";
}
$bbcodeToHTML = array(
		"[b]" 		=>array("HTML",	"<strong>"),
		"[i]"		=>array("HTML",	"<em>"),
		"[u]"		=>array("HTML",	"<u>"),
		"[s]"		=>array("HTML",	"<s>"),
		"[sup]"		=>array("HTML",	"<sup>"),
		"[sub]"		=>array("HTML",	"<sub>"),
		"[left]"	=>array("CSS","align:left"),
		"[center]"	=>array("CSS",	"align:center"),
		"[right]"	=>array("CSS",	"alight:right"),
		"[justify]"	=>array("CSS",	"align:justify"),
		"[font=$2]"	=>array("CSS",	"font-family:$2"),
		"[size=$2]"	=>array("CSS",	"size:$2"),
		"[color=$2]"=>array("CSS",	"color:$2"),
		"[ul]"		=>array("HTML",	"<ul>"),
		"[li]"		=>array("HTML",	"<li>"),
		"[ol]"		=>array("HTML",	"<ol>"),
		"[quote]"	=>array("HTML",	"<blockquote>"),
		"[table]"	=>array("HTML",	"<table>"),
		"[tr]"		=>array("HTML",	"<tr>"),
		"[td]"		=>array("HTML",	"<td>"),
		"[code]"	=>array("HTML",	"<code>"),
		"[hr]"		=>array("HTML",	"<hr>"),
		
		
		
		"[table]"=>array("HTML","<table>")
);
function template_textButtons($id,$forID,$options=array(
		"Formatting"=>array(
				"Bold" 						=> array("[b]$1[/b]",																							"Icon"),
				"Italics" 					=> array("[i]$1[/i]",																							"Icon"),
				"Underline" 				=> array("[u]$1[/u]",																							"Icon"),
				"Strike through"			=>array("[s]$1[/s]",																							"Icon"),
				"Sub-script"				=>array("[sub]$1[/sub]",																						"Icon"),
				"Super-script"				=>array("[sup]$1[/sup]",																						"Icon")
		),
		"Alignment"=>array(
				"Left"						=>array("[left]$1[/left]",																						"Icon"),
				"Center"					=>array("[center]$1[/center]",																					"Icon"),
				"Right"						=>array("[right]$1[/right]",																					"Icon"),
				"Justify"					=>array("[justify]$1[/justify]",																				"Icon")
		),
		"Font"=>array(
				"Font"						=>array("[font=$2]$1[/font]",																					"Icon",		array("Arial","Arial Black","Comics Sans MS","Courier New","Georgia","Impact","Sans-serif","Serif","Time New Roman","Trebuchet MS","Verdana")),
				"Font Size"					=>array("[size=$2]$1[/font]",																					"Icon",		array("1","2","3,","4","5","6","7")),
				"Font Color"				=>array("[color=$2]$1[/color]",																					"Icon",		array("#00000","#FF5F54")),
				"Remove Formatting"			=>array("$1\$RemoveFormat\$",																					"Icon")
		),
		"Lists"=>array(
				"Bulleted List"				=>array("[ul][li]$1[/li][/ul]",																					"Icon"),
				"Numbered List"				=>array("[ol][li]$1[/li][/ol]",																					"Icon"),
				"Indent"					=>array("[quote]$1[/quote]",																					"Icon"),
				"Remove Indent"				=>array("$1\$RemoveIndent\$",																					"Icon")
		),
		"Table"=>array(
				"New Table"					=>array("[table][tr][td]$1[/td][/tr][/table]",																	"Icon"),
				"Add Row"					=>array("[/tr][tr]$1",																							"Icon"),
				"Add Cell"					=>array("[/td][td]$1",																							"Icon")
		),
		"Special Text"=>array(
				"Code"						=>array("[code]$1[/code]",																						"Icon"),
				"Quote"						=>array("[quote]$1[/quote]",																					"Icon")
		),
		"Insert"=>array(
				"Horizontal Line"			=>array("[hr]$1",																								"Icon"),
				"Image"						=>array("[img]$2[/img]$1",																						"Icon",		"Image"),
				"Image at Size"				=>array("[img=$3\$Prompt;Width\$x$4\$Prompt;Height\$]$2[/img]$1",												"Icon"),
				"External Image"			=>array("[img]$5\$Promt;Url\$[/img]$1",																			"Icon"),
				"External Image at Size"	=>array("[img=$3\$Prompt;Width\$x$4\$Prompt;Height\$]$5\$Promt;Url\$[/img]$1",									"Icon"),
				"Email"						=>array("[email=$3\$Prompt;Email\$]$4\$Prompt;Description\$[/email]$1",											"Icon"),
				"Link"						=>array("[url=$3\$Prompt;Link\$]$4\$Prompt;Description\$[/url]$1",												"Icon")
		),
		"Math Style"=>array(
				"Create Equation"			=>array("$$\$1$$",																								"Icon"),
				"Exponent"					=>array("\{$4\$Prompt;Base\$}^\{$3\$Prompt;Exponent\$}\$1",														"Icon"),
				"ADDME"						=>array("ADDME",																								"Icon")
		),
		"Random Numbers"=>array(
				"NAME ME BETTER!"			=>array("ADDME",																								"Icon"),
				"Arithmetic"				=>array("[math]$1[/math]",																						"Icon"),
				"Variable"					=>array("[var]$1[/var]",																						"Icon"),
				"Random Bounds"				=>array("[random start=$3\$Prompt;Start\$ end=$4\$Prompt;End\$]$1[/random]",									"Icon"),
				"Random Increment"			=>array("[random start=$3\$Prompt;Start\$ end=$4\$Prompt;End\$ increment=$5\$Prompt;Increment\$]$1[/random]",	"Icon")
		)
)){
	$output="";
	foreach ($options as $categoryKey => $category) {
		$output.='<a id="'.$id."_catagory_".$categoryKey.'" class="mdl-button mdl-js-button mdl-button--icon"><i class="material-icons">more_vert</i></a>';
		$output.='<ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect" for="'.$id."_catagory_".$categoryKey.'">';
    	foreach ($category as $itemKey => $item) {
    		$output.="<li class='mdl-menu__item' onclick='insertIntoElement(\"".$forID."\",\"".$item[0]."\")'>".$itemKey."</li>";
    	}
    	$output.="</ul>";
    	$output.='<div class="mdl-tooltip" data-mdl-for="'.$id."_catagory_".$categoryKey.'">'.$categoryKey.'</div>';
    }
    return $output;
}

function template_textArea($id,$name= "",$display="",$rows=2,$required=true,$extraInput='',$nameAttr=true,$extraDiv="",$extraClass=""){
    if($id===""){$id=$name;}
    if($display===""){
        $display=ucwords($name," ");
    }
    return "<div class=\"mdl-textfield mdl-js-textfield\" ".$extraDiv.">
    <textarea class=\"mdl-textfield__input ".$extraClass."\" type=\"text\" rows= \"".$rows."\" id=\"".$id."\" ".($nameAttr?"name=\"".$name."\" ":"").$extraInput.($required?" required":"")."></textarea>
    <label class=\"mdl-textfield__label\" for=\"".$id."\">".$display."</label>
  </div><br>";
}

/**
 * Adds a new dropdown based on SQL query, DOESNT ADD A FIELD
 * @param $query string SQL query to get values
 * @param $idInput string html id of the input field to updated when option is pressed
 * @param $rowName string name of the option
 * @param array array $moreRows allows move values in the option that the user clicks, each new entry into this array is formatted like this: rowname => 'some form |value| more text if u want' These are added on the end of the rowname
 * @param $extraInput string more code on input line
 * @return string options based on SQL
 */
function template_options_SQL($conn, $query, $idInput, $rowName, $moreRows=array(), $extraInput="", $attrSave=array()){
    $sqlinput ='';
    $results=$conn->query($query);
    if($results) {
        $results->data_seek(0);
        while ($row = $results->fetch_assoc()) {
            if(count($moreRows)==0){
                $sqlinput .= template_option($row[$rowName],$row[$rowName],$extraInput);
            } else {
                $display="";
                foreach ($moreRows as $key => $value) {
                    $display .= str_replace("|value|", $row[$key], $value);
                }
                $sqlinput .= template_option($row[$rowName],$display,$extraInput);
            }
            $sqlinput .= "</li>";
        }
        return "<ul class=\"mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect\" for=\"" . $idInput . "-main\" id=\"" . $idInput . "-dropdown\">" . $sqlinput . "</ul>";
    } else {
        return "ERROR";
    }
}
function template_options_sql_xml($conn, $query, $idInput, $rowName, $moreRows=array(), $extraInput="", $attrSave=array()){
    $XML ='<xml>';
    $results=$conn->query($query);
    if($results) {
        $results->data_seek(0);
        while ($row = $results->fetch_assoc()) {
            $attr="";
            foreach ($moreRows as $key => $value) {
                $attr.="$value=\'".$row[$key]."\' ";
            }
            $XML .= "<$rowName $attr>".$row[$rowName]."</$rowName>";
        }
        $XML.='</xml>';
        return template_options_xml($XML,$idInput);
    } else {
        return "ERROR";
    }
}
function template_options_xml($XML, $idInput){
    $sqlinput ='';
    return "<ul class=\"mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect\" onclick=\"updateElementXML('$XML',$('#$idInput-dropdown'))\" for=\"" . $idInput . "-main\" id=\"" . $idInput . "-dropdown\">" . $sqlinput . "</ul>";
}
function template_option($value,$display="",$extraInput=""){
    if($display===""){
        $display=$value;
    }
    return "<li class=\"mdl-menu__item\" onclick='$(this).parent().parent().removeClass(\"is-visible\");$(\"#\"+$(this).parent().attr(\"for\").replace(\"-main\",\"\")).val(\"" . $value . "\").trigger(jQuery.Event( \"keydown\", { keyCode: 64 } )).parent().addClass(\"is-dirty\");" . $extraInput . "'>" . $display;
}
/**
 * @param $name string this is used to get the value in $_POST["name"]
 * @param $alias string CANNOT BE THE SAME AS NAME
 * @param $display string display of the option in the text field
 * @param $extraDiv string adds stuff to the div tag
 * @param $required string whether or not it is a required field
 * @return string recurisve text fields
 */
function template_recursiveInput($id,$name, $alias, $display,$extraDiv,$required=true,$extraInput="",$extraElements=null){
    return "<input style=\"display: none;\" id=\"".$id."\" name=\"".$name."\">".template_textField($id,$alias,$display,$required,"onclick=\"if(!$(this).hasClass('stopCloning'))"."{"."$(this).parent().removeClass('is-dirty is-focused');
        ".((isset($extraElements)?"$('#".$extraElements."').parent().removeClass('is-dirty is-focused is-visible');":""))."
        $(this).removeAttr('required');
        $(this).parent().after('<br>' + $(this).parent()[0].outerHTML.replace(new RegExp('".$alias."', 'g'), '".$alias."z')".((isset($extraElements)?"+$('#".$extraElements."').parent()[0].outerHTML.replace(new RegExp('".$alias."', 'g'), '".$alias."z')":"")).");
        $(this).addClass('stopCloning');
        $(this).parent().addClass('is-dirty is-focused');
    }\" onkeyup=\"$('#".$id."').val('');$('.".$id."').each(function() {
        $('#".$id."').val($('#".$id."').val() + ';' + $(this).val());$('#".$id."').click();
    });\" onblur=\"$(this).parent().removeClass('is-focused');\"".$extraInput,false,$extraDiv,$id." recursive-input");
}

/**
 *
 * @param $idInput
 * @return string Returns the html for an interative calender
 */
function template_calendar($idInput){
    return "<ul class=\"mdl-menu mdl-menu--bottom-left mdl-js-menu\" for=\"".$idInput."-main\">
                    <li class=\"calendar\"><iframe id=\"".$idInput."-iframe\" class=\"calendar\" src=\"../resources/edited%20cal/index.html\" onload=\"registerClicks('".$idInput."-iframe','".$idInput."','".$idInput."-main')\"></iframe></li>
                </ul>";
}

/**
 * @param $name string name of the $_POST
 * @param $display string how it displays
 * @param bool $default if it defaults to checked
 * @return string checkbox
 */
function template_checkbox($id,$display,$default=true){
    return "<label class=\"mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect\" for=\"".$id."\">
                    <input type=\"checkbox\" id=\"".$id."\" class=\"mdl-checkbox__input\" name=\"".$id."\" ".($default?" checked":"").">
                    <span class=\"mdl-checkbox__label\">".$display."</span>
                </label>";
}
