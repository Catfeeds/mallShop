<?php
class Member_marketing_activities_privilegeModel extends Model{
	protected $_validate =array(
		array('sendparameter','require','投放参数不能为空',2),
		array('sendtime','require','投放条件不能为空',2),
		array('name','require','特权名称不能为空',1),
		array('starttime','require','开始时间必须选择',0),
		array('endtime','require','截至时间必须选择',0),
		array('info','require','特权详情必须填写',1),
		array('groupid','require','投放目标必须选择',1),
		array('usepassword','require','使用确认密码不能为空',1),
		array('usepassword','1,4','使用确认密码长度设置错误',1,'length',3),
		array('getmessage','require','特权获得通知不能为空',1),
		array('maturitymessage','require','特权到期通知不能为空',1),
		array('expiredmessage','require','特权过期通知不能为空',1),
		array('issendemail','require','电子邮箱同步推送必须选择',1),
		array('issendsms','require','手机短信同步推送必须选择',1),
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
	 * 获得 条件下的 私享特权
	 * @param unknown $where
	 * @return Ambigous <mixed, boolean, NULL, string, unknown, multitype:, multitype:multitype: , void>
	 */
	public function getMemberMarketingActivitiesPrivilege($where){
		return M('member_marketing_activities_privilege')->where($where)->field('id,type,sendparameter,sendtime,useendtime,name,useshopsid,starttime,endtime,groupid,getmessage,maturitymessage,expiredmessage,issendemail,issendsms')->select();
	}
}