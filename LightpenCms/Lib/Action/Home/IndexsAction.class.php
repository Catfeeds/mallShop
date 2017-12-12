<?php
/**
 * 
 * 
 * @author    Mark<1311013341@qq.com>
 * @since     2015-10-9
 * @version   1.0
 */
class IndexsAction extends HomeBaseAction{
	
	private $usersModel;
	
	private $companyModel;
	
	private $companyGroupModel;
	
	private $logModel;
	
	public function __construct(){
		parent::__construct();
		$this->usersModel = M('users');
		$this->companyModel = M('company');
		$this->companyGroupModel = M('company_group');
		$this->logModel = M('login_log');
	}
	//登录
	public function login(){
		if(IS_POST){  // 如果点击登录提交
			$data['createtime'] = time();
			//判断用户名和邮箱
			$usersInfoWhere['username'] = $this->_post('username');
			if(strstr($usersInfoWhere['username'],'@') == null){
				$userInfo = $this->usersModel->where($usersInfoWhere)->find();
			}
			$userInfo = $this->usersModel->where(array('email'=>$usersInfoWhere['username']))->find();
			if(empty($userInfo)){
				$data['loginError'] = '1';  //用户不存在
				$errorLog = $this->logModel->add($data);
			}
			$password=get_md5_password($this->_post('password'));
			if($password != $userInfo['password']){
				$data['loginError'] = '2';  //密码不正确
				$errorLog = $this->logModel->add($data);
			}
			if($password===$userInfo['password']){
				$data['loginError'] = '0';  //登录成功
				$errorLog = $this->logModel->add($data);
				$companyInfo = $this->companyModel->where(array('id'=>$userInfo['companyid']))->find();
				if(empty($companyInfo)){
					$this->error(L('ServerBusyPrompt'));
				}
				$this->success(L('LoginSuccessful'),U('User/System/systemInfo'));
			}else{
				$this->error(L('WrongPW'));
			} 
		}else{
			$this->setPageSeo(array('title'=>'企业登录-人来风-MobiWind','keywords'=>'企业登录人来风，企业登录MobiWind，人来风，MobiWind，人来风-MobiWind','description'=>'企业登录-人来风-MobiWind'));  // 映射页面
			//1:加二维码扫描;2检测是否生成验证码
			$data['loginip'] = get_real_ip();
			$loginNum = $this->logModel->where(array('loginip'=>$data['loginip'],'createtime'=>array('gt',strtotime('-1 day'))))->count();
			if($loginNum > '2'){
				$loginStatus = '1';  //不显示验证码
			}
			$loginStatus = '0';   //显示验证码
			$this->display();
		}
	}
	//注册
	public function register(){
		if(IS_POST){
			$model = new Model();
			$model->startTrans();//开启事务
			$companyGroupInfo = $this->companyGroupModel->where(array('id'=>1))->field('id,permissions,maximgspace,maxrequestsnum')->find();
			if (empty($companyGroupInfo)){
				$this->error(L('ServerBusyPrompt'),U('Index/register'));
			}
			$viptime = time()+604800;//注册一周试用期
			$companyInfoData['viptime'] = $viptime;
			$companyInfoData['permissions'] = $companyGroupInfo['permissions'];
			$companyInfoData['gid'] = $companyGroupInfo['id'];
			$companyInfoData['maximgspace'] = $companyGroupInfo['maximgspace'];
			$companyInfoData['maxrequestsnum'] = $companyGroupInfo['maxrequestsnum'];
			$companyInfoData['wechatnum'] = 1;
			$companyInfoData['workernum'] = 0;
			$companyInfoData['shopsnum'] = 1;
			$companyInfoData['status'] = 0;
			$companyInfoData['isclose'] = 0;
			$companyInfoData['updatetime'] = $companyInfoData['createtime'] = time();
			$companyInfoInsterReturn = $this->companyModel->add($companyInfoData);
			
			$_POST['companyid'] = $companyInfoInsterReturn;
			//$_POST['password'] = get_md5_password($this->_post('repeat_password'));
			//$_POST['truePassword'] = $this->_post('repeat_password','trim');
			$_POST['updatetime'] = $_POST['createtime'] = time();
			$_POST['createip'] = get_client_ip(0);
			$_POST['applyname'] = $this->_post('truename');
			$_POST['applymobile'] = $this->_post('phone');
			//$_POST['applyemail'] = $this->_post('email');
			$_POST['isboss'] = 1;
			$usersInsterReturn = $this->usersModel->add($_POST);
			
			$mallHomeData['companyid'] = $companyInfoInsterReturn;
			$mallHomeData['tplid'] = 1;
			$mallHomeData['updatetime'] = $mallHomeData['createtime'] = time();
			$mallHomeReturn = M('mall_home')->add($mallHomeData);
			
			if($companyInfoInsterReturn && $usersInsterReturn && $mallHomeReturn){
				$model->commit();//事务提交
				check_dir('./Uploads/'.$companyInfoInsterReturn.'/image');//创建文件夹
				$this->redirect(U('Index/registerOk'));//提示 审核
				//$this->redirect(U('Index/index'));//提示 审核
			}else{
				$model->rollback();//事务回滚
				$this->error(L('ServerBusyPrompt'),U('Index/register'));
			}
		}else{
			/* if(session('uid')){
				$this->redirect(U('Index/index'));
			} */
			$this->setPageSeo(array('title'=>'免费开通人来风账号','keywords'=>'免费开通,人来风,mobiwind','description'=>'立即注册人来风账号-免费开通，我们的AM将在24小时内与您确认注册信息'));
			$this->display();
		}
	}
	//注册成功
	public function registerOk(){
		$this->display();
	}
	//找回密码
	public function forgotPassword(){
		if(IS_POST){
			$usersWhere['email'] = $this->_post('email','trim');
			$usersCount = $this->usersModel->where($usersWhere)->field('id,truePassword')->find();
			if($usersCount){
				$mailer = new Mailer();
				$toList = array($usersWhere['email']);
				$subject = '人来风：您的登录密码';
				$content = '<div class="" id="qm_con_body"><div id="mailContentContainer" class="qmbox qm_con_body_content" style="">
	<div class="wrapper" style="margin: 20px auto 0; width: 500px; padding:15px auto 10px">
	<div class="header clearfix">
	<a href="'.C('site_url').'" class="logo" style="float:left" target="_blank">
	<img src="'.C('site_url').'/Tpl/Home/default/common/images/logo.png" width="150"></a>
	</div>
	<br style="clear:both; height:0"><div class="content" style="background: none repeat scroll 0 0 #FFFFFF; border: 1px solid #E9E9E9; margin: 10px 0 0; padding: 30px;">
	<p> '.$usersWhere['email'].'，你好</p>
	<p>您的登录密码为：'.$usersCount['truePassword'].'</p>
	<p class="footer" style="border-top: 1px solid #DDDDDD; padding-top:6px; margin-top:25px; color:#838383;">© 2014 人来风&nbsp;&nbsp;|&nbsp;&nbsp;该邮件由系统发送，请勿回复</p>
	</div>
	</div>
	  </div></div>';
				$sendReturn = $mailer->sendMail($toList, $subject, $content);
				if ($sendReturn){
					$this->redirect(U('Index/forgotPasswordOk'));
				}else{
					$this->error(L('ServerBusyPrompt'));
				}
			}else{
				$this->error('邮箱不存在');
			}
		}else{
			$this->display();
		}
	}
	//找回密码
	public function forgotPasswordOk(){
		$this->display();
	}
	//退出
	public function logOut(){
		session(null);
		$this->success('退出成功',U('Index/index'));
	}
}