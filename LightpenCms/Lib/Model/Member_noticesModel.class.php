<?php
class Member_noticesModel extends Model{

	protected $_validate =array(
		array('mid','require','客户id不能为空',1),
		array('info','require','站内信内容不能为空',1),
	);
	
	protected $_auto = array (
		array('companyid','getcompanyid',self::MODEL_INSERT,'callback'),
		array('createtime','time',self::MODEL_INSERT,'function'),
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
	public function getMessageNoticesInfo($where){
		return M('message_notices')->where($where)->field('id,info,unsentnum,groupid,sentnum,sendtime')->find();
	}
	
	/**
	 * 获得 站内信 列表
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getWhereMessageNoticesList($where){
		return M('message_notices')->where($where)->field('id,info,unsentnum,groupid,sentnum,sendtime')->select();
	}
}