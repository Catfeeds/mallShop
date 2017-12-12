<?php
class WechatTicketAction extends BaseAction{
    
    private $encodingAesKey = 'CW473uKrWVWFtTTXKsheRE97kBflpDnZ73BCArJU6cw';
    private $token = 'MobiwindRE97kBflpDn';
    private $appId = 'wx9ce87b608e6ce573';
    private $appsecret = '6e39445eaa2011cbfcb38a21e13c9386';
    private $errCodeFile = 'LightpenData/logs/Cache/open_access_token/error_log.txt';
    private $componentVerifyTickeFile = 'LightpenData/logs/Cache/open_access_token/component_verify_ticke.json';
    private $componentAccessToken = '';
    private $componentAccessTokenFile = 'LightpenData/logs/Cache/open_access_token/component_access_token.json';
    private $preauthCodeFile = 'LightpenData/logs/Cache/open_access_token/pre_auth_code.json';
    private $preAuthCode = '';
    private $authorizationCode = '';
    private $authorizationCodeExpiresIn = 0;
    
    /* 【预发布】人来风SCRM－会员生意，无限价值
    AppID: wx9ce87b608e6ce573
    AppSecret: 6e39445eaa2011cbfcb38a21e13c9386
          人来风SCRM－会员生意，无限价值
    AppID: wx069a5680485fd1f2
    AppSecret: 1a1ae1bdaaa4e9d2eb43ac577e9a01c4
    
          公众号消息校验Token : MobiwindRE97kBflpDn
          公众号消息加解密Key : CW473uKrWVWFtTTXKsheRE97kBflpDnZ73BCArJU6cw
    
    */
    
