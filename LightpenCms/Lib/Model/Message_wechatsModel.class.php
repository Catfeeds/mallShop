<?php
class Message_wechatsModel extends Model{

	protected $_validate =array(
		array('msgtype','require','模板类型必选选择',1),
		array('msgid','require','模板标题必选选择',1),
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
	 * 获得 群发任务 详情
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getMessageWechatsInfo($where){
		return M('message_wechats')->where($where)->find();
	}
	
	/**
	 * 获得 群发任务列表
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getWhereMessageWechatsList($where){
		return  M('message_wechats')->where($where)->select();
	}
	
	/**
	 * 获得 群发任务计数
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getWhereMessageWechatsCount($where){
		$count = M('message_wechats')->where($where)->count();
		return $count > 0 ? $count : 0 ;
	}
}