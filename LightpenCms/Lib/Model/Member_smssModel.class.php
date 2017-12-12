<?php
class Member_smssModel extends Model{
	
	protected $_validate =array(
		array('mid','require','客户id不能为空',1),
		array('info','require','SMS内容不能为空',1),
	);
	
	protected $_auto = array (
		array('companyid','getcompanyid',self::MODEL_INSERT,'callback'),
		array('createtime','time',self::MODEL_INSERT,'function'),
	);
	public function getuid(){
		return session('uid');
	}
	public function getcompanyid(){
		return session('cid');
	}
	public function getshopsid(){
		return session('shopsid');
	}
}