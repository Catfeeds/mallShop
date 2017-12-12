<?php
class Member_voucher_pool_catModel extends Model{

	protected $_auto = array (
		array('adduid','getuid',self::MODEL_INSERT,'callback'),
		array('edituid','getuid',self::MODEL_BOTH,'callback'),
		array('companyid','getcompanyid',self::MODEL_INSERT,'callback'),
		array('shopsid','getshopsid',self::MODEL_INSERT,'callback'),
		array('createtime','time',self::MODEL_INSERT,'function'),
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
	 * 获得券池类名列表
	 */
	public function getMemberVoucherPoolCatList($where){
		return  M('member_voucher_pool_cat')->where($where)->field('id,name,updatetime,createtime')->order('id DESC')->select();
	}
	/**
	 * 获得券池类名详情
	 */
	public function getMemberVoucherPoolCatInfo($where){
		return  M('member_voucher_pool_cat')->where($where)->field('id,name,updatetime,createtime')->find();
	}
	
}