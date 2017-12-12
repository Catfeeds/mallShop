<?php
class CustommenusModel extends Model{
	protected $_validate =array(
		array('title','require','名称不能为空',1),
		array('is_show','require','是否显示不能为空',1),
		array('sort','require','排序默认不能为空',1),
		array('url','require','链接地址不能为空',1),
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
	 * 获取多条数据
	 */
	public function getCustommenusList($where){
		return M('custommenus')->where($where)->field('id,pid,title,is_show,sort,url,clicknum,createtime,updatetime')->order('sort ASC;id DESC')->select();
	}
	/**
	 * 获取单条数据
	 */
	public function getCustommenusInfo($where){
		return M('custommenus')->where($where)->field('id,pid,title,is_show,sort,url,clicknum')->find();
	}
}