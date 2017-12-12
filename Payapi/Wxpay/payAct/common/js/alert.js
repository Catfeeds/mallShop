document.writeln("<style type=\"text/css\">");
document.writeln(".tips-page{position:fixed;display:none;top:50%;left:50%; -webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%);z-index:100001;-webkit-box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);box-sizing: border-box;}");
document.writeln(".tips-con{background: rgba(0,0,0,.8);text-align: center;font-size:14px;color: #fff;line-height:18px;min-height:18px;padding:10px 20px;border-radius:2px;}");
document.writeln(".tips-con *{display:inline;vertical-align: middle; font-weight: 600;}");
document.writeln("</style>");
document.writeln("<div class=\"tips-page\" id=\"win-right\">");
document.writeln("	<div class=\"tips-con\"><p id=\"win-right-txt\"></p></div>");
document.writeln("</div>");
document.writeln("<div class=\"tips-page\" id=\"win-eorre\">");
document.writeln("	<div class=\"tips-con\"><p id=\"win-eorre-txt\"></p></div>");
document.writeln("</div>");
function alert(title,time,type){
	 alerttype = type ? type : 'error';
	 alerttime = time ? time : 2000;
	 if(alerttype == 'error'){
		$("#win-eorre").show(); 
		$("#win-eorre-txt").html(title);
	 }else if(alerttype == 'success'){
		$("#win-right").show(); 
		$("#win-right-txt").html(title);
	 }
	setTimeout('$(".tips-page").hide();',alerttime);
} 