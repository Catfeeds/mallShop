<?php
class Member_applyModel extends Model{
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
	 * 会员申请数
	 * @param unknown $memberApplyCountWhere
	 * @return Ambigous <number, unknown>
	 */
	public function getMemberApplyCount($memberApplyCountWhere){
		$count = M('member_apply')->where($memberApplyCountWhere)->count();
		return $count > 0 ? $count : 0 ;
	}
}