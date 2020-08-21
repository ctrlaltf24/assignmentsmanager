function updateDropDownAtribute(input,listArray,attr){
    for(var i = 0, size = listArray.length; i < size ; i++){
        listArray[i].find("*").hide();
        listArray[i].find("["+attr+"="+input.val()+"]").show();
    }
}
