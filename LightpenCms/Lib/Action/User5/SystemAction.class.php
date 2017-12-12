<?php
/**
 * 系统设置
 * Enter description here ...
 * @author yongfei.zhao
 *
 */
class SystemAction extends UserAction{

	private $uid;
	
	private $companyid;
	
	public $wechatsModel;
	
	public function __construct(){
		parent::__construct();
		$this->wechatsModel = D('wechats');
	 	$this->uid = session('uid');
	 	$this->companyid = session('cid');
	}
	public function Error(){
		$this->display();
	}
}
?>