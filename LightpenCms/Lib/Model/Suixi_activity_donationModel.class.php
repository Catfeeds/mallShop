<?php
class Suixi_activity_donationModel extends Model{
	protected $_validate =array(
		array('name','require','姓名不能为空',1),
		array('mobile','require','手机号不能为空',1),
		array('amout','require','捐助金额不能为空',1),
		array('date','require','捐助日期不能为空',1),
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
	
	public function getSuixiActivityDonationInfo($where){
		return M('Suixi_activity_donation')->where($where)->field('id,companyid,name,mobile,amout,date')->order('id DESC')->select();
	}
}