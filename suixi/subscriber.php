<!DOCTYPE html>
<!--[if lt IE 7]> <html class="front ie lt-ie9 lt-ie8 lt-ie7 fluid top-full"> <![endif]-->
<!--[if IE 7]>    <html class="front ie lt-ie9 lt-ie8 fluid top-full sticky-top"> <![endif]-->
<!--[if IE 8]>    <html class="front ie lt-ie9 fluid top-full sticky-top"> <![endif]-->
<!--[if gt IE 8]> <html class="front ie gt-ie8 fluid top-full sticky-top"> <![endif]-->
<!--[if !IE]><!--><html class="front fluid top-full sticky-top"><!-- <![endif]-->
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>随喜</title>
	<link href="common/css/base.css" rel="stylesheet" type="text/css" />
	<!--[if lt IE 9]>
	<script src="common/js/html5shiv.js"></script>
	<![endif]-->
	<script src="common/js/jquery.1.10.1.min.js"></script>
	<style>
		body{background: #fff;height:557px;}
		.select-date{margin-top: 15px;}
	</style>
</head>
<body>
	<ul class="donate donate-title cf">
		<li>捐助者姓名</li>
		<li>捐助金额</li>
		<li>捐助者手机</li>
	</ul>
	<div id="donation-list">
	</div>
	<div class="select-date cf">
		<button class="donation-page ffff fl active  " page="1" type="submit">上一页</button>
		<button class="donation-page rrrr fr" page="2" type="submit">下一页</button>
		<span id="showpage">1/1</span>
	</div>
	<script>
	$(function(){
		var page=$('.ffff').attr('page');
		var button = $('.ffff');
		$.get("http://www.mobiwind.cn/index.php?g=Home&m=Suixi&a=getDonationList&callback=returnData&time="+Math.random(),
			{page:page},
			function(data){
			if(data.code == 200){
				$('.ffff').removeClass('active');
				$('.rrrr').removeClass('active');
				$('#donation-list').html(data.html);
				$('#showpage').html(data.showpage);
				$('.ffff').attr({'page':data.prevpage});
				$('.rrrr').attr({'page':data.nextpage});
			}else if(data.code == 300){
				$(button).addClass('active');
			}
		},"jsonp");
		});
	$('.donation-page').click(function(){
		var page=$(this).attr('page');
		var button = $(this);
		$.get("http://www.mobiwind.cn/index.php?g=Home&m=Suixi&a=getDonationList&callback=returnData&time="+Math.random(),
			{page:page},
			function(data){
			if(data.code == 200){
				$('.ffff').removeClass('active');
				$('.rrrr').removeClass('active');
				$('#donation-list').html(data.html);
				$('#showpage').html(data.showpage);
				$('.ffff').attr({'page':data.prevpage});
				$('.rrrr').attr({'page':data.nextpage});
			}else if(data.code == 300){
				$(button).addClass('active');
			}
		},"jsonp");
	});
	</script>
</body>
</html>