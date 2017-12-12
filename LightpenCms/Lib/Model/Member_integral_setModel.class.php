<?php
class Member_integral_setModel extends Model{

	protected $_validate =array(
		/*array('integralgetinfo','require','获取积分说明不能为空',1),
		array('registerscore','require','注册赠送积分不能为空',1),
		array('registerscore','number','注册赠送积分必须为整数',1),
		array('registerscore','1,8','注册赠送积分数值设置有误',1,'length',3),
		array('registerscoreinfo','require','注册积分获得说明不能为空',1),
		array('createcardscore','require','开卡送积分不能为空',1),
		array('createcardscore','number','开卡送积分必须为整数',1),
		array('createcardscore','1,8','开卡送积分数值设置有误',1,'length',3),
		array('createcardscoreinfo','require','注册积分获得说明不能为空',1),
		array('consumenum','require','消费积分的消费值不能为空',1),
		array('consumenum','number','消费积分的消费值必须为整数',1),
		array('consumenum','1,8','消费积分的消费值设置有误',1,'length',3),
		array('consumescore','require','消费积分的积分数不能为空',1),
		array('consumescore','number','消费积分的积分数必须为整数',1),
		array('consumescore','1,8','消费积分的积分数设置有误',1,'length',3),
		array('consumescoreinfo','require','消费积分获得说明不能为空',1),
		array('prepaidconsumenum','require','储值积分的消费值不能为空',0),
			array('prepaidconsumenum','number','储值积分的消费值必须为整数',0),
			array('prepaidconsumenum','1,8','储值积分的消费值设置有误',0,'length',3),
		array('prepaidconsumescore','require','储值消费获得积分数不能为空',0),
			array('prepaidconsumescore','number','储值消费获得积分数必须为整数',0),
			array('prepaidconsumescore','1,8','储值消费获得积分数设置有误',0,'length',3),
		array('rechargenum','require','充值数值不能为空',0),
			array('rechargenum','number','充值数值必须为整数',0),
			array('rechargenum','1,8','充值数值设置有误',0,'length',3),
		array('rechargescore','require','充值获得积分数不能为空',0),
			array('rechargescore','number','充值获得积分数必须为整数',0),
			array('rechargescore','1,8','充值获得积分数设置有误',0,'length',3), 
		array('registrationscore','require','签到送积分不能为空',1),
		array('registrationscore','number','签到送积分必须为整数',1),
		array('registrationscore','1,8','签到送积分设置有误',1,'length',3),
		array('registrationscoreinfo','require','签到送积分说明不能为空',1),
		array('recommendscore','require','推荐积分不能为空',1),
		array('recommendscore','number','推荐积分必须为整数',1),
		array('recommendscore','1,8','推荐积分设置有误',1,'length',3),
		array('recommendscoreinfo','require','推荐积分获得说明不能为空',1),
		//大众	
		array('dianpingscore','require','大众点评网赠送积分不能为空',1),
		array('dianpingscore','number','大众点评网赠送积分必须为整数',1),
		array('dianpingscore','1,8','大众点评网赠送积分设置有误',1,'length',3),
		array('dianpinginfo','require','大众点评网赠送积分说明不能为空',1),
		//Tripadvisor
		array('tripadvisorscore','require','tripadvisor点评赠送积分不能为空',1),
		array('tripadvisorscore','number','tripadvisor点评赠送积分必须为整数',1),
		array('tripadvisorscore','1,8','tripadvisor点评赠送积分设置有误',1,'length',3),
		array('tripadvisorinfo','require','tripadvisor点评赠送积分说明不能为空',1),
		//密码	
		array('commentspassword','require','点评送积分确认密码不能为空',1),
		array('commentspassword','4','点评送积分确认密码设置有误',1,'length',3),*/
			
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
	 * 获得条件下的 会员积分设置
	 * @param unknown $where
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getMemberIntegralSetInfo($where){
		return M('member_integral_set')->where($where)->field('integralisautoclear,integralgetinfo,perfectreginfoisopen,perfectreginfoexp,perfectreginfoint,createcardisopen,createcardexp,createcardint,recommendcreatecardisopen,recommendcreatecardexp,recommendcreatecardint,cardrankchangisopen,cardrankchangexp,cardrankchangint,offlinespendingisopen,offlinespendingexp,offlinespendingint,houtaispendingisopen,houtaispendingexp,houtaispendingint,wechatspendingisopen,wechatspendingexp,wechatspendingint,yinhangkapendingisopen,yinhangkapendingexp,yinhangkapendingint,alipaypendingisopen,alipaypendingexp,alipaypendingint,shophuodaofukuanisopen,shophuodaofukuanexp,shophuodaofukuanint,onlinechuzhipayisopen,onlinechuzhipayexp,onlinechuzhipayint,houtaichuzhipayisopen,houtaichuzhipayexp,houtaichuzhipayint,onlinechongzhiisopen,onlinechongzhiexp,onlinechongzhiint,houtaichongzhiisopen,houtaichongzhiexp,houtaichongzhiint,lbsqiandaoisopen,lbsqiandaoexp,lbsqiandaoint,dianpingisopen,dianpingexp,dianpingint,tripadvisorisopen,tripadvisorexp,tripadvisorint,updatetime,windhelperwechatspendingisopen,windhelperwechatspendingexp,windhelperwechatspendingint,windhelperalipaypendingisopen,windhelperalipaypendingexp,windhelperalipaypendingint,windhelpercashspendingisopen,windhelpercashspendingexp,windhelpercashspendingint,createtime')->find();
	}
}