<?php
/**
 * 个人微信扫码验证
 * 
 * @author      Tomas<416369046@qq.com>
 * @since     2015-12-17
 * @version   1.0
 */
class UsersAction extends UserAction{
	private $companyid;
	
	
	public function __construct(){
		parent::__construct();
		$this->companyid = session('wapcid');
	}
	/**
	 * 个人微信扫码验证
	 */
	public function index(){
			$id = $_REQUEST['id'];
			$wechatsInfo = D('Wechats')->getCompanyWechatsInfo(array('companyid'=>$this->companyid,'wechattype'=>array('in','2,4')));
			echo M()->getLastSql();
			$AppID = $wechatsInfo['appid'];
			$AppSecret = $wechatsInfo['appsecret'];
			dump($wechatsInfo);
			if($wechatsInfo){
				$wechatOptions = array('token'=>$wechatsInfo['token'],'appid'=>$wechatsInfo['appid'],'appsecret'=>$wechatsInfo['appsecret']);
				$wechat  = new Wechat($wechatOptions);
				//去授权获取code
				$info = $wechat->getOauthRedirect(C('site_url').U('Wap/Users/index',array('companyid'=>$this->companyid,'id'=>$id)),'renlaifeng','snsapi_base');
				$signPackage = $wechat->getJsSign($wechatsInfo['appid']);
				$info = json_decode($info,true);
				$code = $info['code'];
				//通过code获取openid
				$tokenInfo = http_get("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$AppID."&secret=".$AppSecret."&code=".$code."&grant_type=authorization_code");
				$tokenInfo = json_decode($tokenInfo,true);
				$openid = $tokenInfo['openid'];
				//修改users表中的openid
				$data['helperopenid'] = $openid;
				$result = M('users')->where(array('companyid'=>$this->companyid,'id'=>$id))->save($data);
				$this->assign('signPackage',$signPackage);
				$this->display();
			}
	}

}
?>