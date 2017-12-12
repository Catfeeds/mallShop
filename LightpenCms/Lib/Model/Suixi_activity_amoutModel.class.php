<?php
class Suixi_activity_amoutModel extends Model{
	protected $_validate =array(
		array('amout','require','捐助金额',1),
	);
	protected $_auto = array (
		array('adduid','getuid',self::MODEL_INSERT,'callback'),
		array('edituid','getuid',self::MODEL_BOTH,'callback'),
		array('companyid','getcompanyid',self::MODEL_INSERT,'callback'),
		array('createtime','time',self::MODEL_INSERT,'function'),
		array('updatetime','time',self::MODEL_BOTH,'function'),
	);
	public function getuid(){
		return session('uid');
	}
	public function getcompanyid(){
		return session('cid');
	}
	
	public function getSuixiActivityAmoutInfo($where){
		return M('Suixi_activity_amout')->field('id,companyid,amout,status')->where($where)->find();
	}
}