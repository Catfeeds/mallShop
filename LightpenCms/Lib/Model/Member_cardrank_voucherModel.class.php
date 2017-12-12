<?php
class Member_cardrank_voucherModel extends Model{
	protected $_validate =array(
		array('rankid','require','会员等级不能为空',1),
		array('voucherid','require','优惠标题不能为空',1),
		array('prefix','require','前缀不能为空',1),
		array('num','require','赠券数量不能为空',1),
		array('num','number','赠券数量必须为整数',1),
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
}