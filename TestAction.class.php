<?php
/**
 * 我的会员中心
 * Enter description here ...
 * @author yaochengkai
 */
class TestAction extends WapBaseAction{
	
	public function index(){
		$companyid = 1;
		$openid1 = session('openid1');
		$openid2 = session('openid2');
		if(empty($openid1)){
			$wechatOptions = array('appid'=>'wx1c866df83d065151','appsecret'=>'3107fbffd27fd0ca544ddac58f2ad627');
			$wechat  = new Wechat($wechatOptions);
			if($_GET['code']){
				$wechatInfo = $wechat->getOauthAccessToken();
				if($wechatInfo['openid']){
					session('openid1',null);
					session('openid1',$wechatInfo['openid']);
					$this->redirect(U('Test/index',array('companyid'=>'1')));
				}
			}else{
				$info = $wechat->getOauthRedirect('http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"],'renlaifeng','snsapi_base');
				redirect($info);
			}
		}elseif(empty($openid2)){
			$wechatOptions = array('appid'=>'wxf049ea4617292e0f','appsecret'=>'193f3e7759365e842af373ae96b1480d');
			$wechat  = new Wechat($wechatOptions);
			if($_GET['code']){
				$wechatInfo = $wechat->getOauthAccessToken();
				if($wechatInfo['openid']){
					session('openid2',null);
					session('openid2',$wechatInfo['openid']);
					$this->redirect(U('Test/index',array('companyid'=>'1')));
				}
			}else{
				$info = $wechat->getOauthRedirect('http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"],'renlaifeng','snsapi_base');
				redirect($info);
			}
		}else{
			print_r($_SESSION);exit;
		}
	}
	public function test(){
		session(null);
	}
	public function wx(){
		$this->display();
	}
}
?>