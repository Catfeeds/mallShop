<?php
ini_set('date.timezone','Asia/Shanghai');
header("Content-type: text/html; charset=utf-8");
require_once "../lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';
include_once("../Common/common.php");

$db = new db();
$orderid = $_GET['orderid'];
// $companyid = $_GET['companyid'];
if(!$orderid){
    echo "<p style='font-size: 20px;font-weight: bold;'>订单号错误</p>";
    exit();
}

$orderInfo = $db->table('wechat_check_order')->where(array('orderid'=>$orderid))->field('id,companyid,paystate,subtotal,orderid,out_trade_no,totalprice,actname,cattype,catdiscouratio,catdiscoumoney')->find();
$companyid = $orderInfo['companyid'];
$companyInfo = $db->table('company')->where(array('id'=>$companyid))->field('id,name,logourl')->find();
if(!$companyInfo){
    echo "<p style='font-size: 20px;font-weight: bold;'>公司信息配置错误</p>";
    exit();
}else{
    // 定义分享信息
    $info['sharefriendstitle'] = $companyInfo['name'].'微信买单'; // 分享标题
    $info['shareurl'] = site_url."/index.php?g=Wap&m=WechatPayBill&a=index&companyid=".$companyid; // 分享链接
    $info['shareimg'] = $companyInfo['logourl']; // 分享图片
    $info['sharedes'] = '微信买单仅限到店现场支付，请勿提前购买'; // 分享描述
}
$wechatsInfo = $db->table('wechats')->where(array('companyid'=>$companyid,'wechattype'=>'4'))->field('token,appid,appsecret')->find();
if(!$wechatsInfo){
    echo "<p style='font-size: 20px;font-weight: bold;'>微信公众号信息配置错误</p>";
    exit();
}
if($orderInfo['paystate'] == '1'){  // 支付中
    $wechatPay = $db->table('company_pay_wechat')->where(array('companyid'=>$companyid,'isshow'=>1))->field('toaccount,keypassword')->find();
    if(!$wechatPay){
        echo "<p style='font-size: 20px;font-weight: bold;'>支付商户号错误</p>";
        exit();
    }
    $options['appid'] = $wechatsInfo['appid'];
    $options['mchid'] = $wechatPay['toaccount'];
    $options['key'] = $wechatPay['keypassword'];
    $options['appsecret'] = $wechatsInfo['appsecret'];
    // ①、获取用户openid
    $tools = new JsApiPay($options);
    $openId = $tools->GetOpenid();
    // ②、统一下单
    $input = new WxPayUnifiedOrder($options);
    $input->SetBody($companyInfo['name']);
    $cardvalue = $orderInfo['subtotal']*100;//金额
    $out_trade_no = $orderInfo['merchantorder'] ? $orderInfo['merchantorder'] : get_order_id();
    if($out_trade_no){
        $updateOrderInfoReturn = $db->table('wechat_check_order')->data(array('out_trade_no'=>$out_trade_no,'updatetime'=>time()))->where(array('orderid'=>$orderid,'companyid'=>$companyid))->update();
        if(!$updateOrderInfoReturn){
            echo "<p style='font-size: 20px;font-weight: bold;'>微信支付接口异常</p>";
            exit();
        }
    }
    $input->SetOut_trade_no($out_trade_no);
    $input->SetTotal_fee($cardvalue);
    $input->SetNotify_url(site_url."/Payapi/Wxpay/payAct/wechatPayBillNotify.php");
    $input->SetTrade_type("JSAPI");
    $input->SetOpenid($openId);
    $WxPayApi = new WxPayApi($options);
    $order = $WxPayApi->unifiedOrder($input);
    $jsApiParameters = $tools->GetJsApiParameters($order);
}
if($wechatsInfo){
    include_once("./Wechat.class.php");
    $wechatOptions = array('token'=>$wechatsInfo['token'],'appid'=>$wechatsInfo['appid'],'appsecret'=>$wechatsInfo['appsecret']);
    $wechat  = new Wechat($wechatOptions);
    $signPackage = $wechat->getJsSign($wechatsInfo['appid']);
}else{
    $signPackage = '';
}

?>

<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimal-ui" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="black" />
  <meta name="format-detection"content="telephone=no, email=no" />
  <title>微信买单</title>
  <!-- basic styles -->
  <link rel="stylesheet" href="./common/css/app.css"/>
  <link rel="stylesheet" href="./common/css/base.css"/>
  <script src="./common/js/zepto.min.js"></script>
  <script src="./common/js/jquery1.7.2.js"></script>
