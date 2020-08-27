var submitQuestionData = {};

window.onload = function(e) {
    var startTime = (new Date).getTime();
    registerForms(startTime);
};

function registerForms(startTime) {
    $("form").submit(function(event) {
        event.preventDefault();
        var element = this;
        if(submitQuestionData[$(this).prop("action")]==$(this).serialize()){
            // Do not submit duplicate data.
            return;
        } else {
            submitQuestionData[$(this).prop("action")]=$(this).serialize();
        }
        $.post($(this).prop("action") + "&timeTaken=" + ((new Date).getTime() -
            startTime), $(this).serialize(), function(data) {
            $(element).get(0).outerHTML = data;
            update();
            registerForms();
            if (data.includes("color:green")&&!data.includes("color:red")){
                $(document).ready(function(){$('#fireworks').show();setTimeout(function(){$('#fireworks').hide();},2000);});
            }
        });
    });
}
