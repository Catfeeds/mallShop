<?php
class Mall_tagsModel extends Model{
	protected $_validate = array(
		array('name','require','类品标签名称不能为空',1),
		array('title','require','网页标题不能为空',1),
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
	 * 获得品类设置信息
	 */
	public function getMallTagsInfo($where){
		return M('mall_tags')->where($where)->field('id,companyid,name,title,tplid,tplname,backgroundImage,ordertype,shareimg,sharefriendstitle,sharedes')->find();
	}
}