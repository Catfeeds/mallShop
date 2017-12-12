<?php
ini_set('date.timezone','Asia/Shanghai');
header("Content-type: text/html; charset=utf-8");
include_once("../Common/common.php");
//使用通用通知接口
$xml = $GLOBALS['HTTP_RAW_POST_DATA'];

$xmldata = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
$out_trade_no = $xmldata['out_trade_no'];
$db = new db();
//存日志
$logdata['out_trade_no'] = $out_trade_no ? $out_trade_no : '';
$logdata['log'] = $xml ? $xml : '' ;
$logdata['companyid'] = 113;
$logdata['createtime'] = time();

$orderInfo = $db->table('mall_order_info')->where(array('out_trade_no' => $out_trade_no))->find();
if($orderInfo['orderstatus'] == 1 ){ /* ,"vipendtime"=>(time()+24*3600*365) */
    $res = $db->table("mall_order_info")->data(array('orderstatus'=>'2','paytime'=>time(),'updatetime'=>time()))
    ->where(array('out_trade_no'=>$out_trade_no))->update();
	if($res){
        echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
	}
}elseif($orderInfo['orderstatus'] == 2 ){
    echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
}



