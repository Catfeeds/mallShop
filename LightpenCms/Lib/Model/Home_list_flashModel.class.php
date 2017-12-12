<?php
class Home_list_flashModel extends Model{
	protected $_validate =array(
		array('title','require','幻灯片名称不能为空',1),
		array('img','require','图片地址',1),
		//array('url','require','跳转地址',1),
		array('sort','number','显示顺序不能为空',1),
		array('isshow','number','是否显示不能为空',1),
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
	 * 获得 幻灯片列表
	 * @param unknown $where
	 */
	public function getWhereHomeListFlashList($where){
		return M('home_list_flash')->where($where)->field('id,title,img,url,isshow,sort')->order('sort ASC,id DESC')->select();
	}
	/**
	 * 获得 幻灯片 详细信息
	 * @param unknown $where
	 */
	public function getWhereHomeListFlashInfo($where){
		return M('home_list_flash')->where($where)->field('id,homelistid,title,img,url,isshow,sort')->find();
	}
	
}