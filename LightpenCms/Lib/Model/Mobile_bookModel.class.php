<?php
class Mobile_bookModel extends Model{
	protected $_validate =array(
		//array('sn','require','SN码不能为空',1),
		array('shopid','require','预约门店不能为空',2),
		//array('mid','require','会员不能为空',1),
		//array('bookdate','require','预约日期不能为空',1),
		//array('booktime','require','预约时间不能为空',1),
		//array('bookpeoplenum','require','预约人数不能为空',1),
		array('bookcontact','require','预约联系人不能为空',1),
		array('bookcontactmoblie','require','预约联系人手机不能为空',1),
		array('bookgender','require','预约人性别不能为空',1),
		//array('bookremark','require','预约备注不能为空',1),
		array('bookstatus','require','预约状态不能为空',0),
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
	 * 获得 订单详情
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getWhereCommonBookInfo($where){
		return M('mobile_book')->where($where)->field('id,sn,shopid,mid,bookdate,booktime,bookpeoplenum,bookcontact,bookcontactmoblie,bookgender,bookremark,bookstatus,createtime')->find();
	}
	/**
	 * 获得 订单 列表
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getWhereCommonBookList($where){
		return M('mobile_book')->where($where)->field('id,sn,shopid,mid,bookdate,booktime,bookpeoplenum,bookcontact,bookcontactmoblie,bookgender,bookremark,bookstatus,createtime')->order('id DESC')->select();
	}
}