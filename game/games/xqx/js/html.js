
    //var isiPad = navigator.userAgent.match(/(iPad|iPhone|iPod|Android|Windows Phone)/i) != null;
    var sUserAgent = navigator.userAgent.toLowerCase();
    if (sUserAgent.match(/MicroMessenger/i) != 'micromessenger') {
	    //不是手机
        //document.location.href = siteurl + "/ewm.htm";
	}
