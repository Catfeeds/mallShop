<?php
class Mobile_book_setModel extends Model{
	protected $_validate =array(
		array('name','require','订座页面名称',1),
		array('isshow','require','是否开启不能为空',1),
		array('bookdatetype','require','接受预约日期类型不能为空',1),
		//array('bookbeforedays','require','接收提前几天预约的天数不能为空',2),
		//array('bookinsidedays','require','接收预约几天内的天数不能为空',2),
		//array('renamecommondate','require','重命名预约日期不能为空',1),
		//array('commondateinputcue','require','预约日期输入提示不能为空',1),
		array('bookpeoplemin','require','预约接受最低人数不能为空',1),
		array('bookpeoplemax','require','预约接受最高人数不能为空',1),
		array('booktimetype','require','接受预约时间类型不能为空',1),
		array('remarkinputcue','require','附加要求输入提示不能为空',1),
		//array('booktimeids','require','接收预约的时间段不能为空',2),
		//array('renamecommontime','require','重命名预约时间不能为空',1),
		//array('commondateinputcue','require','预约时间输入提示不能为空',1),
		//array('renamecommonnumberofpeople','require','重命名预约人数不能为空',1),
		//array('commonnumberofpeopleinputcue','require','预约人数输入提示不能为空',1),
		//array('commonnumberofpeopleisshow','require','是否启用预约人数不能为空',1),
		/* array('renameshops','require','重命名关联门店不能为空',1),
		array('shopsinputcue','require','关联门店输入提示不能为空',1),
		array('shopsisshow','require','是否启用关联门店不能为空',1),
		array('renamebooktableids','require','重命名预约偏好不能为空',1),
		array('booktableidsisshow','require','是否启用预约偏好不能为空',1),
		 
		array('bookinfo','require','预约说明不能为空',1),
		array('renamebookinfo','require','重命名预约说明不能为空',1),*/
		
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
	 * 
	 * @param unknown $where
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getCommonBookSetInfo($where){
		return M('mobile_book_set')->where($where)->field('id,name,bookdatetype,bookbeforedays,bookinsidedays,renamecommondate,commondateinputcue,isshowbookdate,booktimetype,booktimeids,renamecommontime,commontimeinputcue,isshowbooktime,renamecommonnumberofpeople,commonnumberofpeopleinputcue,commonnumberofpeopleisshow,shopids,renameshops,shopsinputcue,shopsisshow,booktableids,renamebooktableids,booktableidsisshow,text1,text2,text3,text4,text5,select1,select2,select3,select4,select5,remarkinputcue,isshowremark,bookinfo,renamebookinfo,isshowbookinfo,isshow,booktimeids1,booktimeids2,booktimeids3,booktimeids4,booktimeids5,booktimeids6,booktimeids7')->find();
	}
	/**
	 * 
	 * 获取信息
	 * 
	 * @param unknown $where
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-3-17
	 */
	public function getCommonBookSetInfos($where){
		return M('mobile_book_set')->where($where)->field('bookshopid,bookpeoplemin,bookpeoplemax,bookcoupe,id,name,bookdatetype,bookbeforedays,bookinsidedays,renamecommondate,commondateinputcue,isshowbookdate,booktimetype,booktimeids,renamecommontime,commontimeinputcue,isshowbooktime,renamecommonnumberofpeople,commonnumberofpeopleinputcue,commonnumberofpeopleisshow,shopids,renameshops,shopsinputcue,shopsisshow,booktableids,renamebooktableids,booktableidsisshow,remarkinputcue,isshowremark,bookinfo,renamebookinfo,isshowbookinfo,isshow,booktimeids1,booktimeids2,booktimeids3,booktimeids4,booktimeids5,booktimeids6,booktimeids7,isdeposit,explain')->find();
	}
	/**
	 * 网页链接+URL
	 * @param unknown $where
	 * @return Ambigous <mixed, boolean, NULL, string, unknown, multitype:, multitype:multitype: , void>
	 */
	public function getCommonBookSetList($where){
		return M('mobile_book_set')->where($where)->field('id,companyid,name,scannum,bookinfo,isshow')->order('id DESC')->select();
	}
}