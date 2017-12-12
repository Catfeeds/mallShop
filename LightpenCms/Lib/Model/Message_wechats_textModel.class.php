<?php
class Message_wechats_textModel extends Model{

	protected $_validate =array(
		array('content','require','文字回复内容不能为空',1),
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
	 * 获得 文字模板 详情
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getMessageWechatsTextInfo($where){
		return M('message_wechats_text')->where($where)->find();
	}
	
	/**
	 * 获得 文字模板 列表
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getWhereMessageWechatsTextList($where){
		return M('message_wechats_text')->where($where)->select();
	}
}