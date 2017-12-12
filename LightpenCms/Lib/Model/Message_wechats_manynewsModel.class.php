<?php
class Message_wechats_manynewsModel extends Model{
	protected $_validate =array(
		array('title','require','多图文模板名称不能为空',1),
		array('newsid','require','关联单图文模板名称必须选择',1),
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
	 * 获得详情
	 * @param unknown $where
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getMessageWechatsManyNewsInfo($where){
		return M('message_wechats_manynews')->field('id,token,title,newsid,newsnum,isuse')->where($where)->find();
	}
	/**
	 * 获得列表
	 * @param unknown $where
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getMessageWechatsManyNewsList($where){
		return M('message_wechats_manynews')->field('id,token,title,newsid,newsnum,isuse')->where($where)->select();
	}
}