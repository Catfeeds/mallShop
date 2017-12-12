<?php
header("Content-type: text/html; charset=utf-8");
error_reporting(E_ALL);
ini_set('display_errors', '1');
/* require './AopSdk.php'; */
require './function.inc.php';
require './f2fpay/model/builder/AlipayTradePrecreateContentBuilder.php';
require './f2fpay/service/AlipayTradeService.php';
$sendData = json_decode(file_get_contents('php://input'),true);
if($sendData['type']=='orderCreate'){
    /**
     *  预下单
     *  参数	                                                参数名称	                        类型	                        必填	                描述	                    范例
     *  out_trade_no	                 商户订单号	         String(64)	              是	                          商户订单号,64个字符以内、可包含字母、数字、下划线；需保证在商户端不重复	20150320010101001
     *  total_amount	                 订单总金额	         String(9)	              是	                          订单总金额，单位为元，精确到小数点后两位，取值范围[0.01,100000000]。如果同时传入【可打折金额】和【不可打折金额】，该参数可以不用传入；如果同时传入了【可打折金额】，【不可打折金额】，【订单总金额】三者，则必须满足如下条件：【订单总金额】=【可打折金额】+【不可打折金额】	88.88
     *  subject	                                     订单标题	         String(256)	    是	                           订单标题	Iphone6 16G
     *  scene	                                     支付场景                              String	                        否                             支付场景 条码支付，取值：bar_code 声波支付，取值：wave_code
     *  auth_code	                           支付授权码                            String	                        否                             支付授权码 最大长度32	
     *  seller_id	                           卖家支付宝用户ID	     String(28)	              否	                          如果该值为空，则默认为商户签约账号对应的支付宝用户ID	2088102146225135
     *  discountable_amount	       可打折金额	         String(9)	              否	                          参与优惠计算的金额，单位为元，精确到小数点后两位，取值范围[0.01,100000000]。如果该值未传入，但传入了【订单总金额】和【不可打折金额】，则该值默认为【订单总金额】-【不可打折金额】	8.88
     *  undiscountable_amount  不可打折金额	     String(9)	              否	                          不参与优惠计算的金额，单位为元，精确到小数点后两位，取值范围[0.01,100000000]。如果该值未传入，但传入了【订单总金额】和【可打折金额】，则该值默认为【订单总金额】-【可打折金额】	80.00
     *  body	                                     订单描述	         String(128)	    否	                          对交易或商品的描述	Iphone6 16G
     *  goods_detail[]	                 商品明细列表	         String	                        否	                          订单包含的商品列表信息，Json格式，其它说明详见商品明细说明	[{“goods_id”:“apple-01”,“goods_name”:“ipad”,“goods_category”:“7788230”,“price”:“2000.00”,“quantity”:“1”}]
     *  operator_id	                           商户操作员编号	     String(28)	              否	                          商户操作员编号	Yx_001
     *  store_id	                           商户门店编号	         String(32)	              否	                          商户门店编号	NJ_001
     *  terminal_id	                           机具终端编号	         String(32)	              否	                          商户机具终端编号	NJ_T_001
     *  extend_params[]	                 扩展参数	         String(512)	    否	                          业务扩展参数，sys_service_provider_id：系统商编号	{“sys_service_provider_id”:“ 2088511833207846”}
     *  time_expire	                           支付超时时间	         String	                        否	                          该笔订单允许的最晚付款时间，逾期将关闭交易。格式为：yyyy-MM-dd HH:mm:ss	2015-01-01 11:01:01
     *  royalty_info	                 分账信息	         String(2000)	    否	                          描述分账信息，Json格式，其它说明详见分账说明	{“royalty_type”:“ROYALTY”,“royalty_detail_infos”:[{“serial_no”:“1”,“trans_out”:“2088101126765726”,“trans_in”:“2088101126708402”,“amount”:“0.10”,“desc”:“分账测试1”},{“serial_no”:“2”,“trans_out”:“2088101126765726”,“trans_in”:“2088101126707869”,“amount”:“0.10”,“desc”:“分账测试2”}]}
     *
     * @param unknown $biz_content
     * @return Ambigous <Ambigous, boolean, mixed>
     * @author Lando<806728685@qq.com>
     * @since  2015-12-18
     *
     */
    if($sendData['payType'] == 'qrPay'){
        // 创建请求builder，设置请求参数
        $biz_content = $sendData['bizContent'];
        $qrPayRequestBuilder = new AlipayTradePrecreateContentBuilder();
        $qrPayRequestBuilder->setOutTradeNo($biz_content['out_trade_no']);
        $qrPayRequestBuilder->setTotalAmount($biz_content['total_amount']);
        $qrPayRequestBuilder->setSubject($biz_content['subject']);
        // 调用qrPay方法获取当面付应答
        $baseConfig = $sendData['base'];
        $baseConfig['gatewayUrl'] = 'https://openapi.alipay.com/gateway.do';
        $baseConfig['charset'] = 'utf-8';
        $baseConfig['sign_type'] = 'RSA2';
        $baseConfig['MaxQueryRetry'] = '10';// 最大查询重试次数
        $baseConfig['QueryDuration'] = '3';// 查询间隔
        $qrPay = new AlipayTradeService($baseConfig);
        $qrPayResult = $qrPay->qrPay($qrPayRequestBuilder);
        echo json_encode(array('alipay_trade_precreate_response'=>$qrPayResult->getResponse()));
        exit();
    }
    /* 
    $biz_content = $sendData['biz_content'];
    $app_id = $sendData['app_id'];
    $notify_url = $sendData['notify_url'];
    $request = new AlipayTradePrecreateRequest();
    $request->setBizContent(json_encode($biz_content));
    $response = scan_code_pay_request_execute( $request ,$app_id , $notify_url, NUll); */
}elseif ($sendData['type']=='queryOrder'){
    /**
     *
     * 订单查询
     *
     * @param unknown $out_trade_no  商户订单号
     * @return Ambigous <Ambigous, boolean, mixed>
     * @author Lando<806728685@qq.com>
     * @since  2015-12-18
     */
    $biz_content = $sendData['biz_content'];
    $app_id = $sendData['app_id'];
    $notify_url = $sendData['notify_url'];
    $request = new AlipayTradeQueryRequest();
    $request->setBizContent ( json_encode($biz_content) );
    $response = scan_code_pay_request_execute ( $request ,$app_id, $notify_url);
    echo json_encode($response);
}elseif ($sendData['type']=='cancelOrder'){
    /**
     *
     * 订单取消
     *
     * @param unknown $out_trade_no 商户订单号
     * @return Ambigous <Ambigous, boolean, mixed>
     * @author Lando<806728685@qq.com>
     * @since  2015-12-18
     */
    $biz_content = $sendData['biz_content'];
    $app_id = $sendData['app_id'];
    $notify_url = $sendData['notify_url'];
    $request = new AlipayTradeCancelRequest();
    $request->setBizContent ( json_encode($biz_content) );
    $response = scan_code_pay_request_execute ( $request, $app_id, $notify_url);
    echo json_encode($response);
}elseif ($sendData['type']=='refundOrder'){
    /**
     *
     * 申请退款
     * trade_no	支付宝交易号	String(64)	是	支付宝交易凭证号	2014112611001004680073956707
     * refund_amount	退款金额	String(9)	是	需要退款的金额，该金额不能大于订单金额,单位为元，支持两位小数	200.12
     * out_request_no	商户退款请求号	String(64)	否	标识一次退款请求，同一笔交易多次退款需要保证唯一	HZ01RF001
     * refund_reason	退款原因	String(128)	否	退款的原因说明	正常退款
     * store_id	商户的门店编号	String(32)	否	商户的门店编号	NJ_S_001
     * terminal_id	商户的终端编号	String(32)	否	商户的终端编号	NJ_T_001
     * @param unknown $biz_content  退款内容格式
     * @return Ambigous <Ambigous, boolean, mixed>
     * @author Lando<806728685@qq.com>
     * @since  2015-12-18
     */
    $biz_content = $sendData['biz_content'];
    $app_id = $sendData['app_id'];
    $notify_url = $sendData['notify_url'];
    $request = new AlipayTradeRefundRequest();
    $request->setBizContent ( json_encode($biz_content) );
    $response = scan_code_pay_request_execute ( $request, $app_id, $notify_url);
    echo json_encode($response);
}
