<?php
class Member_treasure_box_activitiesModel extends Model{
	protected $_validate =array(
		/* //array('prefix','require','券号前缀不能为空',1),
		array('sendparameter','require','投放参数不能为空',0),
		array('sendtime','require','投放条件不能为空',0),
		array('vouchertype','require','优惠类型不能为空',1),
		array('name','require','活动名称不能为空',1),
		array('starttime','require','开始时间必须选择',0),
		array('endtime','require','截至时间必须选择',0),
		array('info','require','券详情必须填写',1),
		array('groupid','require','投放目标必须选择',0),
		array('usepassword','require','使用确认密码不能为空',1),
		array('usepassword','1,4','使用确认密码长度设置错误',1,'length',3),
		array('getmessage','require','券获得通知不能为空',1),
		//array('maturitymessage','require','券到期通知不能为空',1),
		//array('expiredmessage','require','券过期通知不能为空',1),
		//array('issendemail','require','电子邮箱同步推送必须选择',1),
		array('issendsms','require','手机短信同步推送必须选择',1), */
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
	 * 获得 条件下的百宝箱活动列表
	 * @param unknown $where
	 * @return Ambigous <mixed, boolean, NULL, string, unknown, multitype:, multitype:multitype: , void>
	 */
	public function getMemberTreasureBoxActivitiesList($where){
		return M('member_treasure_box_activities')->where($where)->field('id,companyid,title,activitiestarttime,activitieendtime,url,grouptype,groupvalue,consumetype,consumejifen,consumejidian,maxlotterynumber,probability,prizetype1,prizejifen1,prizevouchertype1,prizevouchersid1,prizeprefix1,prizenum1,prizepic1,prizetype2,prizejifen2,prizevouchertype2,prizevouchersid2,prizeprefix2,prizenum2,prizepic2,prizetype3,prizejifen3,prizevouchertype3,prizevouchersid3,prizeprefix3,prizenum3,prizepic3,prizetype4,prizejifen4,prizevouchertype4,prizevouchersid4,prizeprefix4,prizenum4,prizepic4,bannerpic,shareimg,sharefriendstitle,sharedes,createtime,lotterylimittype,everydaylimit,maxjifelotternumber')->order('id DESC')->select();
	}
	/**
	 * 获得条件下的百宝箱活动详情
	 * @param unknown $where
	 * @return Ambigous <mixed, boolean, NULL, string, unknown, multitype:, multitype:multitype: , void>
	 */
	public function getMemberTreasureBoxActivitiesInfo($where){
		return M('member_treasure_box_activities')->where($where)->field('id,companyid,title,activitiestarttime,activitieendtime,url,grouptype,groupvalue,consumetype,consumejifen,consumejidian,maxlotterynumber,probability,prizetype1,prizejifen1,prizevouchertype1,prizevouchersid1,prizeprefix1,prizenum1,prizepic1,prizetype2,prizejifen2,prizevouchertype2,prizevouchersid2,prizeprefix2,prizenum2,prizepic2,prizetype3,prizejifen3,prizevouchertype3,prizevouchersid3,prizeprefix3,prizenum3,prizepic3,prizetype4,prizejifen4,prizevouchertype4,prizevouchersid4,prizeprefix4,prizenum4,prizepic4,bannerpic,shareimg,sharefriendstitle,sharedes,createtime,lotterylimittype,everydaylimit,maxjifelotternumber')->find();
	}
}