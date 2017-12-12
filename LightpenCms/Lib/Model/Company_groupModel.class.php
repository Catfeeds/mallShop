<?php
class Company_groupModel extends Model{
	protected $_auto = array (
		array('createtime','time',self::MODEL_INSERT,'function'),
		array('updatetime','time',self::MODEL_BOTH,'function'),
	);
	/**
	 * 详情
	 */
	public function getCompanyGroupInfo($where){
		return M('Company_group')->where($where)->field('id,name,permissions')->find();
	}
	/**
	 * 列表
	 */
	public function getCompanyGroupList($where){
		return M('Company_group')->where($where)->field('id,name,permissions')->order('sort ASC,id DESC')->select();
	}
}
?>