<?php
class Member_card_rank_setModel extends Model{

	protected $_validate =array(
		array('info','require','会员权益不能为空',1),
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
	 * 会员等级说明
	 */
	public function getMemberCardRankSetInfo(){
		return M('member_card_rank_set')->where(array('companyid'=>session('cid')))->find();
	}
}