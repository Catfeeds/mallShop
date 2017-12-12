<?php
ini_set('date.timezone','Asia/Shanghai');
header("Content-type: text/html; charset=utf-8");
include_once("../Common/common.php");

include_once("../../../LightpenCms/Lib/ORG/TicketMachineS1.class.php");

//使用通用通知接口
$xml = $GLOBALS['HTTP_RAW_POST_DATA'];

$xmldata = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
$out_trade_no = $xmldata['out_trade_no'];
$openid = $xmldata['openid'];
$db = new db();
$time = time();
//存日志
$logdata['out_trade_no'] = $out_trade_no ? $out_trade_no : '';
$logdata['log'] = $xml ? $xml : '' ;
$logdata['companyid'] = 0;
$logdata['createtime'] = $time;
$addOrderLog = $db->table('wechat_check_order_pay_log')->data($logdata)->insert();
$orderInfo = $db->table('wechat_check_order')->where(array('out_trade_no' => $out_trade_no))->field('companyid,shopsid,paystate,orderid,totalprice,subtotal,actname,cattype,catdiscouratio,catdiscoumoney,keyname,keytype,keydiscoumoney,keygiftname,payendtime,mid')->find();
if($orderInfo['mid']){
    $memberInfo = $db->table('member_register_info')->where(array('id'=>$orderInfo['mid']))->field('name,moblie')->find();
}
if($orderInfo['paystate'] == '1'){
    $return = $db->table('wechat_check_order')->data(array('paystate'=>'2','openid'=>$openid,'payendtime'=>$time,'updatetime'=>$time))->where(array('out_trade_no'=>$out_trade_no))->update();
    if($return){
        if($orderInfo['shopsid']){
            // 查询门店信息
            $shopInfo = $db->table('company_shops')->where(array('id' => $orderInfo['shopsid']))->field('printid,printkey')->find();
            if($shopInfo['printid'] && $shopInfo['printkey']){
                $option = '';
                $option['device_no'] = $shopInfo['printid'];
                $option['key'] = $shopInfo['printkey'];
                $ticket = new TicketMachineS1($option);
                
                $content = "^N1^F1\n";
                $content .= "^B3 微信买单\n";
                $content .= "交易编码：";  $content .= $orderInfo['orderid'];  $content .= "\n";
                $content .= "消费总额：";  $content .= $orderInfo['totalprice']?$orderInfo['totalprice']:'0.00';  $content .= "\n";
                $content .= "微信买单优惠活动：\n";
                $content .= $orderInfo['actname']?$orderInfo['actname']:'无'; $content .= "\n";
                $content .= "活动效用：- ";
                if($orderInfo['cattype']=='1' || $orderInfo['cattype']=='2' || $orderInfo['cattype']=='3'){
                    $content .= $orderInfo['catdiscoumoney']?$orderInfo['catdiscoumoney']:'0.00';
                    $content .= '元';
                }elseif($orderInfo['cattype']=='4' || $orderInfo['cattype']=='5'){
                    $content .= $orderInfo['catdiscouratio']?$orderInfo['catdiscouratio']:'0.00';
                    $content .= '%';
                }else{
                    $content .= '0.00';
                }
                $content .= "\n";
                
                $content .= "优惠口令：\n";
                $content .= $orderInfo['keyname']?$orderInfo['keyname']:'无'; $content .= "\n";
                $content .= "口令效用：- "; $content .= $orderInfo['keydiscoumoney']?$orderInfo['keydiscoumoney']:'0.00';  $content .= "\n";
                $content .= "积分抵扣：- 0.00\n";
                $content .= "支付方式：微信支付\n";
                $content .= "商户单号：";  $content .= $out_trade_no;  $content .= "\n";
                $content .= "付款时间：";  $content .= date('Y-m-d H:i',$time);  $content .= "\n";
                $content .= "实付金额：\n";
                // $content .= "^B2 ";  $content .= $orderInfo['subtotal']?$orderInfo['subtotal']:'0.00';  $content .= "\n";
                $content .= "^B2 ";
                
                $subtotal = $orderInfo['subtotal']?$orderInfo['subtotal']:'0.00';
                $subtotaLen = strlen($subtotal);
                $sunLen = '16';
                $surLen = $sunLen-$subtotaLen;
                $nbsp = '';
                if($surLen > 0){
                    for($i=1; $i<$surLen; $i++){
                        $nbsp .= ' ';
                    }    
                }
                $content .= $nbsp;  $content .= $subtotal;  $content .= "\n";
                
                $content .= "^B2================================\n";
                $content .= "付款会员姓名：";  $content .= $orderInfo['mid']?$memberInfo['name']:'未注册';  $content .= "\n";
                $content .= "付款会员手机：";  $content .= $orderInfo['mid']?$memberInfo['moblie']:'未注册';  $content .= "\n";

                $ticket->sendPrintDataInfo($content);
            }
        }
        echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
    }
}
