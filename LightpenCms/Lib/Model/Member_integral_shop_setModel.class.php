<?php
class Member_integral_shop_setModel extends Model{

	protected $_validate =array(
		array('password','require','兑换确认密码不能为空',1),
		array('info','require','兑换流程说明不能为空',1),
	);
	
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
	/**
	 * 获得 积分商城 兑换设置  
	 */
	public function getMemberIntegralShopSetInfo(){
		return M('member_integral_shop_set')->where(array('companyid'=>session('cid')))->find();
	}
}