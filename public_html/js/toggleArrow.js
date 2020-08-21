function toggleElement(toggleElement, element) {
    $(toggleElement).css("transition", "max-height .5s ease-in-out");
    if ($(toggleElement).css("max-height") == window.innerHeight*5+"px") {
        $(toggleElement).css("max-height", "0px");
        //$('#' + id).show();
        $(element).text('keyboard_arrow_up');
    } else {
        $(toggleElement).css("max-height", window.innerHeight*5+"px");
        //$('#' + id).hide();
        $(element).text('keyboard_arrow_down');
    }
    //http://n12v.com/css-transition-to-from-auto/
}
