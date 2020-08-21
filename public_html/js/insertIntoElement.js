function insertIntoElement(id,content){
	if($("#"+id)[0].selectionStart==$("#"+id)[0].selectionEnd){//if it is a cursor not a selection
		var myRegexp = /\$([0-9]+)\$Prompt;(.*?)\$/g;
		var match = myRegexp.exec(content);
		while (match!=null){
			var input = prompt(match[2]);
			content=content.replace(match[0],input);
			content=content.replace("$"+match[1],input);
			myRegexp = /\$([0-9]+)\$Prompt;(.*?)\$/g;
			match = myRegexp.exec(content);
		}
		$("#"+id).val($("#"+id).val().substring(0,$("#"+id)[0].selectionStart)+content+$("#"+id).val().substring($("#"+id)[0].selectionEnd,$("#"+id).val().length));
		var location=$("#"+id).val().indexOf("$1");
		$("#"+id).val($("#"+id).val().replace("$1",""));
		$("#"+id).parent().find("label").hide();
		$("#"+id).trigger("focus");
		$("#"+id)[0].selectionEnd=location;
		$("#"+id)[0].selectionStart=location;
	} else {
		$("#test_textarea").val($("#"+id).val().substring(0,$("#"+id)[0].selectionStart)+content.replace("$1",$("#"+id).val().substring($("#"+id)[0].selectionStart,$("#"+id)[0].selectionEnd)+$("#"+id).val().substring($("#"+id)[0].selectionEnd,$("#"+id).val().length)));
	}
}