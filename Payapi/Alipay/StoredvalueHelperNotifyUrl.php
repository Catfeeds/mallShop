<?php
header("Content-type: text/html; charset=utf-8");
/* error_reporting(E_ALL);
ini_set('display_errors', '1'); */
require './function.inc.php';
$db = new db();
$get = $_POST;
$getJson = json_encode($get);
$getData = json_decode($getJson, true);
$time = time();
if($getData){
    $payWhere['linkoutorderid'] = $getData['out_trade_no'];  // 通过商户订单号查询记录
    $orderInfo = $db->table('storedvalue_helper_goods_order')->where($payWhere)->field('companyid,status')->find();
    $companyid = $orderInfo['companyid'];
    // 支付日志
    $logdata['log'] = 'AliPay:'.json_encode($getData);
    $logdata['out_trade_no'] = $getData['out_trade_no'];
    $logdata['paytype'] = 2;
    $logdata['companyid'] = $companyid;
    $logdata['createtime'] = $time;
    $db->table('storedvalue_helper_goods_order_pay_log')->data($logdata)->insert();
    
    $aliwhere['companyid'] = $companyid;
    $aliInfo = $db->table('company_pay_alipay')->where($aliwhere)->field('id,pid,merchant_private_key,alipay_public_key')->find();
    if($orderInfo&&$aliInfo['pid']&&$aliInfo['alipay_public_key']){
        $pid = $aliInfo['pid'];//通过数据读取该值，支付宝配置表
        //验证消息 ID
        $notify_id = $getData['notify_id'];
        $checkUrl = 'https://mapi.alipay.com/gateway.do?service=notify_verify&partner='.$pid.'&notify_id='.$notify_id;
        $checkUrlReturn = http_get($checkUrl);
        if($checkUrlReturn == 'true'){
            //验证签名
            require './aop/AopClient.php';
            $as = new AopClient ();
            // 支付宝公钥
            $as->alipayrsaPublicKey=$aliInfo['alipay_public_key'];
            $checkedReturn = $as->rsaCheckV1($getData,'','RSA2');
            if($checkedReturn){
                if($orderInfo['status'] == 1){ // 支付中
                    // 操作订单信息
                    $payData['status'] = '2';
                    $payData['paytime'] = strtotime($getData['notify_time']);
                    $payData['updatetime'] = $time;
                    $return = $db->table('storedvalue_helper_goods_order')->data($payData)->where($payWhere)->update();
                    if($return){
                        echo 'success';
                    }
                }
            }
        }
    }
}