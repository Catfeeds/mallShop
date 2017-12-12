<?php
class LiuyanModel extends Model{

	protected $_validate =array(
		array('title','require','留言标题不能为空',1),
		array('info','require','留言简介不能为空',1),
		array('headpic','require','头部图片必须选择',1),
		array('sort','require','排序必须不能为空',1),
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
}