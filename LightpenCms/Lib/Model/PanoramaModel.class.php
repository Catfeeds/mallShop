<?php
class PanoramaModel extends Model{

	protected $_validate =array(
		array('title','require','相册名称不能为空',1),
		array('info','require','相册简介不能为空',1),
		array('frontpic','require','相册图片-前必须选择',1),
		array('rightpic','require','相册图片-右必须选择',1),
		array('backpic','require','相册图片-后必须选择',1),
		array('leftpic','require','相册图片-左必须选择',1),
		array('toppic','require','相册图片-上必须选择',1),
		array('bottompic','require','相册图片-下必须选择',1),
		//array('sort','require','排序不能为空',1),
	);
	protected $_auto = array (
		array('adduid','getuid',self::MODEL_INSERT,'callback'),
		array('edituid','getuid',self::MODEL_BOTH,'callback'),
		array('companyid','getcompanyid',self::MODEL_INSERT,'callback'),
		array('shopsid','getshopsid',self::MODEL_BOTH,'callback'),
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