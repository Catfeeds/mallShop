<?php

header("content-Type: text/html; charset=utf-8");
/*用户签到返还函数*/
/**
 * GET 请求
 * @param string $url
 */
function http_get($url){
	$oCurl = curl_init();
	if(stripos($url,"https://")!==FALSE){
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
	}
	curl_setopt($oCurl, CURLOPT_URL, $url);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
	$sContent = curl_exec($oCurl);
	$aStatus = curl_getinfo($oCurl);
	curl_close($oCurl);
	if(intval($aStatus["http_code"])==200){
		return $sContent;
	}else{
		return false;
	}
}
function randStr($len = 10){
	for($i=0;$i<$len;$i++){
		$rand .= mt_rand(0,9);
	}
	return $rand;
}

/*用户签到返还函数*/
$code = $_REQUEST['code'];
$from = $_REQUEST['from']; //签到获取的openid
if($code){
	include('../config.php');
	include('../files/db.class.php');
	include('../../LightpenCms/Lib/ORG/Wechat.class.php');
	$_SESSION['cid'] = $_GET['companyid'];
	$flagwechats=new M('wechats');
	$isusemobiwind=$flagwechats->find();
	//var_dump($from);exit;
	if($isusemobiwind['isusemobiwind'] == 2){
		$wechatsInfo=$flagwechats->find("`wechattype` in('2','4')");
		$AppID = $wechatsInfo['appid'];
		$AppSecret = $wechatsInfo['appsecret'];
		//$wechatOptions = array('token'=>$wechatsInfo['token'],'appid'=>$wechatsInfo['appid'],'appsecret'=>$wechatsInfo['appsecret']);
		//$wechat  = new Wechat($wechatOptions);
		//$signPackage = $wechat->getJsSign($wechatsInfo['appid']);
	}else{
		//使用人来风授权
		$AppID = $publicAppID;
		$AppSecret = $publicAppSecret;
		//$wechatOptions = array('appid'=>$AppID,'appsecret'=>$AppSecret);
		//$wechat  = new Wechat($wechatOptions);
		//$signPackage = $wechat->getJsSign($AppID);
	}
	$tokenInfo = http_get("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$AppID."&secret=".$AppSecret."&code=".$code."&grant_type=authorization_code");
	$tokenInfo = json_decode($tokenInfo,true);

	//判断是否启用人来风授权，openid不同
	if($isusemobiwind['isusemobiwind'] == 1){
		$_SESSION['openid'] = $from;
	}else{
		$_SESSION['openid'] = $tokenInfo['openid'];
	} 
	$flag=new M('wall_flag');
	$count=$flag->find("openid='".$_SESSION['openid']."'",'flag','','assoc');
	
	//var_dump($count).'=================';
	
	if($count['flag'] !='2'){
		$isCheck = '2';//未签到
		//http_get("https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=".$AppID."&grant_type=refresh_token&refresh_token=".$tokenInfo['refresh_token']."");
		$info = http_get("https://api.weixin.qq.com/sns/userinfo?access_token=".$tokenInfo['access_token']."&openid=".$tokenInfo['openid']."&lang=zh_CN");
		
		//var_dump($info).'--------------';
		
		$infoarr=json_decode($info,true);
		$sqlarr=array(
				"nickname"=>bin2hex($infoarr['nickname']),
				"avatar"=>$infoarr['headimgurl'],
				"fakeid"=>randStr(),
				"sex"=>$infoarr['sex'],
				"fromtype"=>'weixin',
				"datetime"=>time(),
				"flag"=>"2",
		);
		$savve=$flag->update("openid='".$_SESSION['openid']."'",$sqlarr);
		
		//var_dump($savve);
		
		if($savve == '1'){
			$title='签到成功';
		}else{
			$title = '签到失败';
		}
	}else{
		$isCheck = '1';//已签到
		$title = '签到成功';
	}
?>
<html>
	<head lang="en">
	    <meta charset="UTF-8">
	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	    <meta name="viewport" content="width=320px,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="description" content="@fmRADIO8">
	    <meta name="format-detection" content="telephone=no">
	    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	    <meta http-equiv="Pragma" content="no-cache" />
	    <meta http-equiv="Expires" content="0" />
	    <title><?php echo $title;?></title>
	    <script src="js/jquery.js" type="text/javascript" charset="utf-8"></script>
	    <link rel="stylesheet" type="text/css" href="css/sign.css?010"/>
	    <script type="text/javascript">
	    (function () {
            document.addEventListener('DOMContentLoaded', function () {
                var html = document.documentElement;
                var windowWidth = html.clientWidth;
                if(windowWidth > 500 ){
                    html.style.fontSize = 50 + 'px';
                }else {
                    html.style.fontSize = windowWidth / 6.4 + 'px';
                }
                // alert(windowWidth);
                // 等价于html.style.fontSize = windowWidth / 640 * 100 + 'px';
            }, false);
        })();
	    </script>
	</head>
	<body>
	<?php if($isCheck == '2'){?>
		<!-- 签到成功  -->
	    <?php if ($savve == '1'){?>
        <div class="content-sign">
			<div class="pow_logo"><b class="pow_logo_rlf"></b></div>
			<div class="sign_div">
				<p class="sign_defe1">签到成功</p>
				<p class="sign_defe_cont1"> 返回发送消息<br/>就可以参与互动了哟</p>
			</div>
			  <div class="sign_btn">
				<a href="javascript:void(0);" class="close_btn close">返 回</a>
			</div>
	    </div>
	    <?php }else{?>
       	<div class="content_sign_false">
			<div class="pow_logo"><b class="pow_logo_rlf"></b></div>
			<div class="sign_div">
				<p class="sign_defe1">签到失败</p>
			</div>
			<div class="sign_btn">
				<a href="javascript:void(0);" class="close_btn close">返 回</a>
			</div>
    	</div>
	    <?php }?>
	  	<?php }else if($isCheck == '1'){?>
      	<div class="content-sign">
        	<div class="pow_logo"><b class="pow_logo_rlf"></b></div>
	    	<div class="sign_div">
	        	<p class="sign_defe">您已经签到过啦</p>
	        	<p class="sign_defe_cont"> 返回发送消息<br/>就可以参与互动了哟</p>
	    	</div>
        	<div class="sign_btn">
            	<a href="javascript:void(0);" class="close_btn close">返 回</a>
        	</div>
    	</div>
    	<?php }?>
<script src="../../Tpl/Wap/default/common/js/jweixin-1.0.0.js"></script>
<script>
	wx.config({
		  debug: false,
		  appId: "<?php echo $signPackage['appId'];?>",
		  timestamp: "<?php echo $signPackage['timestamp'];?>",
		  nonceStr: "<?php echo $signPackage['nonceStr'];?>",
		  signature: "<?php echo $signPackage['signature'];?>",
		  jsApiList: [
		'onMenuShareTimeline',    
		'onMenuShareAppMessage',    
		'onMenuShareQQ',    
		'onMenuShareWeibo',    
		'hideMenuItems',    
		'showMenuItems'
		  ]
		});
		wx.ready(function(){
			//显示右上角菜单接口
			wx.hideOptionMenu();
			$('.close').click(function(){
				wx.closeWindow();
			});
		});
</script>
</body>
</html>
<?php } ?>