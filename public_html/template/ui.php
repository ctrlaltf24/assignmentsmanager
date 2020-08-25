<?php
/**
 * @param bool $showHeader
 */
function template_header($showHeader=true,$logged_in=false,$is_teacher=false){
    echo "<html>
<head>";
    $path = "https://".$_SERVER['HTTP_HOST']."/";
    echo "
    <title>Assignments Manager</title>
    <script src=\"".$path."js/jquery-3.2.1.min.js\"></script>
    <script src=\"".$path."js/material.min.js\"></script>
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, minimum-scale=1.0\">
    <script src=\"".$path."js/edgeCheck.js\"></script>";
    if(file_exists($_SERVER['DOCUMENT_ROOT'].urldecode(pathinfo($_SERVER['REQUEST_URI'])['dirname'])."/head/".pathinfo($_SERVER["PHP_SELF"])['filename'].".php")){
        include $_SERVER['DOCUMENT_ROOT'].urldecode(pathinfo($_SERVER['REQUEST_URI'])['dirname'])."/head/".pathinfo($_SERVER["PHP_SELF"])['filename'].".php";
    }
    $output= "
    <link rel=\"stylesheet\" type=\"text/css\" href=\"".$path."css/material.min.css\">
    <link rel=\"stylesheet\" href=\"".$path."css/MaterialIcons.css\">
    <link rel=\"stylesheet\" href=\"".$path."css/main.css\">
    <link rel='icon' href='/logoSmall.png'>";
    if(file_exists($_SERVER['DOCUMENT_ROOT'].urldecode(pathinfo($_SERVER['REQUEST_URI'])['dirname'])."/css/".pathinfo($_SERVER["PHP_SELF"])['filename'].".css")){
        echo '<link rel="stylesheet" href="'.$path.'css/'.pathinfo($_SERVER["PHP_SELF"])['filename'].'.css"';
    }
    $output.= "
</head>
<body>
<div class=\"mdl-layout__container\">
<div class=\"mdl-layout mdl-js-layout mdl-layout--fixed-header\">";
    if($showHeader){
        $output.= "<header class=\"mdl-layout__header\">
        <div class=\"mdl-layout__header-row\">
            <a href='/' style='height: 100%;'><img class=\"mdl-logo\" src='/logoSmall.png'alt='Assignments Manager'></a>
            <a href='/' class=\"mdl-layout-title\">Assignments Manager</a>
            <div class=\"mdl-layout-spacer\"></div>
            <nav class=\"mdl-navigation mdl-layout--large-screen-only\">";
        $output.=get_header_items($logged_in,$is_teacher,false);
        $output.="    </nav>
        </div>
    </header>
    <div class=\"mdl-layout__drawer\">
        <span class=\"mdl-layout-title\">Assignments Manager</span>
        <nav class=\"mdl-navigation\">";
        $output.=get_header_items($logged_in,$is_teacher,true);
        $output.="</nav>
    </div>";
    }
    $output.= "<main class=\"mdl-layout__content\">
        <div class=\"page-content\">";
    return $output;
}
function template_footer($showFooter=true){
    if(!$showFooter){return "";}
    return "                </div>
                <footer class=\"mdl-mini-footer\">
                    <div class=\"mdl-mini-footer__left-section\">
                    <div class=\"mdl-logo\">Basics</div>
                        <ul class=\"mdl-mini-footer__link-list\">
                            <li><a href=\"/legal.php\">Legal</a></li>
                        </ul>
                    </div>
                </footer>
            </main>
        </div>
    </body>
</html>";
}
function dirToArray($dir) {
    $contents = array();
    foreach (scandir($dir) as $node) {
        if ($node === '.' || $node === '..') continue;
        if (is_dir($dir . '/' . $node)) {
            $contents[$dir . '/' . $node] = dirToArray($dir . '/' . $node);
        } else {
            $contents[] = $node;
        }
    }
    return $contents;
}

/**
 * This is used as it needs to be done twice
 */
function get_header_items($logged_in=false,$is_teacher=false,$drawer=false){
    require_once $_SERVER['DOCUMENT_ROOT']."/../resources/startsWithEndsWith.php";
    $array=array();
    if($logged_in) {
        if ($is_teacher) {
            foreach (dirToArray($_SERVER['DOCUMENT_ROOT']."/teacher") as $key => $value){
                if(is_numeric($key)){//file
                    if(!startsWith($value,"post")&&!startsWith($value,"noShow")){
                    	if(startsWith(strtoupper(substr(pathinfo($value)['filename'],0,1)).substr(preg_replace("([A-Z])"," $0",pathinfo($value)['filename']),1), "Create")){
                    		$array["Create"][strtoupper(substr(pathinfo($value)['filename'],0,1)).substr(preg_replace("([A-Z])"," $0",pathinfo($value)['filename']),1)]=('https://').$_SERVER['HTTP_HOST']."/teacher/".$value;
                    	} else if (startsWith(strtoupper(substr(pathinfo($value)['filename'],0,1)).substr(preg_replace("([A-Z])"," $0",pathinfo($value)['filename']),1), "View")) {
                    		$array["View"][strtoupper(substr(pathinfo($value)['filename'],0,1)).substr(preg_replace("([A-Z])"," $0",pathinfo($value)['filename']),1)]=('https://').$_SERVER['HTTP_HOST']."/teacher/".$value;
                    	} else {
                    		$array[strtoupper(substr(pathinfo($value)['filename'],0,1)).substr(preg_replace("([A-Z])"," $0",pathinfo($value)['filename']),1)]=('https://').$_SERVER['HTTP_HOST']."/teacher/".$value;
                    	}
                    }
                } else {//folder create a dropdown
                    if(!(substr($key, -strlen("/head")) === "/head")){//if it isnt the head folder

                    }
                }
            }
            //$output .= "<a class=\"mdl-navigation__link\" href=\"\">Link</a>";
        }
        foreach (dirToArray($_SERVER['DOCUMENT_ROOT']."/student") as $key => $value){
            if(is_numeric($key)){//file
                if(!startsWith($value,"post")&&!startsWith($value,"noShow")){
                	$array["Student"][strtoupper(substr(pathinfo($value)['filename'],0,1)).substr(preg_replace("([A-Z])"," $0",pathinfo($value)['filename']),1)]=('https://').$_SERVER['HTTP_HOST']."/student/".$value;
                }
            } else {//folder create a dropdown
                if(!(substr($key, -strlen("/head")) === "/head")){//if it isnt the head folder

                }
            }
        }
        $output=get_header_html_from_array($array,$drawer);
    } else {
        $output= "<a class=\"mdl-navigation__link\" href=\"/login.php\">Login</a>";
    }
    return $output;
}
function get_header_html_from_array($array,$drawer=false){
	$output="";
	foreach ($array as $key => $value){
		if(is_array($value)){
			if($drawer){
				$output .="<a class=\"mdl-navigation__link\">".$key."</a><div style=padding-left:16px;>".get_header_html_from_array($value)."</div>";
			} else {
				$output .="<a class='mdl-navigation__link nav-dropdown__link' id='nav-dropdown-".str_replace(" ", "", $key)."' onmouseenter='$(this)[0].click();'>$key</a><ul class='mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect' onmouseleave='$(\"#nav-dropdown-".str_replace(" ", "", $key)."\")[0].click();' for='nav-dropdown-".str_replace(" ", "", $key)."'>".str_replace("mdl-navigation__link", "mdl-menu__item", get_header_html_from_array($value))."</ul>";
			}
		} else {
			$output .= "<a class=\"mdl-navigation__link\" href=\"".$value."\">".$key."</a>";
		}
	}
	return $output;
}
