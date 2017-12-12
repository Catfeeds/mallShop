<?php
header("Content-type: text/html; charset=utf-8");
/* error_reporting(E_ALL);
ini_set('display_errors', '1');  */
require './function.inc.php';
$db = new db();
$get = $_POST;
$getJson = json_encode($get);
$getData = json_decode($getJson, true);
if($getData){
    $time = time();
    $payWhere['out_trade_no'] = $getData['out_trade_no'];  // 通过商户订单号查询记录
    $orderInfo = $db->table('shop_cashier_order_pay')->where($payWhere)->field('id,companyid,paystate')->find();
    
    $data['log'] = 'AliPay:'.json_encode($getData);
    $data['companyid'] = $orderInfo['companyid'];
    $data['createtime'] = $time;
    $db->table('shop_cashier_order_pay_log')->data($data)->insert();
    
    if($orderInfo){
        $companyid = $orderInfo['companyid'];
        $aliwhere['companyid'] = $companyid;
        $aliwhere['isshow'] = '1';
        $aliInfo = $db->table('company_pay_alipay')->where($aliwhere)->field('id,pid,merchant_private_key,alipay_public_key')->find();
        if($aliInfo['pid']&&$aliInfo['alipay_public_key']){
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
                    if($orderInfo['paystate']==1 || $orderInfo['paystate']==2){   // 支付中
                        // 操作订单信息
                        $payData['paystate'] = '3';
                        $payData['transaction_id'] = $getData['trade_no'];
                        $payData['paydonetime'] = strtotime($getData['notify_time']);
                        $payData['updatetime'] = $time;
                        $return = $db->table('shop_cashier_order_pay')->data($payData)->where($payWhere)->update();
                        if($return){
                            echo 'success';
                        }
                    }
                }
            }
        }
    }
}