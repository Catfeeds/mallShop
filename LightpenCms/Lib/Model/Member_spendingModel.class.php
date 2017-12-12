<?php
class Member_spendingModel extends Model{

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
	 * 获得 公司 限制 条件的 消费 记录统计
	 * @param unknown $companyMemberSpendingWhere
	 */
	public function getCompanyMemberSpendingNum($companyMemberSpendingWhere){
		$count =  M('member_spending')->where($companyMemberSpendingWhere)->count();
		return $count > 0 ? $count : 0 ;
	}
	/**
	 * 获得 公司 消费 记录的 消费额统计
	 * @param unknown $companyMemberTotalspendingWhere
	 */
	public function getCompanyMemberTotalspending($companyMemberTotalspendingWhere){
		$sum =  M('member_spending')->where($companyMemberTotalspendingWhere)->Sum('spendingamount');
		if(empty($sum)){
			$sum = '0.00';
		}
		return $sum;
	}
}