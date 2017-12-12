<?php
class Member_delivery_voucher_setModel extends Model{

	
	protected $_auto = array (
			array('adduid','getuid',self::MODEL_INSERT,'callback'),
			array('edituid','getuid',self::MODEL_BOTH,'callback'),
			array('companyid','getcompanyid',self::MODEL_INSERT,'callback'),
			array('shopsid','getshopsid',self::MODEL_INSERT,'callback'),
			array('createtime','time',self::MODEL_INSERT,'function'),
			array('updatetime','time',self::MODEL_BOTH,'function'),
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
	public function getmemberdeliveryvouchersetinfo($Where){
		return M('member_delivery_voucher_set')->field('id,companyid,info,createtime')->where($Where)->find();
	}
}
?>