    /**
     * 授权事件接收
     * 
     * 仅仅是接收 component_verify_ticket（每10分钟推送一次的安全ticket）来获取自己的接口调用凭据（component_access_token）
     * 在公众号第三方平台创建审核通过后，微信服务器会向其“授权事件接收URL”每隔10分钟定时推送component_verify_ticket。第三方平台方在收到ticket推送后也需进行解密（详细请见【消息加解密接入指引】），接收到后必须直接返回字符串success。
     * 
     * @author Lando<806728685@qq.com>
     * @since  2016-12-23
     */
    public function authorizationEvent(){
        // 第三方发送消息给公众平台
	    include_once "LightpenCms/Lib/ORG/wechat-bind/wxBizMsgCrypt.php";
	    $pc = new WXBizMsgCrypt($this->token, $this->encodingAesKey, $this->appId);
	    // 第三方收到公众号平台发送的消息
	    $msg = '';
	    $msg_sign = $_GET['msg_signature'];
	    $timeStamp = $_GET['timestamp'];
	    $nonce = $_GET['nonce'];
	    $from_xml = file_get_contents("php://input");
	    $errCode = $pc->decryptMsg($msg_sign, $timeStamp, $nonce, $from_xml, $msg);
	    if ($errCode == 0) {
	        $returnData = (array)simplexml_load_string($msg, 'SimpleXMLElement', LIBXML_NOCDATA);
	        if($returnData['AppId']==$this->appId){
	            if($returnData['InfoType'] == 'authorized'){
	                // 授权成功通知
	                $saveWechatData['authorizedstatus'] = '1';
	                $saveWechatData['bindtime'] = time();
	            }elseif ($returnData['InfoType'] == 'updateauthorized'){
	                // 更新授权
	                // 缓存 authorization_code
	                $authorizationCodeDate['authorization_code'] = $this->authorizationCode = $returnData['AuthorizationCode'];
	                $authorizationCodeDate['expire_time'] = $this->authorizationCodeExpiresIn = time() + $returnData['AuthorizationCodeExpiredTime']-10;
	                $writeReturn = writeLog(json_encode($authorizationCodeDate), 'LightpenData/logs/Cache/open_access_token/'.$returnData['AuthorizerAppid'].'authorization_code.json',FILE_USE_INCLUDE_PATH);
	                if(!$writeReturn){
	                    // authorization_code 缓存失败日志
	                    $logData['id'] = guidNow();
	                    $logData['type'] = '1'; // authorization_code 缓存失败日志
	                    $logData['log'] = format_time(time(),'ymdhis').'authorization_code 缓存失败；Function : incomeInformation(); Data:'.json_encode($authorizationCodeDate);
	                    $logData['createtime'] = time();
	                    M('log_wechat_authorization')->add($logData);
	                }else{
	                    $isEchoSuccess = 1;
	                }
	            }elseif ($returnData['InfoType'] == 'unauthorized'){
	                // 取消授权
	                $saveWechatData['authorizedstatus'] = '2';
	                $saveWechatData['unbindtime'] = time();
	            }elseif ($returnData['InfoType'] == 'component_verify_ticket'){
	                $writeReturn = writeLog(json_encode($returnData),$this->componentVerifyTickeFile,FILE_USE_INCLUDE_PATH);
	                if(!$writeReturn){
	                    // component_verify_ticket 缓存失败日志
	                    $logData['id'] = guidNow();
	                    $logData['type'] = '8'; // component_verify_ticket 缓存失败日志
	                    $logData['log'] = format_time(time(),'ymdhis').'component_verify_ticket 缓存失败；Function : authorizationEvent(); Data:'.json_encode($returnData);
	                    $logData['createtime'] = time();
	                    M('log_wechat_authorization')->add($logData);
	                }else{
	                    $isEchoSuccess = 1;
	                }
	            }
	            if($saveWechatData){
	                $wechatSave = M('wechats')->where(array('appid'=>$returnData['AuthorizerAppid']))->save($saveWechatData);
	                if(!$wechatSave){
	                    // wechats 授权状态更新失败日志
	                    $logData['id'] = guidNow();
	                    $logData['type'] = '4'; // wechats 授权状态更新失败日志
	                    $logData['log'] = format_time(time(),'ymdhis').'wechats 授权状态更新失败日志；Function : incomeInformation(); Data:'.json_encode($saveWechatData);
	                    $logData['createtime'] = time();
	                    M('log_wechat_authorization')->add($logData);
	                }else{
	                    $isEchoSuccess = 1;
	                }
	            }
	            if($isEchoSuccess){
	                echo 'success';
	            }
	        }else{
	            // 公众号消息与事件接收失败日志
	            $logData['id'] = guidNow();
	            $logData['type'] = '7'; // 公众号消息与事件接收失败日志
	            $logData['log'] = format_time(time(),'ymdhis').'公众号消息与事件接收失败日志；Function : incomeInformation(); Data:'.json_encode($returnData);
	            $logData['createtime'] = time();
	            M('log_wechat_authorization')->add($logData);
	        }
    	    $logData['id'] = guidNow();
    	    $logData['type'] = '9'; // wechats 授权状态更新失败日志
    	    $logData['log'] = format_time(time(),'ymdhis').'数据记录'.json_encode($returnData);
    	    $logData['createtime'] = time();
    	    M('log_wechat_authorization')->add($logData);
	    } else {
	        writeLog('error'.$errCode,$this->errCodeFile);
	    }
	     
	    
    }
    
