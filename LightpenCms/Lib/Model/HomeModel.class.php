<?php
class HomeModel extends Model{
	protected $_validate =array(
		//array('title','require','网页标题不能为空',1),
		//array('shareimg','require','分享图片不能为空',1),
		//array('sharefriendstitle','require','分享标题不能为空',1),
		//array('sharedes','require','分享描述不能为空',1),
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
	 * 获得官网设置信息
	 * @param unknown $where
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getWhereHomeInfo($where){
		return M('home')->where($where)->field('id,companyid,title,info,backgroundImage,tplid,tplname,shareimg,sharefriendstitle,sharedes')->find();
		
	}
	
}