<?php
class Member_register_infoModel extends Model{

	protected $_validate =array(
		/* array('name','require','姓名不能为空',0),
		array('gender','require','性别必须选择',0),
		array('moblie','require','手机不能为空',0),
		array('email','require','邮箱不能为空',0),
		array('email','email','邮箱格式错误',0),
		array('email','1,30','邮箱长度设置错误',0,'length',3),
		array('truepassword','require','密码不能为空',0),
		array('truepassword','1,16','密码长度设置错误',0,'length',3),
		array('birthday','require','生日必须选择',2), */
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
	 * 获得 注册用户详细信息
	 * @param unknown $memberRegisterWhere
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getMemberRegisterInfo($memberRegisterWhere){
		return M('member_register_info')->where($memberRegisterWhere)
		->field('id,name,gender,mobliecode,moblie,ischeckmoblietrue,email,ischeckemailtrue,password,truepassword,birthday,lastspendingtime,spendingfrequency,totalspending,totalintegration')
		->find();
	}
	/**
	 * 获得 指定 条件的 注册会员数量
	 * @param unknown $memberRegisterCountWhere
	 */
	public function getMemberRegisterCount($memberRegisterCountWhere){
		$count = M('member_register_info')->where($memberRegisterCountWhere)->count();
		return $count > 0 ? $count : 0 ;
	}
	/**
	 * 获得 公司 会员 指定时间 内的 消费频次 平均值
	 * @param unknown $memberSpendingFrequencyAvgWhere
	 */
	public function getCompanyMemberSpendingFrequencyAvg($memberSpendingFrequencyAvgWhere){
		return M('member_register_info')->where($memberSpendingFrequencyAvgWhere)->avg('spendingfrequency');
	}
	/**
	 * 获得 条件下的 会员列表
	 * @param unknown $memberRegisterWhere
	 * @return Ambigous <mixed, boolean, NULL, string, unknown, multitype:, multitype:multitype: , void>
	 */
	public function getMemberRegisterList($memberRegisterWhere){
		return M()->table('tp_member_register_info as register')->join(array('tp_member_card_info as card on card.mid=register.id','tp_member_card_rank as rank on card.rankid=rank.id'))->where($memberRegisterWhere)
		->field('rank.name as rankname,register.id,register.name,register.gender,register.mobliecode,register.moblie,register.ischeckmoblietrue,register.email,register.ischeckemailtrue,register.password,register.truepassword,register.birthday,register.lastspendingtime,register.spendingfrequency,register.totalspending,register.totalintegration,card.cardnum,register.weddingday,register.loverbirthday,register.fatherbrithday,register.motherbirthday,register.childbirthday')
		->select();
		//->field('rank.name as rankname,register.id,register.name,register.gender,register.mobliecode,register.moblie,register.cardnum,register.ischeckmoblietrue,register.email,register.ischeckemailtrue,register.password,register.truepassword,register.birthday,register.lastspendingtime,register.spendingfrequency,register.totalspending,register.totalintegration,register.weddingday,register.loverbirthday,register.fatherbrithday,register.motherbirthday,register.childbirthday,register.createtime')
	}
	
	/**
	 * 获得 条件下的 会员详情
	 * @param unknown $memberRegisterWhere
	 * @return Ambigous <mixed, boolean, NULL, string, unknown, multitype:, multitype:multitype: , void>
	 */
	public function getMemberRegisterLinkRankInfo($memberRegisterWhere){
		return M()->table('tp_member_register_info AS register')->join(array('tp_member_card_info as card on register.id=card.mid','tp_member_card_rank as rank on card.rankid=rank.id'))->where($memberRegisterWhere)
		->field('rank.name as rankname,register.id,register.name,register.gender,register.mobliecode,register.moblie,card.cardnum,register.ischeckmoblietrue,register.email,register.ischeckemailtrue,register.password,register.truepassword,register.birthday,register.lastspendingtime,register.spendingfrequency,register.totalspending,register.totalintegration,register.weddingday,register.loverbirthday,register.fatherbrithday,register.motherbirthday,register.childbirthday,register.createtime')
		->find();
	}
}