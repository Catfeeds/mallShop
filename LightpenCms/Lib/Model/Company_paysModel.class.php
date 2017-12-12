<?php
class Company_paysModel extends Model{
    protected $_validate =array(
		array('name','require','名称不能为空',1),
	);
	protected $_auto = array (
		array('adduid','getuid',self::MODEL_INSERT,'callback'),
		array('edituid','getuid',self::MODEL_BOTH,'callback'),
		array('companyid','getcompanyid',self::MODEL_INSERT,'callback'),
		array('createtime','time',self::MODEL_INSERT,'function'),
		array('updatetime','time',self::MODEL_BOTH,'function'),
	);
	public function getcompanyid(){
		return session('cid');
	}
	public function getuid(){
		return session('uid');
	}
}
?>