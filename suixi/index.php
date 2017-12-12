<?php 
 $data = file_get_contents('http://www.mobiwind.cn/index.php?g=Home&m=Suixi&a=getInfo');
 $data = json_decode($data,true);
 ?>
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
  <link href="common/css/jcarousel.basic.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="nav">
  <div class="nav_ct">
    <ul class="cf">
      <li><a href="javascript:void(0);">旗 下<i>SUBORDINATE</i></a>
        <div class="second_menu"><a href="http://www.kervanchina.com/list/?6_1.html">颐和四季</a> <a href="http://www.kervanchina.com/list/?7_1.html">凯乐文</a> <a href="http://www.kervanchina.com/list/?20_1.html">J pasta and steak</a> </div>
      </li>
      <li><a href="http://www.kervanchina.com/about/?19.html">顾 问 <i>CONSULTANT </i></a> </li>
      <li class="bj_none"><a href="http://www.kervanchina.com/about/?107.html">定 制<i>CUSTOM</i></a> </li>
      <li class="logo_li"><img src="common/images/logo.jpg" alt=""></li>
      <li><a href="http://www.kervanchina.com/list/?109_1.html">最 新<i>THE LATEST</i></a> </li>
      <li><a href="http://suixi.kervanchina.com/">随 喜<i>REJOICE IN</i></a> </li>
      <li class="bj_none"><a href="http://www.kervanchina.com/about/?108.html">食 客<i>CUSTOM</i></a> </li>
    </ul>
  </div>
  <p class="second_p"></p>
</div>

<div class="layout">
	<div class="head">
		<div class="suixi-schedule">
			<div class="suixi-schedule-item">
				<div class="mb30">
					<div class="suixi-project"><h3>筹集金额:</h3>	</div>
					<div class="cf">
						<p class="fl p2 total-amount-wrap">
							<span><?php if(strlen(intval($data['amout']['amout'])) == 5){?><?php echo substr(intval($data['amout']['amout']),-5,1);?><?php }else{?>0<?php }?></span>
						<span><?php if(strlen(intval($data['amout']['amout'])) >= 4){?><?php echo substr(intval($data['amout']['amout']),-4,1);?><?php }else{?>0<?php }?></span>
						<span><?php if(strlen(intval($data['amout']['amout'])) >= 3){?><?php echo substr(intval($data['amout']['amout']),-3,1);?><?php }else{?>0<?php }?></span>
						<span><?php if(strlen(intval($data['amout']['amout'])) >= 2){?><?php echo substr(intval($data['amout']['amout']),-2,1);?><?php }else{?>0<?php }?></span>
						<span><?php if(strlen(intval($data['amout']['amout'])) >= 1){?><?php echo substr(intval($data['amout']['amout']),-1);?><?php }?></span>
						</p>
						<span class="unit">元</span>
					</div>
					<p class="c1">目标:20,000元</p>
				</div>
				<div class="prog">
					<div class="suixi-project"><h3>活动进程:</h3></div>
					<div class="level-map">
						<div class="progressbarbox">
							<div class="progressbar" data-perc="<?php if($data['amout']['status'] == 1){echo 0; }elseif($data['amout']['status'] == 2){echo 33.3; }elseif($data['amout']['status'] == 3){ echo 66.6; }elseif($data['amout']['status'] == 4){echo 99.5; }?>">
								<div class="bar color"><span></span></div>
							</div>
						</div>
						<div class="level-medal cf">
							<span class="one">启动</span>
							<span class="two">筹款</span>
							<span class="three">拨款</span>
							<span class="four">采购</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="layout">
	<div class="activity-rule">
		<h3></h3>
		<div class="bd">
			<p class="fw" style="padding-right: 276px;">凯乐文创始人吴尔先生在一次去云南的旅途中，游走到了一片偏僻穷困的山区，遇到一个正在上学的云南小女孩，因为名字中与妻子同有一个颖字，于是决定以个人名义资助她念书。			</p>
			<p class="fw" style="padding-right: 276px;">当时正值凯乐文最为艰难的创立之初，女孩来信感谢我们，附了一张令人欣慰的成绩单，这一封信带来的慰藉远胜于我们的付出。</p>
			<div><img src="common/images/p5.gif"></div>
			<p><strong>引申义：即行善时，随己所喜，即根据自己的能力和意愿布施行善。</strong>凯乐文联合云南省青少年发展基金会发起”随喜“微公益活动，宣扬人人可公益理念，以筹款方式为偏远山区的孩子们建造一个整洁明亮的食堂。</p>
			<p>于是我们就有了”随喜“下午茶的idea，让轻松惬意的土耳其下午茶，伴随温馨善举，更加令人随之欢喜起来。</p>
			<p class="c2">活动时间：2014年6月15日至8月10日，下午13:30至16:30</p>
			<p class="fw">参与方式：凯乐文店内“随喜”下午茶单品定价10元起，您可随己所喜，根据自己的能力和意愿支付≥10元的任意金额。</p>
			<p>您下午茶的消费金额即为此次活动的筹款额。我们将会在筹款书上记录您的信息和筹款金额。活动期间所有下午茶营业筹款将悉数捐赠予云南省青少年发展基金会，用于永仁县万马小学食堂建设。</p>
			<p class="c2">如果您参与“随喜”下午茶活动还有机会获得凯乐文准备的精美礼品一份！</p>
		</div>
	</div>
