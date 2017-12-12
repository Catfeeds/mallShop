<?php
class System_diymenModel extends Model{
	protected $_validate =array(
		array('pid','require','一级菜单不能为空',1),
		array('title','require','菜单名称不能为空',1),
	);
	protected $_auto = array (
		array('adduid','getuid',self::MODEL_INSERT,'callback'),
		array('edituid','getuid',self::MODEL_BOTH,'callback'),
		array('companyid','getcompanyid',self::MODEL_INSERT,'callback'),
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
	 * 自定义菜单设置详情
	 */
	public function getSystemDiymenList($where){
		return M('system_diymen')->where($where)->field('id,pid,title,is_show,sort,url')->order('sort')->select();
	}
	/**
	 * 自定义菜单设置详情
	 */
	public function getSystemDiymenInfo($where){
		return M('system_diymen')->where($where)->field('id,pid,title,is_show,sort,url')->find();
	}
}