<?php

define('site_url', 'http://'.$_SERVER['SERVER_NAME']);
include '../../LightpenData/PayDB/config.db.php';// 引入数据库配置文件
include '../../LightpenData/PayDB/db.class.php';// 引入数据库操作类


function writeLog($text) {
	// $text=iconv("GBK", "UTF-8//IGNORE", $text);
	$text = characet ( $text );
	file_put_contents ( dirname ( __FILE__ ) . "/log/log.txt", date ( "Y-m-d H:i:s" ) . "  " . $text . "\r\n", FILE_APPEND );
}

//转换编码
function characet($data) {
	if (! empty ( $data )) {
		$fileType = mb_detect_encoding ( $data, array (
				'UTF-8',
				'GBK',
				'GB2312',
				'LATIN1',
				'BIG5' 
		) );
		if ($fileType != 'UTF-8') {
			$data = mb_convert_encoding ( $data, 'UTF-8', $fileType );
		}
	}
	return $data;
}

/**
 * 扫码支付，使用SDK执行接口请求
 * @param unknown $request
 * @param string $token
 * @return Ambigous <boolean, mixed>
 */
function scan_code_pay_request_execute($request, $app_id = '', $notify_url, $token = NULLL) {
    
    $config = array (
            'alipay_public_key_file' => dirname ( __FILE__ ) . "/key/alipay_rsa_public_key.pem",
            'merchant_private_key_file' => dirname ( __FILE__ ) . "/key/rsa_private_key.pem",
            'merchant_public_key_file' => dirname ( __FILE__ ) . "/key/rsa_public_key.pem",
            'charset' => "GBK",
            'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
            // 'notify_url' => "http://www.mobiwind.cn/Payapi/Alipay/ScanCodePaymentNotifyUrl.php"
            'notify_url' => $notify_url
    );
    //  支付宝公钥：MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAp9xs4MXv05tUsm40wOAIrAMR1hEfd9LsOlYkyC847hMPVkuPXDfbLnGdx989THfwkabNMWgT1iy0iVeX9aWG+lUSr6gls2Zizg+vcd9O9i0dP2l1tz36C3NAZOW/vd4cFwRzvb45q3t/0J5ovvoA619mB7b+Jd36xHToBw9+7NNDiaxQg4XOE8vhhQ3LK4vlfkpiAPny2AAdRfv7d3bbfQSNbE8X3Mtk5vRmYnIxJyDjXwpSSJkHYpxjQYRWxXxVlIq2Sm8V9B77zIvWXDm191nU/ypG6yaduf03Hj5iX8HOIu9FbJrwtte95gvzmpNC5Cr5UQ/tMmQzdsAuW8kLEQIDAQAB
    //  应用私钥 ： MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCwZYoTaRElHCDiUruPVB8dlvZHIV4HvGwJnD2yI9ZyX0btoRyfkBkfmDu+Rq36fuZ7MLPR2qSckvnw5zhN5H/HwhW++e/hxQ0BYEhCt1lcRBnqbXAtqGkiutSLPan1bDOfTvhWHiIC63LRY+sCirtYeIMX6kNQl0jf6XB9ej6qkN4d+qwNy7rkLKALqEKVOSsMMSz+/xV/IhOLaNuxJeqUdsynIDvxLgpMDFpwdsyjxiRbKROBiWaLXUMiPGC192GDWrtxaycm1YWUlgKRsBSD0euAVSYpsMpYGuhTbhsWFVLRTRbgXOHTMyysO12D/lKbQIfYthzJWK0YDgX/0r71AgMBAAECggEAVegNl6hqpiUNyss59vKj+LgllrykW6YjA4co8NHNRYQrOd1l4DvAUIfGSMOJRV56BvLQEFqoImzd9rnUzPKEXJekGY8FiXF7BPmMF5nNlkbATFAh+spy8WwFyQ4t8I1SkrpV7TLdo4hhMaK0uFcyKEMArSv0vpH+9rVsiF95l30IxxoN1jrrVPWDhkuaKPUidM6qrP16oX9amSnDsECND78KmXFGIOLdM9kqL11nB2x6EIQFd5PsZzAiZC2MefqKOqOXGTQRViJonfHH+7+viXt2rkzeNuinTv4UD1Pj5Qd9zOjbN6Rum0O/1oDIIj1eyix/DAlo7xGoJ+8a6wn1YQKBgQDe58rLuL4MsqZ8RoZ4jHm1OKTLeVBLymhyDZ1Jmh9DGiKUw/447poB8ec33Ws5ump/uqkTB7xXt0PCK+KBgk6TP8hZv1jyoZKXzoZ1dV9Bg6yUpEuvweYQkEKrzUkmFVELNh55Vgd27ZaEvFJ9Q2aBDqmszWqCCUw6D8cxnlquqQKBgQDKlgl3FLqCGqRRcSlCzIZ8NA/MRhnBoyCqUXH8zHBJIoAW3qZ2ddI7ti5LZ3B9DFlD1whjQ2J+YYaxwBc+7xLIbm+50FUP76CbE4vqE1O5t7fOTqZcgg+jgnxVaMfVbwqp49AveZKvve4fW1wCb9LRwNvK7RG8EJSuTzHM3TT5bQKBgQDHMFLkYZ5TMoHbpBoeN0m7S3bX7Px3/dP/t3LKhtNQ31XvooTW00EEY+q+3TooSBFLOr5DpzIDIF2IcjbijFMy+lhK+ta9Wuzog0FcTRpmLQhXe8m2sYqpWoB5jeTEB2B4qE/rFZluCTnsO21rSoOa6ENvi9CVi0F20QqrqFpYoQKBgHIEkZ0w0GurPPINY3CSFdrPnliU16gw/JB/OE1UbpnqzfaRJ1UiqMksls+fjyMCETdqTfE4GrYQg7OE7lBgU8z0wvLsewN2FYlSLfv3Frc+3DdD8sO7+7tGFF4pk9bfITxO2Hvvy4wXtG53h8hjUV72UX3bgZC8MEIOlnO7yDXdAoGAFaQIy8OzNkOPeMCv/N8KxzqQTnkW4+KKSo+pWyPttO9ozCPeqtsebOBhMfzyHc+xiE6upyLGY6y/XpeAKPzhY9li+BQH+rKClqG9O/hKWyA47KgERNNKcgAFnDDob5mr1i47P0b4y3fjKZAmUngrg84k5Kdl08PPjoLb/Z0gRJ8=
    $aop = new AopClient ();
	$aop->gatewayUrl = $config ['gatewayUrl'];
	$aop->appId = $app_id;
	$aop->rsaPrivateKeyFilePath = 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCwZYoTaRElHCDiUruPVB8dlvZHIV4HvGwJnD2yI9ZyX0btoRyfkBkfmDu+Rq36fuZ7MLPR2qSckvnw5zhN5H/HwhW++e/hxQ0BYEhCt1lcRBnqbXAtqGkiutSLPan1bDOfTvhWHiIC63LRY+sCirtYeIMX6kNQl0jf6XB9ej6qkN4d+qwNy7rkLKALqEKVOSsMMSz+/xV/IhOLaNuxJeqUdsynIDvxLgpMDFpwdsyjxiRbKROBiWaLXUMiPGC192GDWrtxaycm1YWUlgKRsBSD0euAVSYpsMpYGuhTbhsWFVLRTRbgXOHTMyysO12D/lKbQIfYthzJWK0YDgX/0r71AgMBAAECggEAVegNl6hqpiUNyss59vKj+LgllrykW6YjA4co8NHNRYQrOd1l4DvAUIfGSMOJRV56BvLQEFqoImzd9rnUzPKEXJekGY8FiXF7BPmMF5nNlkbATFAh+spy8WwFyQ4t8I1SkrpV7TLdo4hhMaK0uFcyKEMArSv0vpH+9rVsiF95l30IxxoN1jrrVPWDhkuaKPUidM6qrP16oX9amSnDsECND78KmXFGIOLdM9kqL11nB2x6EIQFd5PsZzAiZC2MefqKOqOXGTQRViJonfHH+7+viXt2rkzeNuinTv4UD1Pj5Qd9zOjbN6Rum0O/1oDIIj1eyix/DAlo7xGoJ+8a6wn1YQKBgQDe58rLuL4MsqZ8RoZ4jHm1OKTLeVBLymhyDZ1Jmh9DGiKUw/447poB8ec33Ws5ump/uqkTB7xXt0PCK+KBgk6TP8hZv1jyoZKXzoZ1dV9Bg6yUpEuvweYQkEKrzUkmFVELNh55Vgd27ZaEvFJ9Q2aBDqmszWqCCUw6D8cxnlquqQKBgQDKlgl3FLqCGqRRcSlCzIZ8NA/MRhnBoyCqUXH8zHBJIoAW3qZ2ddI7ti5LZ3B9DFlD1whjQ2J+YYaxwBc+7xLIbm+50FUP76CbE4vqE1O5t7fOTqZcgg+jgnxVaMfVbwqp49AveZKvve4fW1wCb9LRwNvK7RG8EJSuTzHM3TT5bQKBgQDHMFLkYZ5TMoHbpBoeN0m7S3bX7Px3/dP/t3LKhtNQ31XvooTW00EEY+q+3TooSBFLOr5DpzIDIF2IcjbijFMy+lhK+ta9Wuzog0FcTRpmLQhXe8m2sYqpWoB5jeTEB2B4qE/rFZluCTnsO21rSoOa6ENvi9CVi0F20QqrqFpYoQKBgHIEkZ0w0GurPPINY3CSFdrPnliU16gw/JB/OE1UbpnqzfaRJ1UiqMksls+fjyMCETdqTfE4GrYQg7OE7lBgU8z0wvLsewN2FYlSLfv3Frc+3DdD8sO7+7tGFF4pk9bfITxO2Hvvy4wXtG53h8hjUV72UX3bgZC8MEIOlnO7yDXdAoGAFaQIy8OzNkOPeMCv/N8KxzqQTnkW4+KKSo+pWyPttO9ozCPeqtsebOBhMfzyHc+xiE6upyLGY6y/XpeAKPzhY9li+BQH+rKClqG9O/hKWyA47KgERNNKcgAFnDDob5mr1i47P0b4y3fjKZAmUngrg84k5Kdl08PPjoLb/Z0gRJ8=';
	$aop->apiVersion = "1.0";
	$aop->notify_url = $config ['notify_url'];
	$result = $aop->execute ( $request, $token );
    // writeLog("response: ".var_export($result,true));
    return $result;
}
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
/**
 * POST 请求
 * @param string $url
 * @param array $param
 * @param boolean $post_file 是否文件上传
 * @return string content
 */
function http_post($url,$param,$post_file=false){
    $oCurl = curl_init();
    if(stripos($url,"https://")!==FALSE){
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
    }
    if (is_string($param) || $post_file) {
        $strPOST = $param;
    } else {
        $aPOST = array();
        foreach($param as $key=>$val){
            $aPOST[] = $key."=".urlencode($val);
        }
        $strPOST =  join("&", $aPOST);
    }
    curl_setopt($oCurl, CURLOPT_URL, $url);
    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt($oCurl, CURLOPT_POST,true);
    curl_setopt($oCurl, CURLOPT_POSTFIELDS,$strPOST);
    $sContent = curl_exec($oCurl);
    $aStatus = curl_getinfo($oCurl);
    curl_close($oCurl);
    if(intval($aStatus["http_code"])==200){
        return $sContent;
    }else{
        return false;
    }
}