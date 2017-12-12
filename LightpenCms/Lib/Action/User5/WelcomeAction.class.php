<?php
/**
 * 欢迎页
 * 
 * @author    Mark<1311013341@qq.com>
 * @since     2016-9-20
 * @version   1.0
 */
class WelcomeAction extends UserAction{
	
	private $companyid;
	
	public function __construct(){
		parent::__construct();
		$this->companyid = session('cid');
		$this->uid = session('uid');
	}
	/**
	 * 首页(non-PHPdoc)
	 * @see UserAction::index();
	 */
	public function index(){
		$companyInfo = M('company')->where(array('id'=>session('cid')))->find();
		if ($companyInfo['status']==4){
			$this->redirect(U('EnterPlatform/enterOne'));
		}
		if($this->_get('ispermissions')){
			
		}else{
			$this->assign('commonheader','login');
		}
		$loginInfo = M('users')->where(array('id'=>$this->uid))->field('loginnum,username')->find();
		
		$info['myInt'] = json_decode($this->integralJK('getUserCanUseIntegral', $loginInfo['username']), true);// 获取可用积分
		$info['myCuInt'] = json_decode($this->integralJK('getUserCumulativeIntegral', $loginInfo['username']), true);// 获取可用积分
		$this->assign('loginInfo',$loginInfo);
		$this->assign('info',$info);
		$this->display();
	}
}
?>