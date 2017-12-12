<?php
class AdmaModel extends Model{
	protected $_validate = array(
			array('title','require','标题不能为空',1),
			array('info','require','功能介绍内容必须填写',1),
			array('url','require','二维码链接必须填写',1),
			array('copyright','require','版权信息必须填写',1),

	 );
	protected $_auto = array (
		array('adduid','getuid',self::MODEL_INSERT,'callback'),
		array('edituid','getuid',self::MODEL_BOTH,'callback'),
		array('companyid','getcompanyid',self::MODEL_INSERT,'callback'),
		array('shopsid','getshopsid',self::MODEL_BOTH,'callback'),
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

?>