</div>
<div class="layout cf mb60">
	<div class="area area-left fl">
		<h3 class="hd"><img src="common/images/h3.jpg" /></h3>
		<div class="bd">
			<ul class="imglist cf">
				<li><img src="common/images/p1.jpg" width="212" height="157" /> </li>
				<li><img src="common/images/p2.jpg" width="212" height="157" /> </li>
				<li><img src="common/images/p3.jpg" width="212" height="157" /> </li>
				<li><img src="common/images/p4.jpg" width="212" height="157" /> </li>
			</ul>
			<p>永仁县万马小学是属于永仁县中和镇中心小学下的一所边远、偏僻的山区小学。</p>
			<p>由于万马小学的厨房年长陈旧，学校于2012年年底着手建盖了一幢新厨房，由于资金短缺，凯乐文的本次公益活动将帮助学校完成新厨房的建设。解决130多名学校孩子们的正常就餐问题。</p>
		</div>
	</div>
	<div class="area area-right fl">
		<h3 class="hd"><img src="common/images/h4.jpg" /></h3>
		<div class="bd">
			<div class="select-date cf">
				<button class="fl ffll show-photo active" day="1" type="submit">上一天</button>
				<button class="fr ffrr show-photo " day="2" type="submit">下一天</button>
				<span id="show-title"><?php echo $data['photo']['title']; ?></span></div>
			<div class="notice-main"><img id="show-photo-img" src="<?php echo $data['photo']['picurl']; ?>" width="441" height="492" /> </div>
		</div>
	</div>
</div>
<div class="layout">
	<div class="area area-sub">
		<h3 class="hd"><img src="common/images/h5.jpg" /></h3>
		<div class="bd">
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
		</div>
	</div>
</div>
<div class="layout">
  <div class="area area-give">
    <h3 class="hd"><img src="common/images/h6.jpg" /></h3>
    <div class="bd">
      <div class="jcarousel-wrapper">
        <div class="jcarousel">
          <ul>
            <li><img id="show-photo-picture" src="" width="955" height="675" alt=""><p id="photoName"></p></li>
          </ul>
        </div>
        <a href="javascript:void(0);" class="jcarousel-control-prev picture inactive" page="1">&nbsp;</a>
        <a href="javascript:void(0);" class="jcarousel-control-next picture" page="2">&nbsp;</a>
      </div>
      <div class="area-give-info">
        <h3>万马完小举办“感恩”活动画展</h3>
        <div class="cf">
          <img class="fr ml20" src="common/images/p6.jpg" />
          <p>6月4日，万马完小举办以“感恩”为主题的小学生画展。</p>
          <p>此次画展共有130余幅纸质画参展，这些作品汇集了美术课堂内外学生的精品良作，作品内容以感恩、爱、梦想为主题，描绘了学生心中最真、最美的梦想和表达了最真挚的感谢，表达了学生对美好“中国梦”的无限憧憬和热爱家乡、热爱幸福生活的美好愿望。本校144名小学生用近一个月的时间在科任老师的指导下完成纸质参展作品的构图设计和涂色等过程，绘画学生最大年龄14岁，最小年龄7岁。</p>
        </div>
        <div class="cf">
          <img class="fl mr20" src="common/images/p6.jpg" />
          <p>参加过捐赠仪式的同学们在活动中普遍受到感化和激励，不仅会牢记捐赠方的资助关爱，而且也会怀感恩之心和报答之志，通过参加各种各样丰富多彩的社会公益活动，奉献爱心、回报社会，在成长成才的道路上不断迈进。</p>
          <p class="t-a-r">此次画展为学生们留下了一个难忘的“六•一”。<br />
            万马完小少队部<br />
            2014年6月4日</p>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="layout footer">
	<img src="common/images/f1.gif" />