</head>
<body>
<?php if($orderInfo['paystate'] == '1'){?>
<!--确认买单-->
<div class="mxmd_content_box">
    <div class="wxmd_head_box">
        <div class="wxmd_head_son">
            <i><img src="<?php echo $companyInfo['logourl'];?>"></i>
        </div>
    </div>
    <div class="wxmd_main_box">
        <div class="wxmd_main_son">
            <ul>
                <li>消费总额：</li>
                <li><i><?php echo $orderInfo['totalprice']?$orderInfo['totalprice']:'0.00';?></i></li>
                <li>微信买单优惠活动：</li>
                <li><?php echo $orderInfo['actname']?$orderInfo['actname']:'无';?></li>
                <li>活动效用：</li>
                <li><i>-<?php if($orderInfo['cattype']=='1' || $orderInfo['cattype']=='2' || $orderInfo['cattype']=='3'){ echo $orderInfo['catdiscoumoney']?$orderInfo['catdiscoumoney']:'0.00'.'元';}elseif($orderInfo['cattype']=='4' || $orderInfo['cattype']=='5'){ echo $orderInfo['catdiscouratio']?$orderInfo['catdiscouratio']:'0.00'.'%';} ?></i></li>
                <li>优惠口令：</li>
                <li>无</li>
                <li>口令效用：</li>
                <li>无</li>
                <li>积分抵扣：</li>
                <li>无</li>
                <li>支付方式：</li>
                <li>微信支付</li>
            </ul>
        </div>
    </div>
    <div class="wxmd_main_box2">
        <div class="wxmd_main_son">
            <ul class="wxmd_main_alt">
                <li>实付金额：</li>
                <li class="wxmd_main_aat"><i>¥ <b><?php echo $orderInfo['subtotal']?$orderInfo['subtotal']:'0.00'; ?></b></i></li>
            </ul>
        </div>
    </div>
    <div class="wxmd_bluubut">
        <button onclick="callpay();"><span><i><?php echo $orderInfo['subtotal']?$orderInfo['subtotal']:'0.00'; ?></i>元</span>确认买单</button>
    </div>
</div>

<?php }elseif($orderInfo['paystate'] == '2'){?>
<?php echo "<p style='font-size: 20px;font-weight: bold;'>本订单支付成功</p>";?>
<?php }else{?>
<?php echo "<p style='font-size: 20px;font-weight: bold;'>本订单支付失败</p>";?>
<?php }?>


<script type="text/javascript">
	//调用微信JS api 支付
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo $jsApiParameters; ?>,
			function(res){
				if(res.err_msg == "get_brand_wcpay_request:ok" ) {
					window.location.href="<?php echo site_url.'/index.php?g=Wap&m=WechatPayBill&a=PaySuccess&companyid='.$companyid.'&orderid='.$orderid;?>";
				}
				/* WeixinJSBridge.log(res.err_msg);
				WeixinJSBridge.log(res.err_code+res.err_desc+res.err_msg); */
			}
		);
	}
	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}
</script>
<script src="../../../Tpl/Wap/default/common/js/jweixin-1.0.0.js"></script>
<script>
wx.config({
  debug: false,
  appId: '<?php echo $signPackage["appId"];?>',
  timestamp: <?php echo $signPackage["timestamp"];?>,
  nonceStr: '<?php echo $signPackage["nonceStr"];?>',
  signature: '<?php echo $signPackage["signature"];?>',
  jsApiList: [   
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
	// config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。
    var diytitle = "<?php echo htmlspecialchars_decode($info['sharefriendstitle']);?>";
    var diylink = "<?php echo $info['shareurl'];?>";
    var diyimgUrl = "<?php echo $info['shareimg']; ?>";
    var diydesc = "<?php echo htmlspecialchars_decode($info['sharedes']);?>";
    //获取“分享到朋友圈”
	wx.onMenuShareTimeline({
	    title: diytitle, // 分享标题
	    link: diylink, // 分享链接
	    imgUrl: diyimgUrl, // 分享图标
	    success: function () { 
	        // 用户确认分享后执行的回调函数
	    },
	    cancel: function () { 
	        // 用户取消分享后执行的回调函数
	    },
        fail: function (res) {
          $('#ceshicontent').html(JSON.stringify(res));
        }
	});
    //获取“分享给朋友”
	wx.onMenuShareAppMessage({
	    title: diytitle, // 分享标题
	    link: diylink, // 分享链接
	    imgUrl: diyimgUrl, // 分享图标
	    desc: diydesc, // 分享描述
	    type: '', // 分享类型,music、video或link，不填默认为link
	    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
	    success: function () { 
	        // 用户确认分享后执行的回调函数
	    },
	    cancel: function () { 
	        // 用户取消分享后执行的回调函数
	    }
	});
});
</script>

</body>
</html>