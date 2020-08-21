window.onload = function(e) {
	if (document.documentMode || /Edge/.test(navigator.userAgent)) {
		alert(
			'WARNING. Edge is not supported and some features such as hints may not work properly. All other major browsers should work. Firefox, Chrome hae been tested. Operera and Chromium probobly work as well.'
		);
	}
}
