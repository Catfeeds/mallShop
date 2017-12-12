<?php 
ini_set('date.timezone','Asia/Shanghai');
header("Content-type: text/html; charset=utf-8");
//error_reporting(E_ERROR);
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
require_once "../lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';
require_once "WxPay.NativePay.php";
include_once("../Common/common.php");

$returnData['code'] = 300;
$returnData['tips'] = '抱歉，服务器繁忙，请稍后重试';

$db = new db();
$orderid = $_GET['orderid'];
$type = $_GET['type'] ? $_GET['type'] : 0;

$orderInfo = $db->table('mall_order_info')->where(array('orderid'=>$orderid))->field('mid,out_trade_no,outtradenoisuser,companyid,orderid,ordertitle,orderstatus,paytime,createtime,orderprice')->find();
if($type==1){
	if(!$orderid || !$orderInfo['companyid']){
	    $returnData['tips'] = '抱歉，订单信息错误';
	}
	$companyid = $orderInfo['companyid'];
	$wechatsInfo = $db->table('wechats')->where(array('companyid'=>$companyid,'wechattype'=>'4'))->field('appid,appsecret')->find();
	if(!$wechatsInfo){
	    $returnData['tips'] = '微信公众号信息配置错误';
	}
	if($orderInfo['orderstatus'] == 1){
		$wechatPay = $db->table('company_pay_wechat')->where(array('companyid'=>$companyid,'isshow'=>1,'isempower'=>1))->field('toaccount,keypassword')->find();
		if(!$wechatPay){
			echo "<p style='font-size: 20px;font-weight: bold;'>支付商户号错误</p>";
			exit();
		}
		$options['appid'] = $wechatsInfo['appid'];
		$options['mchid'] = $wechatPay['toaccount'];
		$options['key'] = $wechatPay['keypassword'];
		$options['appsecret'] = $wechatsInfo['appsecret'];
		$jsApiParameters = '';
	
		$input2 = new WxPayUnifiedOrder($options);     // 统一下单
	
		$body = get_substr($orderInfo['ordertitle'],30);//商品描述
		$encode = mb_detect_encoding($body, array('ASCII','UTF-8','GB2312','GBK','BIG5'));
		if ($encode == 'GBK'){
			$body = iconv('GBK','UTF-8',$body);
		}elseif ($encode == 'GB2312'){
			$body = iconv('GB2312','UTF-8',$body);
		}elseif ($encode == 'ASCII'){
			$body = iconv('ASCII','UTF-8',$body);
		}elseif ($encode == 'BIG5'){
			$body = iconv('BIG5','UTF-8',$body);
		}
		$input2->SetBody($body);
		$cardvalue = $orderInfo['orderprice']*100;//金额
		$out_trade_no = $orderInfo['out_trade_no'] ;
		if($orderInfo['outtradenoisuser'] == 1 || !$out_trade_no){
			$out_trade_no=get_order_id();
			if($orderid&&$companyid){
				$updateOrderInfoReturn = $db->table('mall_order_info')->data(array('out_trade_no'=>$out_trade_no,'outtradenoisuser'=>2))->where(array('orderid'=>$orderid,'companyid'=>$companyid))->update();
				if(!$updateOrderInfoReturn){
					echo "<p style='font-size: 20px;font-weight: bold;'>微信支付接口异常</p>";
					exit();
				}
			}else{
				echo "<p style='font-size: 20px;font-weight: bold;'>error:334</p>";
				exit();
			}
		}
		$input2->SetOut_trade_no($out_trade_no);     // 订单号
	
		$actualamount = $orderInfo['orderprice']?$orderInfo['orderprice']:'1';
		$input2->SetTotal_fee($cardvalue);  // 设置订单总金额
		$input2->SetTime_start(date("YmdHis"));    // 订单生成时间
		$input2->SetTime_expire(date("YmdHis", time() + 600));   // 订单失效时间（10分钟之后）
		//$input->SetGoods_tag("商品标记");    // 商品标记
		$input2->SetNotify_url(site_url."/Payapi/Wxpay/payAct/notify.php");
		$input2->SetTrade_type("NATIVE");
		$input2->SetProduct_id($out_trade_no);
		 
		$notify = new NativePay($options);
		$result = $notify->GetPayUrl($input2);   // 生成支付URL
		$url = $result["code_url"];
		$returnData['code'] = 200;
		$returnData['url'] = urlencode($url);
		$url = urlencode($url);
	}
	echo $url?$url:'没获取到订单信息';
}elseif($type==2){
	if(($orderInfo['orderstatus'] == '2'||$orderInfo['orderstatus'] == '4')&&$orderInfo['paytime']){
		$returnData['code'] = 200;
		$returnData['tips'] = '支付成功';
	}
	echo $returnData['code'];
}elseif($type == 3){
	if(!$orderid || !$orderInfo['companyid']){
		$returnData['tips'] = '抱歉，订单信息错误';
	}
	$companyid = $orderInfo['companyid'];
	$updateOrderStatusReturn = $db->table('mall_order_info')->data(array('orderstatus'=>'5'))->where(array('orderid'=>$orderid,'companyid'=>$companyid))->update();
	//将商品的库存数据进行回滚
	if($updateOrderStatusReturn){
		$orderGoods = $db->table('mall_order_goods')->where(array('orderid'=>$orderid,'companyid'=>$companyid))->field('id,goodtype,goodid,goodnum,goodskuid')->select();
		if($orderGoods){
			foreach($orderGoods as $oKey=>$oVal){
				$mallstockamountInfo = $db->table('mall_goods')->where(array('id'=>$oVal['goodid'],'companyid'=>$companyid))->field('id,stockamount')->find();
				$stocknum = $mallstockamountInfo['stockamount']+$oVal['goodnum'];
				$db->table('mall_goods')->data(array('stockamount'=>$stocknum,'updatetime'=>time()))->where(array('id'=>$oVal['goodid'],'companyid'=>$companyid))->update();
				if($oVal['goodtype'] == 1 || $oVal['goodtype'] == 3 || $oVal['goodtype'] == 4 || $oVal['goodtype'] == 5){
					$skustockamountInfo = $db->table('mall_goods_sku')->where(array('goodid'=>$oVal['goodid'],'id'=>$oVal['goodskuid'],'companyid'=>$companyid))->field('id,stockamount')->find();
					$skustocknum = $skustockamountInfo['stockamount']+$oVal['goodnum'];
					$mallgoodsskustockamount = $db->table('mall_goods_sku')->data(array('stockamount'=>$skustocknum,'updatetime'=>time()))->where(array('id'=>$oVal['goodskuid'],'companyid'=>$companyid))->update();
				}
			}
		}
	}
	if($updateOrderStatusReturn){
		$returnData['code'] = 200;
		echo $returnData['code'];
	}else{
		echo "<p style='font-size: 20px;font-weight: bold;'>微信支付接口异常</p>";
		exit();
	}
}

?>
