<?php
class Message_wechats_many_newsModel extends Model{
	protected $_validate =array(
		array('token','require','公众号不能为空',1),
		array('title','require','多图文模板名称不能为空',1),
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
	
	public function getMessageWechatsManyNewsInfo($where){
		return M('message_wechats_many_news')->field('id,token,title,desc,isuse')->where($where)->find();
	}
}