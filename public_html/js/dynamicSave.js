var lastsave= [];
//0 is saving, 1 is saved, 2 is failed
var chips =[
    "<span class=\"mdl-chip\" style=\"padding-left: 0;\"><span class=\"mdl-chip__contact mdl-color--grey mdl-color-text--white\"><i class=\"material-icons\" style=\"line-height: 32px;\">cached</i></span><span class=\"mdl-chip__text\">Saving</span></span>",
    "<span class=\"mdl-chip\" style=\"padding-left: 0;\"><span class=\"mdl-chip__contact mdl-color--green mdl-color-text--white\"><i class=\"material-icons\" style=\"line-height: 32px;\">check</i></span><span class=\"mdl-chip__text\">Saved</span></span>",
    "<span class=\"mdl-chip\" style=\"padding-left: 0;\"><span class=\"mdl-chip__contact mdl-color--red mdl-color-text--white\"><i class=\"material-icons\" style=\"line-height: 32px;\">error</i></span><span class=\"mdl-chip__text\">Failed</span></span>"
];
function checkSave(form,input) {
    $(input).attr("dirty","true");
    var currTime = performance.now();
    if(lastsave[$(form).attr('id')]!=-1) {
        lastsave[$(form).attr('id')]=currTime;
        if($(form).find(".mdl-card__title-text")[0].innerHTML.includes(chips[1])) {//remove existing saved
            $(form).find(".mdl-card__title-text")[0].innerHTML=$(form).find(".mdl-card__title-text")[0].innerHTML.replace(chips[1],"");
        }
        if($(form).find(".mdl-card__title-text")[0].innerHTML.includes(chips[2])) {//remove existing failed
            $(form).find(".mdl-card__title-text")[0].innerHTML=$(form).find(".mdl-card__title-text")[0].innerHTML.replace(chips[2],"");
        }
        if(!$(form).find(".mdl-card__title-text")[0].innerHTML.includes(chips[0])) {//add saving
            $(form).find(".mdl-card__title-text")[0].innerHTML += chips[0];
        }
        setTimeout(function(){
            if(currTime==lastsave[$(form).attr('id')]){
                lastsave[$(form).attr('id')]=0;
                triggerSave($(form));
            }
        },1000);
    } else {
        lastsave[$(form).attr('id')]=0;
    }
}
function triggerSave(form) {
    var postData="";
    if($(form).prop("action").includes("?key=")){
        postData=$(form).find("input[dirty=true],textarea[dirty=true]").serialize();
        $(form).find("input.mdl-checkbox__input[dirty=true]").each(function(){
            if(!$(this).is(":checked")) {
                postData += "&"+$(this).attr("name") + "=0";
            } else {
                postData=postData.replace($(this).attr("name")+"=on",$(this).attr("name") + "=1");
            }
        });
        if(postData.startsWith("&")){
            postData=postData.replace("&","");
        }
    } else {
        postData=$(form).serialize();
    }
    $.post($(form).prop("action"), postData, function(data) {
        if(!$(form).prop("action").includes("?key=")){
            if(isNaN(Number(data))){
                alert("Key not valid! "+data);
                return;
            } else {
                $(form).prop("action",$(form).prop("action")+"?key="+data);
                $(form).prop("id","question-form-key-"+data);
            }
        }
        if($("#add_more_questions").is("[disabled]")){
	        $("#add_more_questions").removeAttr("disabled");
	        $("#add_more_questions-tooltip").hide();
	    }
        if($(form).attr("id").indexOf("question")!== -1){
        	$(form).find(".disable-until-save").removeAttr("disabled");
        	$(form).find(".question-sub-question-dropdown").show();
        	$(form).find(".question-sub-question-tooltip").hide();
        }
        //console.log(data);
        $(form).find(".mdl-card__title-text")[0].innerHTML=$(form).find(".mdl-card__title-text")[0].innerHTML.replace(chips[1],"").replace(chips[0],"");
        $(form).find(".mdl-card__title-text")[0].innerHTML+=chips[1];
    }).fail(function(data){
        $(form).find(".mdl-card__title-text")[0].innerHTML=$(form).find(".mdl-card__title-text")[0].innerHTML.replace(chips[0],"");
        $(form).find(".mdl-card__title-text")[0].innerHTML+=chips[2];
        alert(data.responseText);
        console.log(data.responseText);
        $(':focus').blur();// Make sure error doesnt trigger again
        lastsave[$(form).attr("id")]=-1;
        $(form).find(".mdl-card__title-text")[0].innerHTML=$(form).find(".mdl-card__title-text")[0].innerHTML.replace(chips[0],"");
    });
    $(form).find("input[dirty=true],textarea[dirty=true]").attr("dirty","false");
}