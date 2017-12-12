<?php
class Member_rank_privilegeModel extends Model{

	protected $_validate =array(
		array('name','require','会员等级名称不能为空',1),
		array('useendtime','require','有效期不能为空',1),
		array('info','require','特权使用说明不能为空',1),
		array('usepassword','require','密码不能为空',1),
		array('usepassword','1,4','密码长度设置错误',1,'length',3),
		array('getmessage','require','获得新特权系统通知不能为空',1),
		array('maturitymessage','require','特权过期系统通知不能为空',1),
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
	 * 获得 等级特权
	 */
	public function getRankPrivileges($rankid){
		return M('member_rank_privilege')
				->table('tp_member_rank_privilege AS privilege')
				->join('LEFT JOIN tp_member_rank_privilege_link AS link On link.privilegeid=privilege.id')
				->where(array('link.rankid'=>$rankid,'privilege.useendtime'=>array('gt',time())))
				->field('id,name,useendtime')
				->order('privilege.useendtime ASC')
				->select();
	}
	/**
	 * 获得 等级特权 详细信息
	 */
	public function getMemberRankPrivilegeInfo($privilegeid){
		return M('member_rank_privilege')->where(array('id'=>$privilegeid,'companyid'=>session('cid')))->field('id,name')->find();
	}
}