<?php
class Company_pay_alipayModel extends Model{
	protected $_validate =array(
		array('isshow','require','是否启用不能为空',1),
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
	public function getCompanyPayAlipayInfo($where){
		return M('company_pay_alipay')->where($where)->field('id,account,username,isshow,pid,appid')->find();
	}
}