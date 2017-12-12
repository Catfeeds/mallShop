<?php
/**
 * 用户前台操作
 * Enter description here ...
 * @author yongfei.zhao
 *
 */
class UsersAction extends BaseAction{

	private $usersModel;
	
	private $companyModel;
	
	private $companyGroupModel;
	
	public function __construct(){
		parent::__construct();
		$this->usersModel = M('users');
		$this->companyModel = M('company');
		$this->companyGroupModel = M('company_group');
	}
	/**
	 * 验证码
	 * Enter description here ...
	 */
	Public function verify(){
   	 	import('ORG.Util.Image');
    	Image::GBVerify(4,'png');
 	}
	/**
	 * 验证密码
	 * Enter description here ...
	 */
	public function checkpwd(){

		$where['username']=$this->_post('username');
		$where['email']=$this->_post('email');
		$db=D('Users');
		$list=$db->where($where)->find();
		if($list==false) $this->error(L('IncorEmailOrId'),U('Index/regpwd'));
		
		$smtpserver = C('email_server'); 
		$port = C('email_port');
		$smtpuser = C('email_user');
		$smtppwd = C('email_pwd');
		$mailtype = "TXT";
		$sender = C('email_user');
		$smtp = new Smtp($smtpserver,$port,true,$smtpuser,$smtppwd,$sender); 
		$to = $list['email']; 
		$subject = C('pwd_email_title');
		$code = C('site_url').U('Index/resetpwd',array('uid'=>$list['id'],'code'=>md5($list['id'].$list['password'].$list['email']),'resettime'=>time()));
		$fetchcontent = C('pwd_email_content');
		$fetchcontent = str_replace('{username}',$where['username'],$fetchcontent);
		$fetchcontent = str_replace('{time}',date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']),$fetchcontent);
		$fetchcontent = str_replace('{code}',$code,$fetchcontent);
		$body=$fetchcontent;
		//$body = iconv('UTF-8','gb2312',$fetchcontent);
		$send=$smtp->sendmail($to,$sender,$subject,$body,$mailtype);
		$this->success(L('VisitEmail').$list['email'].L('LoginAfterEmailValidation'));
	}
	/**
	 * 重置密码
	 * Enter description here ...
	 */
	public function resetpwd(){
		$where['id']=session('uid');
		$where['password']=get_md5_password($this->_post('password'));
		if(M('Users')->save($where)){
			$this->success(L('OperationSuccessful'),U('Index/login'));
		}else{
			$this->error(L('ServerBusyPrompt'),U('Index/index'));
		}
	}
	/**
	 * ajax 检查 username  修改基本信息
	 */
	public function ajaxRegCheckUsername(){
		$where = " username = '".$this->_get('username')."'";
		$id = $this->_get('id');
		if ($id){
			$where .= " AND id != '".$id."' ";
		} 
		$count = $this->usersModel->where($where)->count();
		if ($count){
			echo 'false';
		}else{
			echo 'true';
		}
	}
	/**
	 * ajax 检查 email  修改基本信息
	 */
	public function ajaxCheckEmail(){
		$where = " email = '".$this->_get('email')."'";
		$id = $this->_get('id');
		if ($id){
			$where .= " AND id != '".$id."' ";
		}
		$count = $this->usersModel->where($where)->count();
		if ($count){
			echo 'false';
		}else{
			echo 'true';
		}
	}
	/**
	 * 检查 phone  修改基本信息
	 */ 
	public function ajaxRegCheckPhone(){
		$where = " applymobile = '".$this->_get('phone')."'";
		$id = $this->_get('id');
		if ($id){
			$where .= " AND id != '".$id."' ";
		}
		$count = $this->usersModel->where($where)->count();
		if ($count){
			echo 'false';
		}else{
			echo 'true';
		}
	}
	/**
	 * 检查 密码是否正确
	 * Enter description here ...
	 */
	public function ajaxRegCheckOldPassword(){
		$password = get_md5_password($this->_get('old_password'));
		$userInfo = $this->usersModel->where(array('id'=>$this->uid))->find();
		if ($userInfo['password'] == $password){
			echo 'true';
		}else{
			echo 'false';
		}
	}
}