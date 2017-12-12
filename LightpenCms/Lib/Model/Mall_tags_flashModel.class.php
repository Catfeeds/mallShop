<?php
class Mall_tags_flashModel extends Model{
	protected $_validate = array(
		array('img','require','幻灯片封面不能为空',1),
		array('sort','require','显示顺序不能为空',1),
		array('sort','number','显示顺序不能为空',1),
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
	 * 获得 幻灯片列表
	 * @param unknown $where
	 */
	public function getMallTagsFlashList($where){
		return M('mall_tags_flash')->where($where)->field('id,title,img,url,isshow,sort')->order('sort ASC,id DESC')->select();
	}
	/**
	 * 获得 幻灯片 详细信息
	 * @param unknown $where
	 */
	public function getMallTagsFlashInfo($where){
		return M('mall_tags_flash')->where($where)->field('id,title,img,url,isshow,sort')->find();
	}
}