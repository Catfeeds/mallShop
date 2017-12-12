<?php
class Member_privilege_templateModel extends Model{

	protected $_validate =array(
		array('name','require','私享特权名称不能为空',1),
		array('info','require','特权使用说明不能为空',1),
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
	 * 获得模板详情
	 */
	public function getMemberPrivilegeTemplateInfo($id){
		return M('member_privilege_template')->where(array('id'=>$id,'companyid'=>session('cid')))->field('id,name,info')->find();
	}
	
}