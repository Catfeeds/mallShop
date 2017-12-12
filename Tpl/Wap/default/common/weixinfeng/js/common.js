$(function(){
	$(window).resize(infinite);
	function infinite() {
		var htmlWidth = $('html').width();
		if (htmlWidth >= 750) {
			$("html").css({
				"font-size" : "40px"
			});
		} else {
			$("html").css({
				"font-size" :  40 / 750 * htmlWidth + "px"
			});
		}
	}infinite();
});