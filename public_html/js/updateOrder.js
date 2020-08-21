function updateOrder(element){
	var neworder = ";";
	$(element).find(" > div").each(function(){
		if($(this).find("form").length!=0&&$(this).find("form").prop("action").indexOf("key=")!==-1){
			neworder+=new RegExp(".*key=([0-9]+).*").exec($(this).find("form").prop("action"))[1]+";";
		}
	});
	$($($(element).parent().children()[0]).children()[0]).find(".question-order").val(neworder).click();
}