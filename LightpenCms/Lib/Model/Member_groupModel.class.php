<?php
class Member_groupModel extends Model{

	protected $_validate =array(
		array('name','require','分组名称不能为空',1),
		array('info','require','分组描述不能为空',0),
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
	 * 获得会员分组详情
	 * @param unknown $groupid
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getMemberGroupInfo($groupid){
		return M('member_group')->where(array('id'=>$groupid,'companyid'=>session('cid')))->field('id,name,info,membernum,optioninfo,createtime')->find();
	}
	/**
	 * 获得条件下的 会员分组详情
	 * @param unknown $groupid
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getWhereMemberGroupInfo($where){
		return M('member_group')->where($where)->field('id,name,info,membernum,optioninfo,createtime')->find();
	}
	/**
	 * 获得条件下的 会员分组详情列表
	 * @param unknown $groupid
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getWhereMemberGroupList($where){
		return M('member_group')->where($where)->field('id,name,info,membernum,optioninfo,createtime')->order('id DESC')->select();
	}
	/**
	 * 获得个人的所有标签
	 * @param unknown $where
	 */
	public function getMemberTagesList($where){
		return M()->table('tp_member_group_link as link')->join(array('tp_member_group as groups on groups.id=link.groupid'))->where($where)->field('link.mid,link.groupid,groups.name')->select();
	}
	
}