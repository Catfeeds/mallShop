<?php 
	@header("Content-type: text/html; charset=utf-8");

	include('db.php');
	/* if(isset($_SESSION[realpath("..").'views']) && $_SESSION[realpath("..").'views'] == true){
	} else {
	$_SESSION[realpath("..").'views'] = false;
	echo "<script>window.location='./login.php?url=".$_SERVER['PHP_SELF']."';</script>";
	die;
	} */
	if(isset($_GET["style"])){
	    $style = $_GET["style"];
	}else{
	    $style ="zidingyi";
	}
	$conf=$wall_config->find();
	/**
	 * 
	 * 以下为获取页面插件
	 * 
	 * qdq_switch	tinyint(1) [0]	 
	cj_switch	tinyint(1) [0]	 
	ddp_switch	tinyint(1) [0]	 
	weibo_switch	tinyint(1) [0]	 
	weixin_switch
	 * */
	$plugsc=new M('wall_plugs'); 
	$plugsa=$plugsc->select('switch =1');
	foreach($plugsa as $plugin) {
		if($plugin['name']=='cj'){
	    	if (@file_exists("cjg_plug/cjg_html.php")){
	        	$plugs['cjg']=1;
	            $plugs['cj']=0;
	       	}
	   	}
	   	if(@file_exists($plugin['name']."_plug/".$plugin['name']."_html.php")){    
	   		$plugs[$plugin['name']]=1;
	    }
	}
	if(file_exists("../api/weixin.php") && $conf['weixin_switch']){
	    $weixin=1;
	    $flag=new M('wall_wall_config');
	    $wallconf=$flag->find();
	    $publicnum =$wallconf['publicnum'];
	}else{
	    $weixin=0;
	}
	if(file_exists("../api/weibo.php") && $conf['weibo_switch']){
		$weibo=1;
		$flag=new M('wall_weibo_config');
		$weiboconf=$flag->find('id=1');
	}else{
		$weibo=0;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>人来风现场互动墙</title>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="../files/js/semantic.min.js"></script>
<script type="text/javascript" src="js/jquery.soChange-min.js"></script>
<script type="text/javascript"> 
	if(document.all){
		alert("ie浏览器无法正常解析本页，请使用谷歌内核的流量器浏览。如（360浏览器，猎豹浏览器等）");
		window.history.back(-1); 
		//window.navigate("top.jsp"); 
		}
	var webroot ='<?php echo Web_ROOT ?>';
</script> 
<link rel="stylesheet" href="css/wxwall.css" type="text/css"/>
<link rel="stylesheet" href="css/emoji.css" type="text/css"/>
<link rel="stylesheet" href="../files/css/semantic.min.css" type="text/css"/>
<link rel="stylesheet" href="style/<?php echo $style?>/css/style.css" type="text/css"/>
</head>
    <body>
	<?php
	if(file_exists("style/".$style."/change.php"))
	 {
	include("style/".$style."/change.php");}
	?>
<div class="main">
   	<EMBED style=" z-index:-2;RIGHT: 250px; POSITION: absolute; TOP: 55px; absolute: " align=right src=".style/<?php echo $style;?>/images/fl.swf" width=1600 height=625 type=application/x-shockwave-flash wmode="transparent" quality="high"></EMBED>
   	<EMBED style="z-index:-2;LEFT: 250px; POSITION: absolute; TOP: 55px; absolute: " align=right src=".style/<?php echo $style;?>/images/fl.swf" width=1600 height=625 type=application/x-shockwave-flash wmode="transparent" quality="high"></EMBED>
	<div class="l"></div>
	<div class="r"></div>
	<div class="top" onClick="viewExplan();" data-position="right center" data-content="二维码，快捷键M">
		<?php 
			$i=1;
			for(;$i<20;$i++){
				if(file_exists('logo/'.$i.'.png')){
					}else{
						break;
					}
				}
			if ($i <= 2){
				echo '<div class="top-logo">';
			}else{
				echo '<div class="ui shape top-logo">';
			}
		?>
		<div class="sides">
			<img src="<?php if($xuanzezu[40]==''){echo 'images/logo.png';}else{echo $xuanzezu[40];}?>" width=455px height=135px class="active side"/>
		</div>
	</div>
	<div class="kword ui shape ">
		<div class="sides">
			<div class="k active side">微信添加微信号：<strong><?php echo $publicnum;?></strong> <br>发送<?php  echo $xuanzezu[0];?>+您想说的话即可上墙！</div>
			<div class="k side"><?php  echo $xuanzezu[1];?></div>
			<div class="k side"><?php  echo $xuanzezu[2];?></div>
		</div>
	</div>
</div>
<div class="wall">
	<div class="left"></div>
    	<div class="center">
      		<div class="list">
        		<ul id="list"></ul>
        	</div>
           	<div class="footer"></div>
            <div class="btns1"><?php echo '<a onClick="viewstyle();" class="tooltip btnSkinSel  btn-icon btn-style" title="更换风格，快捷键F">风格选择</a>';?></div>
       		<div class="btns">
				<?php 
				if($plugs['qdq'])
				 {
				echo '<a href="javascript:void(0);" class="tooltip btnCheckin  btn-icon btn-checkin" title="签到墙，快捷键Q，【空格】开始" id="btnCheckin">签到墙</a>';
				}
				if($plugs['ddp'])
				 {
				echo '<a href="javascript:void(0);" class="tooltip btnDdp     btn-icon btn-pair " title="对对碰，快捷键D，【空格】开始">对对碰</a>';
				}
				if($plugs['shake'])
				 {
				echo '<a href="../shake/index.php?style='.$style.'&companyid='.$_SESSION['cid'].'" class="tooltip btnCheckin  btn-icon btn-shake" target="_blank" title="摇一摇，快捷键Y">摇一摇</a>';
				}
				if($plugs['ppl'])
				 {
				echo '<a href="../paipaile/index.php" class="tooltip btn-icon btn-ppl" target="_blank" title="拍拍乐，快捷键P">摇一摇</a>';
				}
				if($plugs['cj'] || $plugs['cjg'])
				 {
				echo '<a  href="javascript:void(0);" class="tooltip btnLottery btn-icon btn-lottery "  title="抽奖，快捷键C，【空格】开始">抽奖</a>';
				}
				if($plugs['vote'])
				 {
				echo '<a href="javascript:void(0);" class="tooltip btnVote    btn-icon btn-vote "  title="投票，快捷键T">投票</a>';
				}
				if(file_exists("style/".$style."/images/kuxuan.mp4"))
				 {
				echo "<i class=' bigbig volume off icon ' id='video-volume'></i>";}
				 ?>
			</div>
		</div>
       	<div class="right"></div>
	</div>
	<div class="ui transition hidden" onclick="viewstyle();"  id="style">
		<div class="ui teal segment style-box">
			<div class="ui ribbon teal label"><b style="font-size:3.4em;">现场互动墙风格选择</b></div>
    		<div class="style-con">
				   <?php 
			   include 'style.php';
			   $syl_num=count($sty_name);
			   for($i=$syl_num;$i>=1;$i--){
			   ?>
		           <div class="style-img">
		            <a href="<?php echo $sty_lnk[$i]?>" ><img src="<?php echo $sty_img[$i]?>"/></a>
		            <div class="style-tx"><b><?php echo $sty_name[$i]?></b></div>
		           </div>
			   <?php }?>
 			</div>
    	</div>
	</div> 
	<!--插件层-->
	<?php
	foreach ($plugs as $k => $v) {
	   if($plugs[$k]==1){
	    include_once($k.'_plug/'.$k.'_html.php');
	   }
	}
	?>
	<!--微博墙层-->
	<?php 
	 if($weibo && $weiboconf["mention"])
	 {
	include('weibo_plug/weibo_html.php');
	     
	 }
	?>

<div class="mone" id="mone" onClick="viewOneHide();"><div class="leftside"><div class="part"><div class="pic"><img class="msgconimg" src="" width="100" height="100"/></div><div class="username" style="color:#fff"><span style="color:#fff"></span></div></div></div><div class="rightside"><div class="rightmain"><div class="rconner"></div><span class="msgcon"></span></div></div></div>
<div id="explan" onClick="viewExplan();" class="ui primary segment" >
    <div class="ui ribbon green label"><b style="font-size:50px;">微信公众号：</b></div>
    <div class="erweima">
	    <center>
	    	<div class="mabox">
	        <?php 
	            if($weixin){
	                echo'<div class="pic"><center><a class="ui blue label"><b style="font-size:20px;line-height: 1.7em;">微信:'.$publicnum.'</b></a></center><img src="'.$wallconf['erweima'].'" width=362px height=362px;/></div>';
	            	}
	        	?>
	    	</div>
	    </center>
    </div>
	<div class="ui bottom right attached label vote-right"><a class="ui black circular label" >×</a></div>
</div>
  
<script type="text/javascript">
	$(function(){
	  	$('.top').popup();
	  	$('.tooltip').popup();
		$(document).keydown(function (event){    
	    	if (event.keyCode == 77) {
				$('.top').click();
	       	}
			if(event.keyCode == 70){
				$('.btnSkinSel').click();
			}
	    });  
	});
	var refreshtime =<?php echo $xuanzezu[23] ?>;
	var len=4;
	var cur=0;//当前位置
	var mtime;
	var data=new Array();
	var prevgetdatatime=0;
	var nowgetdatatime=0;
	data[0]=new Array('0','../img/0.jpg','系统消息','欢迎来到微信上墙，发送图片也可以上墙哦！','','weibo');
	//var word_id='96';
	<?php 
	$lastid = 0;
	?>
	var lastid='<?=$lastid ?>';
	
	function viewOneHide(){
		oopen=switchto(oopen,'mone');
	}
	function viewOne(cid,t){
	    var str=$('#li'+cid);
		var onenickname = str.find("span").html();
		var oneword = str.find("word").text();
		var onesrc = str.find("img").attr('src');
		var oneimgsrc = str.find(".image").find("img").attr('src');
		if(typeof(oneimgsrc) == 'string'){
			$("#mone").find(".msgcon").html('<img src="'+oneimgsrc+'"/>');
		}else{
			$("#mone").find(".msgcon").text(oneword);
		}
	    $("#mone").find("span").first().html(onenickname);
	    $("#mone").find("img").first().attr('src',onesrc);
		oopen=switchto(oopen,'mone');
	}
	function viewExplan(){
	        $("#explan").transition('fade up');
	}
	function viewstyle(){
	        $("#style").transition('scale');
	}
	function messageAdd(){
	    if(cur==len){
	        messageData();
	        return false;
	    }
	    if (typeof(data[cur]) == 'undefined'){
	    	nowgetdatatime = Date.parse(new Date())-(refreshtime*1000);
	    	if(prevgetdatatime<=nowgetdatatime){
	        	var url='api.php';
	            $.getJSON(url,{lastid:lastid},function(d) {
	                if(d['ret']==1){
	                    $.each(d['data'], function(i,v){
	                        data.push(new Array(v['num'],v['avatar'],v['nickname'],v['content'],v['image'],v['fromtype']));
	                        lastid=v['num'];
	                        len++;
	                    });
	                }
	            });
	        }
	        return false;
	    }
	    if (data[cur][4] == ''){
	        var str='<li id=li'+cur+' onclick="viewOne('+cur+',this);"><div class=m1><div class="'+data[cur][5]+' m2"><div class="pic"><img class="circular ui image" src="'+data[cur][1]+'" width="100" height="100" /><div class="bakico"><img  src="images/ico-'+data[cur][5]+'.png"/></div></div><div class="c f2"><span>'+data[cur][2]+'</span><span>：</span><word>'+data[cur][3]+'</word></div></div></div></li>';
	    }else {
	    	var str='<li id=li'+cur+' onclick="viewOne('+cur+',this);"><div class=m1><div class="'+data[cur][5]+' m2"><div class="pic"><img class="circular ui image" src="'+data[cur][1]+'" width="100" height="100" /><div class="bakico"><img  src="images/ico-'+data[cur][5]+'.png"/></div></div><div class="c f2" style="width:57%"><span>'+data[cur][2]+'</span><span>：</span><word>'+data[cur][3]+'</word></div><div class="image"><img src="'+data[cur][4]+'"/></div></div></div></li>';
	    }
	    if(cur > 50){
	       $("li").remove("#li"+(cur-50));
	    }
	    $("#list").prepend(str);
	    $("#li"+cur).slideDown(600);
	    //当消息为图片消息时  此方法为将图片自动放大观看，然后隐藏。此方法不可删除！！！
	    if (data[cur][4] != ''){
			viewOne(cur,cur);
			window.setTimeout('viewOneHide();', 3000);
		}
	    cur++; 
	    messageData();
	}
	function messageData()
	{
	    var url='api.php';
	    $.getJSON(url,{lastid:lastid},function(d) {
	    	prevgetdatatime = Date.parse(new Date());
	        if(d['ret']==1){
	            $.each(d['data'], function(i,v){
	                data.push(new Array(v['num'],v['avatar'],v['nickname'],v['content'],v['image'],v['fromtype']));
	                lastid=v['num'];
	                len++;
	            });
	        }else{
	           //console.log('木有新消息..每5秒ajax一次');
	           window.setTimeout('messageData();', refreshtime*1000);
	        }
	  });
	}
	window.onload=function(){mtime=setInterval(messageAdd,refreshtime*1000);
	}
</script>
<script>
	setInterval("$('.shape').shape('flip up');",5000);
</script>
<img class="bg" src="<?php if($style=='zidingyi'){if($xuanzezu[41]==''){echo "style/zidingyi/images/kuxuan.jpg";}else{echo $xuanzezu[41];}}else{echo "style/$style/images/kuxuan.jpg";}?>"/>
</body>
</html>