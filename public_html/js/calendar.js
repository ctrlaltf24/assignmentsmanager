function registerClicks(iframeID,inputToChangeId,mainToDirty) {
    document.getElementById(iframeID).contentWindow.document.addEventListener('click', function () {
        $("#" + inputToChangeId).val(document.getElementById(iframeID).contentWindow.document.getElementById("date").value.replace(new RegExp(".*calendar-day-(.*) calendar-.*"), "$1"));
        $("#" + mainToDirty).addClass("is-dirty");
    }, false);
}