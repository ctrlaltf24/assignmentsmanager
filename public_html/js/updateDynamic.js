function update(){
    var all = document.getElementsByTagName("*");
    for (var i=0; i < all.length; i++) {
        componentHandler.upgradeElement(all[i]);
    }
}