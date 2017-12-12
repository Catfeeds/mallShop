<?php
class System_permissions_listModel extends Model{
	protected $_auto = array (
		array('createtime','time',self::MODEL_INSERT,'function'),
		array('updatetime','time',self::MODEL_BOTH,'function'),
	);
	/**
	 * 获得 条件列表
	 * @param number $companyid
	 * @return Ambigous <mixed, boolean, NULL, string, unknown, multitype:, multitype:multitype: , void>
	 */
	public function getSystemPermissionsList($where){
		return M('system_permissions_list')->where($where)->field('id,name,desc,parentid')->order('sort ASC,id ASC')->select();
	}
	/**
	 * 获得 条件详情
	 * @param number $companyid
	 * @return Ambigous <mixed, boolean, NULL, string, unknown, multitype:, multitype:multitype: , void>
	 */
	public function getSystemPermissionsInfo($where){
		return M('system_permissions_list')->where($where)->field('id,name,desc,parentid')->find();
	}
	
	
	
}