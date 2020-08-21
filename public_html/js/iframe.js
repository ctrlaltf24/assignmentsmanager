window.onload = function(){
    if(document.getElementsByTagName("header").length==0){
        //ok we are in the iframe
        $(parent.document.getElementById("question")).height($(".mdl-grid").height()+16);
    }
};