<?php
class Member_voucher_poolModel extends Model{

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
	 * 获得券池列表
	 */
	public function getMemberVoucherPoolList($where){
		return  M('member_voucher_pool')->where($where)->field('id,sn,issend,sendtime,updatetime,createtime')->order('id DESC')->select();
	}
	/**
	 * 获得券池详情
	 */
	public function getMemberVoucherPoolInfo($where){
		return  M('member_voucher_pool')->where($where)->field('id,sn,issend,sendtime,updatetime,createtime')->find();
	}
	
}