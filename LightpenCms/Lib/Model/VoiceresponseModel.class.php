<?php
class VoiceresponseModel extends Model{

	protected $_validate =array(
		array('title','require','标题不能为空',1),
		array('keyword','require','关键词不能为空',1),
		array('musicurl','require','音乐连接不能为空',1),
		array('musicurl','url','音乐连接格式不正确',1),
		array('hqmusicurl','require','高品质音乐连接不能为空',1),
		array('hqmusicurl','url','高品质音乐连接格式不正确',1),
	);
	protected $_auto = array (
		array('adduid','getuid',self::MODEL_INSERT,'callback'),
		array('edituid','getuid',self::MODEL_BOTH,'callback'),
		array('companyid','getcompanyid',self::MODEL_INSERT,'callback'),
		array('shopsid','getshopsid',self::MODEL_INSERT,'callback'),
		array('createtime','time',self::MODEL_INSERT,'function'),
		array('updatetime','time',self::MODEL_BOTH,'function'),
		array('token','gettoken',self::MODEL_INSERT,'callback'),
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
	function gettoken(){
		return session('token');
	}
	
}