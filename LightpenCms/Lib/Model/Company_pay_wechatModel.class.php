<?php
class Company_pay_wechatModel extends Model{

	protected $_validate =array(
		array('toaccount','require','商户号不能为空',1),
		array('keypassword','require','密钥不能为空',1),
		/* array('isshow','require','是否上架不能为空',1),
		array('requiredintegral','require','所需积分不能为空',1),
		array('stocknumber','require','库存数量不能为空',1,),
		array('sort','require','排序默认不能为空',1), */
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
	
	public function getCompanyPayWechatInfo($where){
		return M('company_pay_wechat')->where($where)->field('id,toaccount,key,keypassword,isshow,isempower,apicert,apikey,payversion')->find();
	}
}