<?php
/**
 * 
 * 微信授权 外部调用接口
 * @author    Lando<806728685@qq.com>
 * @since     2016-2-23
 * @version   1.0
 */
class AutoWechatOauth2Action extends BaseAction{

	public function __construct(){
		parent::__construct();
		ignore_user_abort();
		set_time_limit(0);
	}
	/**
	 * 
	 * 微信资料外部获取 接口 
	 * 
	 * @author Lando<806728685@qq.com>
	 * @since  2016-2-23
	 * 
	 * header("Content-Type:text/html;charset=utf-8");
     * $openid = $_GET['openid'] ? base64_decode($_GET['openid']) : '';
     * if($openid){
     *   //接口返回的数据需要通过$_GET接收，$_GET接收后需要通过base64_decode转义
     *   //Array ( [openid] => bzhBbjBqc0hIR2lMVnA5ZXl5Nndyc2pUQ3Y0RQ== [nickname] => TGFuZG8= [sex] => MQ== [province] => 5LiK5rW3 [city] => 6Jm55Y j [country] => 5Lit5Zu9 [headimgurl] => aHR0cDovL3d4LnFsb2dvLmNuL21tb3Blbi9pYktIUDFUWlplWEozV0VuMUMwQ29GTkNqZ25OWlRjejhidlBnUzdQekVwR2tzVWt1VUoxRnl2TUJ5T2lhUkQwYUlJRE9iSDd1WmY3bWUwNFpheG9pYjNuUUJoUHV6ZlQ2NVUvMA== [unionid] => )
     *   print_r($_GET);
     * }else{
     * 	 $scope = 'snsapi_userinfo';//类型分为：snsapi_base（可以获得openid具体含义同微信官方一致）；snsapi_userinfo（可以获得openid,nickname,sex,province,city,country,headimgurl,unionid具体含义同微信官方一致）
     * 	 $location = base64_encode('http://activity.mobiwind.cn/Wechat/test.php?');//目标地址，需要接收接口返回数据的地址
     * 	 $appid = 'wxd1a369ecf574d300';//公众号appid
     * 	 $callback = 'http://www.mobiwind.cn/index.php?g=User&m=AutoWechatOauth2&a=getInfo&appid='.$appid.'&scope='.$scope.'&location='. $location;//相同公众号在多个活动中公用接口地址
     * 	 header('location: https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.urlencode($callback).'&response_type=code&scope='.$scope.'&state=TRE#wechat_redirect');
     * 	 exit();
     * }
	 * 
	 */
	public function getInfo(){
	    $appid = $this->_get('appid');
	    $wechatInfo = M('wechats')->where(array('appid'=>$appid))->field('token,appid,appsecret,encodingaeskey')->find();
	    if($wechatInfo&&$appid){
	        $app_id = $wechatInfo['appid'];
	        $app_secret = $wechatInfo['appsecret'];
	        $scope  = $_GET['scope'];
	        if ($_GET['code']) {
	            $code = $_GET['code'];
	            $location = $_GET['location'] ? base64_decode($_GET['location']) : '';
	            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $app_id . "&secret=" . $app_secret . "&code=" . $code . "&grant_type=authorization_code";
	            $data = json_decode(file_get_contents($url), true);
	            if($data){
	                $accessToken = $data['access_token'];
	                $openid = $data['openid'];
	                if($scope == 'snsapi_userinfo'){
	                    $url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$accessToken."&openid=".$openid."&lang=zh_CN";
	                    $infoData = json_decode(file_get_contents($url), true);
	                    if($location&&$infoData){
	                        header('location:'. $location.'&openid='.base64_encode($openid).'&nickname='.base64_encode($infoData['nickname']).'&sex='.base64_encode($infoData['sex']).'&province='.base64_encode($infoData['province']).'&city='.base64_encode($infoData['city']).'&country='.base64_encode($infoData['country']).'&headimgurl='.base64_encode($infoData['headimgurl']).'&unionid='.base64_encode($infoData['unionid']));
	                    }
	                }else{
	                    if($location&&$data){
	                        header('location:'. $location.'&openid='.base64_encode($openid));
	                    }
	                }
	            }
	        }
	    }
	}
	/**
	 * 
	 * 获取公用JsApiTicket
	 * 
	 * @author Lando<806728685@qq.com>
	 * @since  2016-2-23
	 * 
	 * appid 为参数
	 */
	public function getWechatTicket(){
	    $returnData['code'] = 0;
	    $returnData['JsApi_Ticket'] = '';
	    $returnData['msg'] = 'JsApiTicket获取失败';
	    $appid = $this->_get('appid');
	    $wechatInfo = M('wechats')->where(array('appid'=>$appid))->field('token,appid,appsecret,encodingaeskey')->find();
	    if($wechatInfo&&$appid){
	        $app_id = $wechatInfo['appid'];
	        $app_secret = $wechatInfo['appsecret'];
	        $wechatOptions = array('token'=>$wechatInfo['token'],'appid'=>$wechatInfo['appid'],'appsecret'=>$wechatInfo['appsecret']);
	        $wechat  = new Wechat($wechatOptions);
	        $JsApiTicket = $wechat->getJsTicket();
	        $returnData['code'] = 1;
	        $returnData['JsApi_Ticket'] = $JsApiTicket;
	        $returnData['msg'] = 'JsApiTicket获取成功';
	    }
	    echo json_encode($returnData);
	}
	/**
	 *
	 * 获取公用 Token
	 *
	 * @author Lando<806728685@qq.com>
	 * @since  2016-2-23
	 * 
	 * appid 为参数
	 * 
	 */
	public function getWechatToken(){
	    
	    $returnData['code'] = 0;
        $returnData['access_token'] = '';
        $returnData['msg'] = 'access_token获取失败';
        $appid = $this->_get('appid');
	    $wechatInfo = M('wechats')->where(array('appid'=>$appid))->field('token,appid,appsecret,encodingaeskey')->find();
        if($wechatInfo){
            $wechatOptions = array('token' => $wechatInfo['token'], 'appid' => $wechatInfo['appid'], 'appsecret' => $wechatInfo['appsecret'],'encodingaeskey' => $wechatInfo['encodingaeskey']);
            $wechatPublic = new Wechat($wechatOptions);
            $access_token = $wechatPublic->checkAuth();
            $returnData['code'] = 1;
            $returnData['access_token'] = $access_token;
            $returnData['msg'] = 'access_token获取成功';
	    }
	    echo json_encode($returnData);
	}
	/**
	 *
	 * 获取公用 Token CacheData
	 *
	 * @author Lando<806728685@qq.com>
	 * @since  2016-2-23
	 *
	 * appid 为参数
	 *
	 */
	public function getWechatTokenCacheData(){
	    $returnData['code'] = 0;
	    $returnData['access_token'] = '';
	    $returnData['msg'] = 'access_token获取失败';
	    $appid = $this->_get('appid');
	    $wechatInfo = M('wechats')->where(array('appid'=>$appid))->field('token,appid,appsecret,encodingaeskey')->find();
	    if($wechatInfo){
	        $wechatOptions = array('token' => $wechatInfo['token'], 'appid' => $wechatInfo['appid'], 'appsecret' => $wechatInfo['appsecret'],'encodingaeskey' => $wechatInfo['encodingaeskey']);
	        $wechatPublic = new Wechat($wechatOptions);
	        $access_token = $wechatPublic->checkAuth();
	        $returnData['code'] = 1;
	        $authname = 'wechat_access_token'.$appid;
	        $returnData['data'] = json_decode(file_get_contents('./LightpenData/logs/Cache/access_token/'.$authname.'.json'),true);
	        $returnData['access_token'] = $access_token;
	        $returnData['msg'] = 'access_token获取成功';
	    }
	    echo json_encode($returnData);
	}
}
