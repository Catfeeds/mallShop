<?php
class Member_marketing_activities_voucherModel extends Model{
	protected $_validate =array(
		array('sendparameter','require','投放参数不能为空',0),
		array('sendtime','require','投放条件不能为空',0),
		array('title','require','活动名称不能为空',1),
		array('starttime','require','开始时间必需选择',0),
		array('endtime','require','截至时间必需选择',0),
		array('grouptype','require','关联会员标签必需选择',2),
		array('vouchertype','require','优惠类型必需选择',1),
		array('vouchersid','require','优惠标题必需选择',1),
		//array('prefix','require','券号前缀设置不能为空',1),
		//array('url','require','关联网页链接必需选择',1),
		//array('usepassword','require','使用确认密码不能为空',1),
		//array('usepassword','1,4','使用确认密码长度设置错误',1,'length',3),
		//array('getmessage','require','券获得通知不能为空',1),
		//array('maturitymessage','require','券到期通知不能为空',1),
		//array('expiredmessage','require','券过期通知不能为空',1),
		//array('issendemail','require','电子邮箱同步推送必须选择',1),
		//array('issendsms','require','手机短信同步推送必须选择',1),
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
	 * 获得 条件下的 电子券
	 * @param unknown $where
	 * @return Ambigous <mixed, boolean, NULL, string, unknown, multitype:, multitype:multitype: , void>
	 */
	public function getMemberMarketingActivitiesVoucherList($where){
		return M('member_marketing_activities_voucher')->where($where)->field('id,prefix,type,sendparameter,sendtime,title,starttime,endtime,grouptype,groupvalue,vouchertype,vouchersid,url,createtime')->order('id DESC')->select();
	}
	/**
	 * 获得 条件下的 电子券
	 * @param unknown $where
	 * @return Ambigous <mixed, boolean, NULL, string, unknown, multitype:, multitype:multitype: , void>
	 */
	public function getMemberMarketingActivitiesVoucherLinkVoucherInfoList($where){
		return M()->table('tp_member_marketing_activities_voucher as voucher')->join('tp_member_marketing_activities_voucher_info as vinfo on vinfo.id = voucher.vouchersid')->where($where)->field('vinfo.title as vtitle,vinfo.usestarttime,vinfo.useendtime,vinfo.issystemtips,vinfo.issmstips,vinfo.iswechattips,voucher.id,voucher.prefix,voucher.type,voucher.sendparameter,voucher.sendtime,voucher.title,voucher.starttime,voucher.endtime,voucher.grouptype,voucher.groupvalue,voucher.vouchertype,voucher.vouchersid,voucher.url,voucher.createtime')->order('voucher.id DESC')->select();
	}
	/**
	 * 获得 电子券 活动详情
	 * @param unknown $where
	 * @return Ambigous <mixed, boolean, NULL, string, unknown, multitype:, multitype:multitype: , void>
	 */
	public function getMemberMarketingActivitiesVoucherInfo($where){
		return M('member_marketing_activities_voucher')->where($where)->field('id,prefix,type,sendparameter,sendtime,title,starttime,endtime,grouptype,groupvalue,vouchertype,vouchersid,url,createtime')->find();
	}
	/**
	 * 获得 条件下的 电子券
	 * @param unknown $where
	 * @return Ambigous <mixed, boolean, NULL, string, unknown, multitype:, multitype:multitype: , void>
	 */
	public function getMemberMarketingActivitiesVoucherLinkVoucherInfoInfo($where){
		return M()->table('tp_member_marketing_activities_voucher as voucher')->join('tp_member_marketing_activities_voucher_info as vinfo on vinfo.id = voucher.vouchersid')->where($where)->field('vinfo.title as vtitle,vinfo.usestarttime,vinfo.useendtime,vinfo.issystemtips,vinfo.issmstips,vinfo.iswechattips,voucher.id,voucher.prefix,voucher.type,voucher.sendparameter,voucher.sendtime,voucher.title,voucher.starttime,voucher.endtime,voucher.grouptype,voucher.groupvalue,voucher.vouchertype')->find();
	}
}