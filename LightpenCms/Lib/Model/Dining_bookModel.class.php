<?php
class Dining_bookModel extends Model{
	protected $_validate =array(
		array('shopid','require','预订门店必须选择',0),
		array('bookdate','require','预订日期必须选择',1),
		array('booktime','require','预订时间必须选择',1),
		array('bookpeoplenum','require','用餐人数必须填写',1),
		array('bookcontact','require','联系人姓名必须填写',1),
			array('bookcontact','1,10','填写信息过长',1,'length',3),
		array('bookcontactmoblie','require','联系人手机必须填写',1),
			array('bookcontactmoblie','number','联系人手机必须填写',1),
			array('bookcontactmoblie','1,11','手机长度设置错误',1,'length',3),
	);
	protected $_auto = array (
		array('adduid','getuid',self::MODEL_INSERT,'callback'),
		array('edituid','getuid',self::MODEL_BOTH,'callback'),
		array('companyid','getcompanyid',self::MODEL_INSERT,'callback'),
		array('shopsid','getshopsid',self::MODEL_BOTH,'callback'),
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
	 * 获得 公司 时间段内的 餐厅预订量
	 * @param unknown $companyDiningBookWhere
	 */
	public function getComapnyDiningBookNum($companyDiningBookWhere){
		$count =  M('dining_book')->where($companyDiningBookWhere)->count();
		return $count > 0 ? $count : 0 ;
	}
}