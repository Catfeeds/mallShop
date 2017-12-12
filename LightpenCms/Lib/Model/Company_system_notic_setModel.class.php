<?php
class Company_system_notic_setModel extends Model{
	protected $_validate =array(
		/* array('url','require','点评链接不能为空',1),
		array('isshow','require','是否启用不能为空',1), */
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
	 * 获得详情
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getCompanySystemNoticSetInfo($where){
		return M('company_system_notic_set')->where($where)->field('id,membergetcard,membergetcardisopen,memberchangecardrank,memberchangecardrankisopen,getvoucher,getvoucherisopen,voucherbeforeexpire,voucherbeforeexpireisopen,voucherused,voucherusedisopen,voucherafterexpire,voucherafterexpireisopen,booksubmit,booksubmitisopen,booksuccess,booksuccessisopen,bookfail,bookfailisopen,bookbeforeexpire,bookbeforeexpireisopen,applysubmit,applysubmitisopen,applysuccess,applysuccessisopen,applyfail,applyfailisopen,applybeforeexpire,applybeforeexpireisopen,applycheckinsuccess,applycheckinsuccessisopen,updatetime,createtime')->find();
	}
}