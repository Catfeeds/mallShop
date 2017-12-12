<?php
header("Content-type: text/html; charset=utf-8");
/* error_reporting(E_ALL);
ini_set('display_errors', '1'); */
require './function.inc.php';
$db = new db();

$getData = $_POST;
$getData = json_encode($getData);
$getData = json_decode($getData,true);
$time = time();
if($getData){
    $companyid = MOBIWIND_COMPANYID;
    
    $logdata['log'] = 'AliPay:'.json_encode($getData);
    $logdata['out_trade_no'] = $getData['out_trade_no'];
    $logdata['paytype'] = 2;
    $logdata['companyid'] = $companyid;
    $logdata['createtime'] = $time;
    $db->table('check_hardware_order_pay_log')->data($logdata)->insert(); // 支付日志添加
    
    $paywhere['out_trade_no'] = $getData['out_trade_no'];  //通过数据读取该值，支付宝配置表
    $mobpayInfo = $db->table('check_hardware_order')->where($paywhere)->field('id,ordertype')->find();
    $aliwhere['companyid'] = $companyid;
    $aliwhere['isshow'] = '1';
    $aliInfo = $db->table('company_pay_alipay')->where($aliwhere)->field('id,pid,merchant_private_key,alipay_public_key')->find();
    if($mobpayInfo&&$aliInfo['pid']&&$aliInfo['alipay_public_key']){
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
                if($mobpayInfo['ordertype'] == '1' || $mobpayInfo['ordertype'] == '2'){   // 状态=支付中
                    $payData['ordertype'] = '3';
                    $payData['transaction_id'] = $getData['trade_no'];
                    $payData['paytime'] = strtotime($getData['notify_time']);
                    $payData['updatetime'] = $time;
                    $return = $db->table('check_hardware_order')->data($payData)->where($paywhere)->update();
                    if($return){
                        echo 'success';
                    }
                }
            }
        }
    }
}