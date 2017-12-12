<?php
class Company_shops_dining_tablesModel extends Model{
   
	protected $_auto = array (
		array('adduid','getuid',self::MODEL_INSERT,'callback'),
		array('edituid','getuid',self::MODEL_BOTH,'callback'),
		array('companyid','getcompanyid',self::MODEL_INSERT,'callback'),
		array('createtime','time',self::MODEL_INSERT,'function'),
		array('updatetime','time',self::MODEL_BOTH,'function'),
	);
	public function getcompanyid(){
		return session('cid');
	}
	public function getuid(){
		return session('uid');
	}
	/**
	 * 获得条件下的 信息
	 */
	public function getWhereCompanyShopsDiningTablesList($where){
		return M('company_shops_dining_tables')->where($where)->field('id,companyid,name')->order('sort ASC,id DESC')->select();
	}
	/**
	 * 获得条件下的 信息
	 */
	public function getWhereCompanyShopsDiningTablesInfo($where){
		return M('company_shops_dining_tables')->where($where)->field('id,companyid,name')->find();
	}
}
?>