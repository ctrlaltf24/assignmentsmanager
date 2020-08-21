var lastsave= [];
function checkSave(form,input) {
    $(input).attr("dirty","true");
    var currTime = performance.now();
    lastsave[$(form).attr('id')]=currTime;
    if($(form).find(".mdl-card__title-text")[0].innerHTML.includes("mdl-color--green")) {
        $(form).find(".mdl-card__title-text")[0].innerHTML=$(form).find(".mdl-card__title-text")[0].innerHTML.replace("<span class=\"mdl-chip\" style=\"padding-left: 0;\"><span class=\"mdl-chip__contact mdl-color--green mdl-color-text--white\"><i class=\"material-icons\" style=\"line-height: 32px;\">check</i></span><span class=\"mdl-chip__text\">Saved</span></span>","");
    }
    if(!$(form).find(".mdl-card__title-text")[0].innerHTML.includes("mdl-color--grey")) {
        $(form).find(".mdl-card__title-text")[0].innerHTML += "<span class=\"mdl-chip\" style='padding-left: 0;'><span class=\"mdl-chip__contact mdl-color--grey mdl-color-text--white\"><i class=\"material-icons\" style='line-height: 32px;'>cached</i></span><span class=\"mdl-chip__text\">Saving</span></span>";
    }
    setTimeout(function(){if(currTime==lastsave[$(form).attr('id')]){lastsave[$(form).attr('id')]=0;triggerSave($(form))}},1000);
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
            $(form).prop("action",$(form).prop("action")+"?key="+data);
            $(form).prop("id","question-form-key-"+data);
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
        $(form).find(".mdl-card__title-text")[0].innerHTML=$(form).find(".mdl-card__title-text")[0].innerHTML.replace("<span class=\"mdl-chip\" style=\"padding-left: 0;\"><span class=\"mdl-chip__contact mdl-color--grey mdl-color-text--white\"><i class=\"material-icons\" style=\"line-height: 32px;\">cached</i></span><span class=\"mdl-chip__text\">Saving</span></span>","");
        $(form).find(".mdl-card__title-text")[0].innerHTML+="<span class=\"mdl-chip\" style='padding-left: 0;'><span class=\"mdl-chip__contact mdl-color--green mdl-color-text--white\"><i class=\"material-icons\" style='line-height: 32px;'>check</i></span><span class=\"mdl-chip__text\">Saved</span></span>";
        setTimeout(function(){
            $(form).find(".mdl-card__title-text")[0].innerHTML=$(form).find(".mdl-card__title-text")[0].innerHTML.replace("<span class=\"mdl-chip\" style=\"padding-left: 0;\"><span class=\"mdl-chip__contact mdl-color--green mdl-color-text--white\"><i class=\"material-icons\" style=\"line-height: 32px;\">check</i></span><span class=\"mdl-chip__text\">Saved</span></span>","");
        },3000);
    });
    $(form).find("input[dirty=true],textarea[dirty=true]").attr("dirty","false");
}