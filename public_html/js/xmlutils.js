function updateElementXML(XML,element){
    $(element)[0].innerHTML="";//clear children
    var XMLDoc = new DOMParser().parseFromString(XML,"text/xml");
    var attr ="";
    for(var i=0;i<XMLDoc.getRootNode().children[0].children.length;i++){
        var attrMatch = true;
        for(var j=0;j<XMLDoc.getRootNode().children[0].children[i].attributes.length;j++){
            attr=XMLDoc.getRootNode().children[0].children[i].getAttributeNames()[j];
            if(document.getElementById(attr).value!=""&&document.getElementById(attr).value!=XMLDoc.getRootNode().children[0].children[i].getAttribute(attr)){
                attrMatch=false;
            }
        }
        if(attrMatch&&XMLDoc.getRootNode().children[0].children[i].textContent!=""){
            $(element)[0].innerHTML+="<li class=\"mdl-menu__item\" onclick='$(this).parent().parent().removeClass(\"is-visible\");$(\"#\"+$(this).parent().attr(\"for\").replace(\"-main\",\"\")).val(\"" +XMLDoc.getRootNode().children[0].children[i].textContent+ "\").trigger(jQuery.Event( \"keydown\", { keyCode: 64 } )).parent().addClass(\"is-dirty\");'>"+XMLDoc.getRootNode().children[0].children[i].textContent+"</li>";//add children back
        }
    }
    if($(element)[0].innerHTML==""){
        for(var i=0;i<XMLDoc.getRootNode().children[0].children.length;i++){
            if(XMLDoc.getRootNode().children[0].children[i].textContent!="") {
                $(element)[0].innerHTML += "<li class=\"mdl-menu__item\" onclick='$(this).parent().parent().removeClass(\"is-visible\");$(\"#\"+$(this).parent().attr(\"for\").replace(\"-main\",\"\")).val(\"" + XMLDoc.getRootNode().children[0].children[i].textContent + "\").trigger(jQuery.Event( \"keydown\", { keyCode: 64 } )).parent().addClass(\"is-dirty\");'>" + XMLDoc.getRootNode().children[0].children[i].textContent + "</li>";//add children back
            }
        }
    }
}//onclick='$(this).parent().parent().removeClass("is-visible");$("#"+$(this).parent().attr("for").replace("-main","")).val("" +XMLDoc.getRootNode().children[0].children[i].textContent+ "").trigger(jQuery.Event( "keydown", { keyCode: 64 } )).parent().addClass("is-dirty");"