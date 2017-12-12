<?php
/**
 * 系统文件
 * Enter description here ...
 * @author yaochengkai
 */
class SystemAction extends BaseAction{
	
	private $mid;
	
	private $companyid;
	
	public function __construct(){
		parent::__construct();
		$this->mid = session('mid'.session('wapcid'));
		$this->companyid = session('wapcid');
	}
	
	public function notFound(){
		//@header("http/1.1 404 not found");
		//@header("status: 404 not found");
		$this->setPageTitle(array('title'=>'出错啦！'));
		$this->companyid = $this->_get('companyid') ? $this->_get('companyid') : $this->companyid; 
		$this->assign('companyid',$this->companyid);
		$this->display();
	}
	public function notOauth(){
		//@header("http/1.1 404 not found");
		//@header("status: 404 not found");
		$this->setPageTitle(array('title'=>'获取微信网页授权失败'));
		$companyid = $this->_get('companyid');
		$companyid =  $companyid ? $companyid : $this->companyid;
		$this->assign('companyid',$companyid);
		$this->display();
	}
}
?>