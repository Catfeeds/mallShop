<?php
class Member_shop_addressModel extends Model{

	protected $_validate =array(
		/* array('name','require','会员等级名称不能为空',1),
		array('name','1,16','会员等级名称长度设置错误',1,'length',3),
		array('beginscore','require','积分区间不能为空',1),
		array('beginscore','number','积分区间必须为整数',1),
		array('endscore','require','积分区间不能为空',1),
		array('endscore','number','积分区间必须为整数',1), */
	);
	
	protected $_auto = array(
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
	 * 获得地址列表
	 * @param unknown $where
	 */
	public function getMemberShopAddressList($where){
		return M('member_shop_address')->where($where)->field('id,name,mobile,country,province,city,address,isdefault')->order('id DESC')->select();
	}
	/**
	 * 获得地址单条记录详情
	 * @param unknown $where
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getMemberShopAddressInfo($where){
		return M('member_shop_address')->where($where)->field('id,name,mobile,country,province,city,address,isdefault')->find();
	}
}