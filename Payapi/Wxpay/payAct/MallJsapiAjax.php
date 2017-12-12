<?php
ini_set('date.timezone','Asia/Shanghai');
header("Content-type: text/html; charset=utf-8");
require_once "../lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';
include_once("../Common/common.php");
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
$db = new db();
//$orderid ='131179823887760173';

$time = time();
$orderid =$_GET['orderid'];
if(!$orderid){
    $ajax['code'] = 300;
    $ajax['msg']="订单号错误";
    echo json_encode($ajax);exit;
}
$openId =$_GET['openid'];
if(!$openId){
    $ajax['code'] = 300;
    $ajax['msg']="获取个人信息失败";
    echo json_encode($ajax);exit;
}
$orderInfo = $db->table('mall_order_info')->where(array('orderid'=>$orderid))->find();
$companyid = 1;

//"微信公众号信息配置错误"
$wechatsInfo = $db->table('wechats')->where(array('companyid'=>$companyid))->find();

if(!$wechatsInfo){
    $ajax['code'] = 300;
    $ajax['msg']="微信公众号信息配置错误";
    echo json_encode($ajax);exit;
}

//状态为2，未完成
if($orderInfo['orderstatus'] == 1){

    //下面的错误
    $out_trade_no = $orderInfo['linkoutorderid'] ? $orderInfo['linkoutorderid'] : get_order_id();
    if($out_trade_no){
        $serialNumberOI = $db->table('mall_order_info')->where(array('companyid'=>$companyid,'createtime'=>array('between',array($time,$time+1))))->count();
        $out_trade_no = newOrderID('3', 'E', $companyid).'E'.($serialNumberOI+1);
        $updateOrderInfoReturn = $db->table('mall_order_info')->data(array('linkoutorderid'=>$out_trade_no,'updatetime'=>time()))->where(array('orderid'=>$orderid,'companyid'=>$companyid))->update();
    }

    $cardvalue = $orderInfo['orderprice']*100;//金额

    $options['appid'] = $wechatsInfo['appid'];
    $options['mchid'] = $wechatsInfo['mch_id'];
    $options['key'] = $wechatsInfo['key'];
    $options['appsecret'] = $wechatsInfo['appsecret'];
    $tools = new JsApiPay($options);
    //②、统一下单
    $input = new WxPayUnifiedOrder($options);
    $input->SetBody('商城订单');//商品描述
    $input->SetOut_trade_no($out_trade_no);
    $input->SetTotal_fee($cardvalue);
    $input->SetNotify_url( "http://".$_SERVER['SERVER_NAME']."/Payapi/Wxpay/payAct/MallNotify.php");
    $input->SetTrade_type("JSAPI");
    $input->SetOpenid($openId);
    $WxPayApi = new WxPayApi($options);
    $order = $WxPayApi->unifiedOrder($input);
    $jsApiParameters = $tools->GetJsApiParameters($order);

    $asaTest = json_decode($jsApiParameters,true);
    $ajax['msg'] = 'success';
    $ajax['jsApiParameters'] = $jsApiParameters;
    $ajax['appId'] = $asaTest['appId'];
    $ajax['nonceStr'] = $asaTest['nonceStr'];
    $ajax['package'] = $asaTest['package'];
    $ajax['signType'] = $asaTest['signType'];
    $ajax['timeStamp'] = $asaTest['timeStamp'];
    $ajax['paySign'] = $asaTest['paySign'];
    $ajax['code'] = 200;
    echo json_encode($ajax);
}

?>
