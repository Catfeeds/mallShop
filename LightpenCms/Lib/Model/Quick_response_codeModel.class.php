<?php
class Quick_response_codeModel extends Model{

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
	 * 获得指定条件的 二维码数据组
	 * @param unknown $where
	 */
	public function getQuickCodes($where){
		return M('quick_response_code')->where($where)->field('id,companyid,type,name,picurl,scannum,content')->order('id DESC')->select();
	}
	/**
	 * 获得指定条件的 二维码数据
	 * @param unknown $where
	 */
	public function getWhereQuickCodesInfo($where){
		return M('quick_response_code')->where($where)->field('id,companyid,type,name,picurl,scannum,content')->find();
	}
}