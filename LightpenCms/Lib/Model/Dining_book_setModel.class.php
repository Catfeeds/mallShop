<?php
class Dining_book_setModel extends Model{
	protected $_validate =array(
		array('shopid','require','预订门店必须选择',0),
		array('bookdatetype','require','接受订座日期类型',0),
		//array('bookbeforedays','require','接收提前几天预订的天数',0),
		//array('bookinsidedays','require','接收预订几天内的天数',0),
		array('booktimetype','require','接受订座时间类型',0),
		//array('booktimeids','require','接收预订的时间段',0),
		array('booktabletype','require','餐台偏好类型是否开启',0),
		//array('booktableids','require','餐台偏好ids',0),
		array('bookinfo','require','预订说明',0),
		array('isshow','require','是否开启',0),
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
	 * 网页链接+URL
	 * @param unknown $Where
	 */
	public function getComapnyDiningBookList($Where){
		return M('dining_book_set')->table('tp_dining_book_set AS dining')->join('tp_company_shops AS shops ON dining.shopid=shops.id')->field('dining.id,dining.shopid,dining.companyid,dining.bookinfo,shops.name')->where($Where)->order('shops.sort ASC,shops.id DESC')->select();
	}
}