<?php
class Member_everyday_checkin_setModel extends Model{
	protected $_validate =array(
		array('days','require','连续签到天数不能为空',1),
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
	 * 获得设置信息
	 * @param unknown $where
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getMemberEverydayCheckinSetInfo($where){
		return M('member_everyday_checkin_set')->where($where)->field('id,activitiesid,days,rewardtype,expnum,intnum,vouchertype,vouchersid,prefix,rewardlimit')->find();
	}
	/**
	 * 获得设置信息
	 * @param unknown $where
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getMemberEverydayCheckinSetList($where){
		return M('member_everyday_checkin_set')->where($where)->field('id,activitiesid,days,rewardtype,expnum,intnum,vouchertype,vouchersid,prefix,rewardlimit')->select();
	}
}