    /**
     * 
     * 公众号消息与事件接收
     * 
     * @author Lando<806728685@qq.com>
     * @since  2016-12-23
     */
    public function incomeInformation(){
        // 第三方发送消息给公众平台
        include_once "LightpenCms/Lib/ORG/wechat-bind/wxBizMsgCrypt.php";
        $pc = new WXBizMsgCrypt($this->token, $this->encodingAesKey, $this->appId);
        // 第三方收到公众号平台发送的消息
        $msg = '';
        $msg_sign = $_GET['msg_signature'];
        $timeStamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];
        $from_xml = file_get_contents("php://input");
        $errCode = $pc->decryptMsg($msg_sign, $timeStamp, $nonce, $from_xml, $msg);
        if ($errCode == 0) {
            $returnData = (array)simplexml_load_string($msg, 'SimpleXMLElement', LIBXML_NOCDATA);
            
        } else {
            writeLog('error'.$errCode,$this->errCodeFile);
        }
    }
    
	/**
	 * 
	 * 第一步：获取第三方平台access_token
	 * 
	 * 第三方平台通过自己的component_appid（即在微信开放平台管理中心的第三方平台详情页中的AppID和AppSecret）和component_appsecret，以及component_verify_ticket（每10分钟推送一次的安全ticket）来获取自己的接口调用凭据（component_access_token）
	 * 
	 * @return mixed
	 * @author Lando<806728685@qq.com>
	 * @since  2015-8-27
	 */
	public function getComponentAccessToken(){
	    $getTokenReturnDate = json_decode(file_get_contents($this->componentAccessTokenFile),true);
	    if($getTokenReturnDate['expire_time'] > time()){
	        return $this->componentAccessToken = $getTokenReturnDate['component_access_token'];
	    }else{
	        $componentVerifyTickeData = json_decode(file_get_contents($this->componentVerifyTickeFile),true);
	        if($componentVerifyTickeData['ComponentVerifyTicket']){
    	        $getTokenUrl = 'https://api.weixin.qq.com/cgi-bin/component/api_component_token';
    	        $getTokenData = json_encode(array('component_appid'=>$this->appId,'component_appsecret'=>$this->appsecret,'component_verify_ticket'=>$componentVerifyTickeData['ComponentVerifyTicket']));
    	        $getTokenReturn = json_decode(http_post($getTokenUrl, $getTokenData),true);
    	        if($getTokenReturn['expires_in'] > 0){
    	            $getTokenReturnDate['component_access_token'] = $getTokenReturn['component_access_token'];
        	        $getTokenReturnDate['expire_time'] = time() + $getTokenReturn['expires_in']-10;
        	        $getTokenReturnDateWriteReturn = writeLog(json_encode($getTokenReturnDate), $this->componentAccessTokenFile,FILE_USE_INCLUDE_PATH);
        	        if(!$getTokenReturnDateWriteReturn){
        	            // component_access_token 缓存失败日志
        	            $logData['id'] = guidNow();
        	            $logData['type'] = '4'; // component_access_token 缓存失败日志
        	            $logData['log'] = format_time(time(),'ymdhis').'component_access_token 缓存失败；Function : getComponentAccessToken(); Data:'.json_encode($getTokenReturnDate);
        	            $logData['createtime'] = time();
        	            M('log_wechat_authorization')->add($logData);
        	        }
        	        return $this->componentAccessToken = $getTokenReturn['component_access_token'];
    	        }else{
    	            return false;
    	        }
	        }else{
	            return false; 
	        }
	    }
	}
	/**
	 * 
	 * 第二步：获取预授权码
	 * 
	 * 第三方平台通过自己的接口调用凭据（component_access_token）来获取用于授权流程准备的预授权码（pre_auth_code）
	 * 
	 * @author Lando<806728685@qq.com>
	 * @since  2015-8-27
	 */
	public function getPreAuthCode(){
	    $preauthCodeDate = json_decode(file_get_contents($this->preauthCodeFile),true);
	    if($preauthCodeDate['expire_time'] > time()){
	        return $this->preAuthCode = $preauthCodeDate['pre_auth_code'];
	    }else{
	        if(!$this->componentAccessToken){
	            $this->getComponentAccessToken();
	        }
	        $preauthCodeUrl = 'https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token='.$this->componentAccessToken;
	        $getPreauthCodeData = json_encode(array('component_appid'=>$this->appId));
	        $getPreauthCodeReturn = json_decode(http_post($preauthCodeUrl, $getPreauthCodeData),true);
	        if($getPreauthCodeReturn['expires_in']>0){
	            $getPreauthCodeReturnDate['pre_auth_code'] = $getPreauthCodeReturn['pre_auth_code'];
    	        $getPreauthCodeReturnDate['expire_time'] = time() + $getPreauthCodeReturn['expires_in']-10;
    	        $getPreauthCodeReturnDateWriteReturn = writeLog(json_encode($getPreauthCodeReturnDate), $this->preauthCodeFile,FILE_USE_INCLUDE_PATH);
    	        if(!$getPreauthCodeReturnDateWriteReturn){
    	            // pre_auth_code 缓存失败日志
    	            $logData['id'] = guidNow();
    	            $logData['type'] = '3'; // pre_auth_code 缓存失败日志
    	            $logData['log'] = format_time(time(),'ymdhis').'pre_auth_code 缓存失败；Function : getPreAuthCode(); Data:'.json_encode($getPreauthCodeReturnDate);
    	            $logData['createtime'] = time();
    	            M('log_wechat_authorization')->add($logData);
    	        }
    	        return $this->preAuthCode = $getPreauthCodeReturn['pre_auth_code'];
	        }else{
	            return false;
	        }
	    }
	}
	/**
	 * 
	 * 跳转微信公众平台进行绑定
	 * 
	 * @author Lando<806728685@qq.com>
	 * @since  2015-8-20
	 */
	
	public function test(){
	    if(!$this->preAuthCode){
	        $this->getPreAuthCode();
	    }
	    //$cid = $this->_get('cid');//公司id 用于重新找回公众号
	    $cid = '20004';
	    //第三步  使用授权码换取公众号的授权信息
	    $tiaozhuan = 'https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid='.$this->appId.'&pre_auth_code='.$this->preAuthCode.'&redirect_uri='.urlencode(C('site_url').'/index.php/User5/WechatTicket/bind/cid/'.$cid);
	    echo '<a href="'.$tiaozhuan.'" >绑定</a>';
	}
	/**
	 * 
	 * 获得绑定公众号
	 * 
	 * @author Lando<806728685@qq.com>
	 * @since  2015-8-20
	 */
	public function bind(){
	    $this->authorizationCode = $_GET['auth_code'] ? $_GET['auth_code'] : '' ;
	    $this->authorizationCodeExpiresIn = $_GET['expires_in'] ? $_GET['expires_in'] : 0;
	    //3、使用授权码换取公众号的授权信息
        //1.获得授权信息
	    if(!$this->componentAccessToken){
	        $this->getComponentAccessToken();
	    }
        $getApiQueryAuthUrl = 'https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token='.$this->componentAccessToken;
        $getApiQueryAuthData = json_encode(array('component_appid'=>$this->appId,'authorization_code'=>$this->authorizationCode));
        $getApiQueryAuthReturn = json_decode(http_post($getApiQueryAuthUrl, $getApiQueryAuthData),true);
        $saveWechatData['bindtime'] = $saveWechatData['updatetime'] = time();
        // 公众号授权给开发者的权限集列表，ID为1到15时分别代表：1:消息管理权限;2:用户管理权限;3:帐号服务权限;4:网页服务权限;5:微信小店权限;6:微信多客服权限;7:群发与通知权限;8:微信卡券权限;9:微信扫一扫权限;10:微信连WIFI权限;11:素材管理权限;12:微信摇周边权限;13:微信门店权限;14:微信支付权限;15:自定义菜单权限
        $saveWechatData['bindapiqueryauthinfo'] = json_encode($getApiQueryAuthReturn);
        if($getApiQueryAuthReturn['authorization_info']['expires_in'] > 0){
            // 缓存 authorization_code
            $authorizationCodeDate['authorization_code'] = $this->authorizationCode;
            $authorizationCodeDate['expire_time'] = time() + $this->authorizationCodeExpiresIn-10;
            $authorizationCodeWriteReturn = writeLog(json_encode($authorizationCodeDate), 'LightpenData/logs/Cache/open_access_token/'.$getApiQueryAuthReturn['authorization_info']['authorizer_appid'].'authorization_code.json',FILE_USE_INCLUDE_PATH);
            if(!$authorizationCodeWriteReturn){
                // authorization_code 缓存失败日志
                $logData['id'] = guidNow();
                $logData['type'] = '1'; // authorization_code 缓存失败日志
                $logData['log'] = format_time(time(),'ymdhis').'authorization_code 缓存失败；Function : bind(); Data:'.json_encode($authorizationCodeDate);
                $logData['createtime'] = time();
                M('log_wechat_authorization')->add($logData);
            }
            // 缓存 authorizer_access_token+authorizer_refresh_token
            $authorizerAccessTokenDate['authorizer_access_token'] = $getApiQueryAuthReturn['authorization_info']['authorizer_access_token'];
            $authorizerAccessTokenDate['authorizer_refresh_token'] = $getApiQueryAuthReturn['authorization_info']['authorizer_refresh_token'];
            $authorizerAccessTokenDate['expire_time'] = time() + $getApiQueryAuthReturn['authorization_info']['expires_in']-10;
            $authorizerAccessTokenDateWriteReturn = writeLog(json_encode($authorizerAccessTokenDate), 'LightpenData/logs/Cache/open_access_token/'.$getApiQueryAuthReturn['authorization_info']['authorizer_appid'].'authorizer_access_token.json',FILE_USE_INCLUDE_PATH);
            if(!$authorizerAccessTokenDateWriteReturn){
                // authorizer_access_token+authorizer_refresh_token 缓存失败日志
                $logData['id'] = guidNow();
                $logData['type'] = '2'; // authorizer_access_token+authorizer_refresh_token 缓存失败日志
                $logData['log'] = format_time(time(),'ymdhis').'authorizer_access_token+authorizer_refresh_token 缓存失败；Function : bind(); Data:'.json_encode($authorizerAccessTokenDate);
                $logData['createtime'] = time();
                M('log_wechat_authorization')->add($logData);
            }
            /* // 缓存 authorizer_refresh_token
            $authorizerRefreshTokenDate['authorizer_refresh_token'] = $getApiQueryAuthReturn['authorization_info']['authorizer_refresh_token'];
            writeLog(json_encode($authorizerRefreshTokenDate), 'LightpenData/logs/Cache/open_access_token/'.$getApiQueryAuthReturn['authorization_info']['authorizer_appid'].'authorizer_refresh_token.json',FILE_USE_INCLUDE_PATH); */
        }
        
        //2.获得公众号信息
        $getAuthorizerInfoUrl = 'https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info?component_access_token='.$this->componentAccessToken;
        $getAuthorizerInfoData = json_encode(array('component_appid'=>$this->appId,'authorizer_appid'=>$getApiQueryAuthReturn['authorization_info']['authorizer_appid']));
        $getAuthorizerInfoReturn = json_decode(http_post($getAuthorizerInfoUrl, $getAuthorizerInfoData),true);
        $saveWechatData['wxname'] = $getAuthorizerInfoReturn['authorizer_info']['nick_name'];
        $saveWechatData['headerpic'] = $getAuthorizerInfoReturn['authorizer_info']['head_img'];
        $saveWechatData['servicetypeinfo'] = $getAuthorizerInfoReturn['authorizer_info']['service_type_info'];
        $saveWechatData['verifytypeinfo'] = $getAuthorizerInfoReturn['authorizer_info']['verify_type_info'];
        //计算是否认证匹配原来的公众号类型
        if($saveWechatData['servicetypeinfo'] == '0' || $saveWechatData['servicetypeinfo'] == '1'){
            if($saveWechatData['verifytypeinfo'] =='0'){
	            $saveWechatData['wechattype'] = '2';
            }else{
                $saveWechatData['wechattype'] = '1';
            }
        }else{
            if($saveWechatData['verifytypeinfo'] =='0'){
                $saveWechatData['wechattype'] = '4';
            }else{
                $saveWechatData['wechattype'] = '3';
            }
        }                
        $saveWechatData['bindapigetauthorizerinfo'] = json_encode($getAuthorizerInfoReturn);
        $saveWechatData['wxid'] = $getAuthorizerInfoReturn['authorizer_info']['user_name'];
        $saveWechatData['principalname'] = $getAuthorizerInfoReturn['authorizer_info']['principal_name'];// 公众号主体名称
        $saveWechatData['businessinfo'] = json_encode($getAuthorizerInfoReturn['authorizer_info']['business_info']);// 用以了解以下功能的开通状况（0代表未开通，1代表已开通）：open_store:是否开通微信门店功能;open_scan:是否开通微信扫商品功能;open_pay:是否开通微信支付功能;open_card:是否开通微信卡券功能;open_shake:是否开通微信摇一摇功能
        $saveWechatData['weixin'] = $getAuthorizerInfoReturn['authorizer_info']['alias'];
        $saveWechatData['qrcodeurl'] = $getAuthorizerInfoReturn['authorizer_info']['qrcode_url'];
        $saveWechatData['appid'] = $getAuthorizerInfoReturn['authorization_info']['authorizer_appid'];
        
        $cid = $_GET['cid'];
        $wechatInfo = M('wechats')->where(array('companyid'=>$cid))->field('id')->find();
        if($wechatInfo){
            $wechatSave = M('wechats')->where(array('companyid'=>$cid,'id'=>$wechatInfo['id']))->save($saveWechatData);
        }else{
            $saveWechatData['companyid'] = $cid;
            $wechatSave = M('wechats')->add($saveWechatData);
        }
        if(!$wechatSave){
            // wechats 授权信息保存失败日志
            $logData['id'] = guidNow();
            $logData['type'] = '5'; // wechats 授权信息保存失败日志
            $logData['log'] = format_time(time(),'ymdhis').'wechats 授权信息保存失败；Function : bind(); Data:'.json_encode($saveWechatData);
            $logData['createtime'] = time();
            M('log_wechat_authorization')->add($logData);
        }
        
        
        //设置授权方的选项信息 地理位置上报
        $apiSetAuthorizerOptionUrl = '$https://api.weixin.qq.com/cgi-bin/component/api_set_authorizer_option?component_access_token='.$this->componentAccessToken;
        $apiSetAuthorizerOptionData = json_encode(array('component_appid'=>$this->appId,'authorizer_appid'=>$getAuthorizerInfoReturn['authorization_info']['authorizer_appid'],'option_name'=>'location_report','option_value'=>'1'));
        $apiSetAuthorizerOptionReturn = json_decode(http_post($apiSetAuthorizerOptionUrl, $apiSetAuthorizerOptionData),true);
        if($apiSetAuthorizerOptionReturn !=0){
            // 设置授权方的选项信息 地理位置上报失败日志
            $logData['id'] = guidNow();
            $logData['type'] = '6'; // 设置授权方的选项信息 地理位置上报失败日志
            $logData['log'] = format_time(time(),'ymdhis').'wechats 设置授权方的选项信息 地理位置上报；Function : bind(); Data:'.json_encode($apiSetAuthorizerOptionData).'Return:'.json_encode($apiSetAuthorizerOptionReturn);
            $logData['createtime'] = time();
            M('log_wechat_authorization')->add($logData);
        }
        /* $getApiQueryAuthReturnDate['expire_time'] = time() + $getApiQueryAuthReturn['authorization_info']['expires_in']-10;
        $getApiQueryAuthReturnDate['access_token'] = $getApiQueryAuthReturn['authorization_info']['authorizer_access_token'];
        $getApiQueryAuthReturnDate['refresh_token'] = $getApiQueryAuthReturn['authorization_info']['authorizer_refresh_token'];
        $getApiQueryAuthReturnDate['access_type'] = '3';//定义是第三方授权公众号
        writeLog($getApiQueryAuthReturnDate, 'LightpenData/logs/Cache/access_token/wechat_access_token'.$getApiQueryAuthReturn['authorization_info']['authorizer_appid'].'.json',FILE_USE_INCLUDE_PATH); */
	}
}
?>