<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>跳转提示</title>
<link rel="stylesheet" href="Tpl/User/default/common/css/font-awesome.min.css" />
<style type="text/css">
*{ padding: 0; margin: 0; }
body{ background: #fff; font-family: '微软雅黑'; color: #333; font-size: 16px; }
.system-message{ padding:0 0 48px;margin:150px auto;width:400px;border: 1px solid #A0A0A0;box-shadow: 3px 3px 7px #999;}
.system-message .title{color:#333;font-size:14px;height:30px;background: -moz-linear-gradient(top, #f1f1f1 0%, #e4e4e4 100%);background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#f1f1f1), color-stop(100%,#e4e4e4));background: -webkit-linear-gradient(top, #f1f1f1 0%,#e4e4e4 100%);padding-left:10px;line-height:30px;border-bottom:1px solid #CFCFCF;}
.system-message h3{ font-size: 50px; font-weight: normal; line-height: 120px; margin-bottom: 12px;border:1px solid #ccc}
.system-message .jump{padding:10px 20px 0 0;text-align: right;font-size:12px;}
.system-message .jump a{ color: #333;}
.system-message .success,.system-message .error{ line-height: 1.8em; font-size:18px;text-align: center;color:#555;}
.system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display:none}
.system-message .fa{color:#AAACAF;font-size:28px;vertical-align: text-top; margin-right:10px;}
.system-message .bd{padding:50px 24px 20px 24px;}
</style>
</head>
<body>
<div class="system-message">
	<h2 class="title">提醒</h2>
	<div class="bd">
		<present name="message">		
		<div class="success"><i class="fa fa-check-circle"></i><!--<img style="margin-right: 9px;padding-top:10px;" src="/conf/images/success.png">--><span><?php echo($message); ?></span></div>
		<else/>		
		<div class="error"><i class="fa fa-times-circle"></i><!--<img style="margin-right: 9px;padding-top:10px;" src="/conf/images/error.png" style="cursor:pointer;">--><span style="padding-top:0px;"><?php echo($error); ?></div>
		</present>
	
	</div>
	<p class="detail"></p>
	<div class="jump">页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>
	</div>
</div>
<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time == 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
</script>
</body>
</html>