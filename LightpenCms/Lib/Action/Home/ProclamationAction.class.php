<?php
/**
 * 公告管理
 *
 * @author    Asa<asa@renlaifeng.cn>
 * @since     2016-0329
 * @version   1.0
 */
class ProclamationAction extends HCmsBaseAction{
	
	public function __construct(){
		parent::__construct();
		$this->pro=M("check_proclamation");
		$this->proD=D("Check_proclamation");
	}
	public function style(){
		$this->display();
	}
	public function index(){
		$count=$this->pro->count();
		$page = new Page($count,15);
		$this->list=$list=$this->pro->limit($page->firstRow.','.$page->listRows)->order('isstick desc,sort asc,createtime desc')->select();
		$this->assign('page',$page->show());
		//dump($list);
		$this->display();
	}
	public function info(){
		$this -> info = $info = $this->pro->where(array('id'=>$this->_get('id')))->find();
		$this->display();
	}	
	
}