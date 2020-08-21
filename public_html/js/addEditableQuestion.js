function addEditableQuestion(name,subquestion,element){
	questions++;
	$("#questions > div:last-of-type").after("<div "+(subquestion?"class=mdl-grid":"")+" id=question-"+questions+"></div>");
	$("#question-placeholder").remove();
    var extraArgs="";
    if($(".assignment-form").attr("action").includes("?key=")){
        extraArgs+="&assignment="+$(".assignment-form").attr("action").replace(/.*?key=(.*)/,"$1");
    }
    ["subject","chapter","concept"].forEach(function(item, index){
        if($("#"+item+"-assignment").val()!=""){
            extraArgs+="&"+item+"="+$("#"+item+"-assignment").val();
        }
    });
    updateElement("./noShowCreateQuestion.php?count="+questions+"&question_type="+name+extraArgs+(subquestion?"&parent-question="+new RegExp(".*key=([0-9]+).*").exec($(element).parent().parent().parent().parent().prop("action"))[1]:""),"#question-"+questions);
    if(subquestion){
    	if($(element).parent().parent().parent().parent().parent().parent().find("#sub-questions").length==0){
    		$(element).parent().parent().parent().parent().parent().after("<div id=sub-questions class='mdl-cell mdl-cell--12-col' style='width: calc(100% - 56px);'></div>");
    	}
    	$("#question-"+questions).appendTo($(element).parent().parent().parent().parent().parent().parent().find("> div#sub-questions"));
    	$("#question-"+questions).css("width","100%");
    	$("#question-"+questions).css("padding-left","5%");
    }
}