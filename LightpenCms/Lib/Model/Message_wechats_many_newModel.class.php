<?php
class Message_wechats_many_newModel extends Model{
	protected $_validate =array(
		array('title','require','标题不能为空',1),
		array('pic','require','图文封面不能为空',1),
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
	//单条查询
	public function getMessageWechatsManyNewInfo($where){
		return M('message_wechats_many_new')->field('id,thumb_media,manynewsid,pic,author,title,content_source_url,content,show_cover_pic')->where($where)->find();
	}
	//多条查询
	public function getMessageWechatsManyNewList($where){
		return M('message_wechats_many_new')->where($where)->field('id,thumb_media,manynewsid,pic,author,title,content_source_url,content,show_cover_pic')->order('id ASC')->select();
	}
	//数量
	public function getMessageWechatsManyNewCount($where){
		$count = M('message_wechats_many_new')->where($where)->count();
		return $count > 0 ? $count : 0 ;
	}
}