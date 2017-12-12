<?php
class Member_card_infoModel extends Model{
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
	 * 获得 公司 等级 下 有多少 会员卡
	 * @param number $rankId
	 */
	public function getMemberCardRankMemberCount($rankId = 0){
		$count = M('member_card_info')->where(array('rankid'=>$rankId,'companyid'=>session('cid')))->count();
		return $count > 0 ? $count : 0 ;
	}
	/**
	 * 获得 公司 按条件的 会员卡计数
	 * @param unknown $companyMemberCardWhere
	 */
	public function getCompanyMemberCardNumber($companyMemberCardWhere){
		$count = M('member_card_info')->where($companyMemberCardWhere)->count();
		return $count > 0 ? $count : 0 ;
	}
	/**
	 * 获得 公司 按条件的 会员计数
	 * @param unknown $companyMemberCardWhere
	 */
	public function getCompanyMemberRegisterJoinCardNumber($companyMemberCardWhere){
		$count = M('member_card_info')->table('tp_member_card_info AS card')->join('tp_member_register_info AS register ON register.id=card.mid')->where($companyMemberCardWhere)->count();
		return $count > 0 ? $count : 0 ;
	}
	
}