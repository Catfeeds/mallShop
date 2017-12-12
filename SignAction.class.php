<?php
class SignAction extends HomeBaseAction{
	
	private $usersModel;
	
	private $companyModel;
	
	private $companyGroupModel;
	
	public function __construct(){
		parent::__construct();
		$this->usersModel = M('users');
		$this->companyModel = M('company');
		$this->companyGroupModel = M('company_group');
	}
	//登录
	public function login(){
			$usersInfoWhere['username'] = $this->_post('username');
			$userInfo = $this->usersModel->where($usersInfoWhere)->find();
			if (empty($userInfo)){
				$this->error(L('ServerBusyPrompt'));
			}
			$password=get_md5_password($this->_post('password'));
			if($password===$userInfo['password']){
				$companyInfo = $this->companyModel->where(array('id'=>$userInfo['companyid']))->find(); 
			    if (empty($companyInfo)){
					$this->error(L('ServerBusyPrompt'));
				}
	 
				if($companyInfo['viptime']< time() && $companyInfo['gid'] != 5){
					$this->error('抱歉！您的账号已到期。续费请联系您的客户经理，系统将于一周内自动删除您的历史数据。');
				}
				if($companyInfo['status']==0){
					$this->error('您的试用申请正在审核中，请耐心等待。');
				}elseif ($companyInfo['status']==2){
					$this->error('抱歉！您的试用申请未被通过，如有疑问请联系您的客户经理。');
				}
				if ($companyInfo['isclose']==1){
					$this->error('您的账号已被冻结，请联系您的客户经理。');
				} 
				//记住密码
				$username = $this->_post('username');
				$password = $this->_post('password');
				$rememberPassword = $this->_post('rememberPassword') ? $this->_post('rememberPassword') : 0 ;
				if($rememberPassword == 1){
					cookie('username',$username,time()+360000000);
					cookie('password',$password,time()+360000000);
					cookie('rememberPassword',$rememberPassword,time()+360000000);
				}
				if(!is_dir('./Uploads/'.$userInfo['companyid'].'/image')){
					check_dir('./Uploads/'.$userInfo['companyid'].'/image');//创建文件夹
				}
				$companyGroupInfo = $this->companyGroupModel->where(array('id'=>$companyInfo['gid']))->field('name')->find();
				if (empty($companyGroupInfo)){
					$this->error(L('ServerBusyPrompt'));
				}
				session(null);
				session('uid',$userInfo['id']);
				$userInfo['shopsid'] = $userInfo['shopsid'] > 0 ? $userInfo['shopsid'] : '0';
				session('shopsid',$userInfo['shopsid']);
				session('uname',$userInfo['username']);
				session('truename',$userInfo['truename']);
				session('phone',$userInfo['phone']);
				session('cid',$userInfo['companyid']);
				session('cname',$companyInfo['name']);
				session('viptime',$companyInfo['viptime']);
				session('logourl',$companyInfo['logourl']);
				session('companyPermissions',explode(',', $companyInfo['permissions']));
				if($userInfo['isboss'] == 1){
					session('permissions',explode(',', $companyInfo['permissions']));
				}else{
					session('permissions',explode(',', $userInfo['permissions']));
				} 
				session('maximgspace',$companyInfo['maximgspace']);
				session('gid',$companyInfo['gid']);
				session('gname',$companyGroupInfo['name']);
				session('wechatfollowlink',$companyInfo['wechatfollowlink']);
				$saveCompanyDate['lasttime'] = time();
				$saveCompanyDate['lastip'] = get_client_ip(0);
				if(format_time(time(),'d') == '01'){
					$saveCompanyDate['nowrequestsnum'] = 0;
				}
				$this->usersModel->where(array('id'=>$userInfo['id']))->save();
				$ajax['code']='200';
				$ajax['msg']='登录成功';
			}else{
				$ajax['code']='300';
				$ajax['msg']='登录失败';
				$this->error(L('WrongPW'));
			}
			echo json_encode($ajax);
	}

}