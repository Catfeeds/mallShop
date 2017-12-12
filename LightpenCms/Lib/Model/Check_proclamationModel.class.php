<?php
class Check_proclamationModel extends Model{
	protected $_auto = array (
		array('createtime','time',self::MODEL_INSERT,'function'),
		array('updatetime','time',self::MODEL_BOTH,'function'),
	);
	/**
	 * 获得公告 信息
	 * @param unknown $companyid
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getAllInfo(){
		return M('check_proclamation')->order('sort asc,createtime desc')->select();
	}
	
	public function getInfo($proid){
		return M('check_proclamation')->where(array('id'=>$proid))->find();
	}
	
}
?>