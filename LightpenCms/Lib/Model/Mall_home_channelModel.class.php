<?php
class Mall_home_channelModel extends Model{
	protected $_validate = array(
		//array('title','require','频道名称不能为空',1),
		//array('img','require','频道封面不能为空',1),
		array('sort','require','显示排序不能为空',1),
		array('sort','number','显示排序必须为数字',1),
		//array('icon','require','频道图标不能为空',1),
	);
	protected $_auto = array(
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
	 * 获得 频道列表列表
	 * @param unknown $where
	 */
	public function getMallHomeChannelList($where){
		return M('mall_home_channel')->where($where)->field('id,title,info,img,url,icon,sort')->order('sort ASC,id DESC')->select();
	}
	/**
	 * 获得 频道详情
	 * @param unknown $where
	 */
	public function getMallHomeChannelInfo($where){
		return M('mall_home_channel')->where($where)->field('id,title,info,img,url,icon,sort')->find();
	}
}