function updateElement(url,cssSelector){
    //console.log(url);
    xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", url, true);
    xmlhttp.responseType = 'text';
    xmlhttp.onload = function () {
        if (xmlhttp.readyState === xmlhttp.DONE) {
            if (xmlhttp.status === 200) {
                $(cssSelector+" li").remove();
                $(cssSelector).append(xmlhttp.responseText);
                update();
                //console.log(xmlhttp.responseText);
            }
        }
    };
    xmlhttp.send(null);
}