</div>
<script src="common/js/jquery.1.10.1.min.js" type="text/javascript"></script>
<script src="common/js/all.js"></script>
<!--[if lt IE 9]>
<script src="common/js/html5shiv.js"></script>
<![endif]-->
<script src="common/js/jquery.1.10.1.min.js"></script>
<!-- <script src="common/js/all.js"></script>
<script src="common/js/jquery.jcarousel.min.js"></script>
<script src="common/js/jcarousel.basic.js"></script> -->
<script>
	$(function() {
		$('.progressbar').each(function(){
			var t = $(this),
				dataperc = t.attr('data-perc');
			barperc = Math.round(dataperc);
			t.find('.bar').animate({width:barperc+'%'}, barperc*25);
			t.find('.label').append('<div class="perc"></div>');
		});
		var page = 1;
		var $this = $('.jcarousel-control-prev');
		$.get("http://www.mobiwind.cn/index.php?g=Home&m=Suixi&a=getPicture&callback=returnData&time="+Math.random(),
			{page:page},
			function(data){
			if(data.code == 200){
				//$('.jcarousel-control-prev').removeClass('inactive');
				$('.jcarousel-control-next').removeClass('inactive');
				$('#photoName').text(data.title);
				$('#show-photo-picture').attr('src',data.src);
				$('.jcarousel-control-prev').attr({'page':data.prevPage});
				$('.jcarousel-control-next').attr({'page':data.nextPage});
			}else if(data.code == 300){
				$($this).addClass('inactive');
			}
			},"jsonp");
	});
	$('.show-photo').click(function(){
		var day=$(this).attr('day');
		var button = $(this);
		$.get("http://www.mobiwind.cn/index.php?g=Home&m=Suixi&a=getPhotoInfo&callback=returnData&time="+Math.random(),
			{day:day},
			function(data){
			if(data.code == 200){
				$('.ffll').removeClass('active');
				$('.ffrr').removeClass('active');
				$('#show-photo-img').attr('src',data.src);
				$('#show-title').text(data.title);
				$('.ffll').attr({'day':data.prevday});
				$('.ffrr').attr({'day':data.nextday});
			}else if(data.code == 300){
				$(button).addClass('active');
			}
			},"jsonp");
		});
		//感恩图画
		$('.picture').click(function(){
			var page = $(this).attr('page');
			var $this = $(this);
			$.get("http://www.mobiwind.cn/index.php?g=Home&m=Suixi&a=getPicture&callback=returnData&time="+Math.random(),
				{page:page},
				function(data){
				if(data.code == 200){
					if(data.prevPage > 1){
						$('.jcarousel-control-prev').removeClass('inactive');
					}
					$('.jcarousel-control-next').removeClass('inactive');
					$('#photoName').text(data.title);
					$('#show-photo-picture').attr('src',data.src);
					$('.jcarousel-control-prev').attr({'page':data.prevPage});
					$('.jcarousel-control-next').attr({'page':data.nextPage});
				}else if(data.code == 300){
					$($this).addClass('inactive');
				}
				},"jsonp");
			});
</script>
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