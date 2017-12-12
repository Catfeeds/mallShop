<?php
/**
 * 人来风新官网
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
	
}