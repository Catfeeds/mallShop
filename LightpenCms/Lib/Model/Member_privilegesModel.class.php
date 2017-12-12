<?php
class Member_privilegesModel extends Model{

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
	 * 获得 会员私享特权
	 */
	public function getMemberPrivilegesList($memberPrivilegesWhere){
		return M('member_privileges')->table('tp_member_privileges as privileges')
		->join(array('LEFT JOIN tp_member_marketing_activities_privilege AS privilege ON privilege.id = privileges.privilegeid'))
		->where($memberPrivilegesWhere)
		->field('privileges.id,privilege.name AS privilegename,privilege.useendtime')
		->order('usetime DESC')->select();
	}	
}