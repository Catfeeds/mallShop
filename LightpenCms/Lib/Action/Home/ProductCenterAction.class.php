<?php
/**
 * 人来风新官网  产品中心
 * 5.0
 * @author asa@renlaifeng.cn
 */
class IndexNewAction extends HomeBaseAction{
	
	public function __construct(){
		parent::__construct();
		$this->usersModel = M('users');
		$this->companyModel = M('company');
		$this->companyGroupModel = M('company_group');
	}
	
	public function index(){
		$this->display();
	}
	public function register(){
		$this->display();
	}
	/**
	 *
	 * 微信登陆
	 *
	 * @author Lando<806728685@qq.com>
	 * @since  2015-8-6
	 */
	public function wechatQ(){
		$this->display();
	}
	
}