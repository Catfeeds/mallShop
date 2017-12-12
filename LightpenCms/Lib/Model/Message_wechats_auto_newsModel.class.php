<?php
class Message_wechats_auto_newsModel extends Model{

	protected $_validate =array(
		array('title','require','单图文标题不能为空',1),
		array('digest','require','单图文摘要不能为空',1),
		array('thumb_media','require','单图文封面必须选择',1),
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
	 * 获得 站内信 详情
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getMessageWechatsNewsInfo($where){
		return M('message_wechats_auto_news')->where($where)->find();
	}
	
	/**
	 * 获得 站内信 列表
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getWhereMessageWechatsNewsList($where){
		return M('message_wechats_auto_news')->where($where)->select();
	}
}