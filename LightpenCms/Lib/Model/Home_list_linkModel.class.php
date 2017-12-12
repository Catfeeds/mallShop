<?php
class Home_list_linkModel extends Model{

	protected $_validate =array(
		// array('title','require','关联链接标题必须填写',1),
		array('url','require','关联网页链接必须填写',1),
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
	 * 获得官网列表关联设置信息
	 * @param unknown $where
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getWhereHomeListLinkInfo($where){
		return M('home_list_link')->where($where)->field('id,listid,companyid,title,info,pic,url,sort')->find();
	}
	/**
	 * 获得官网列表关联设置信息列表
	 * @param unknown $where
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getWhereHomeListLinkList($where){
		return M('home_list_link')->where($where)->field('id,listid,companyid,title,info,pic,url,sort')->order('sort ASC,updatetime DESC')->select();
	}
	/**
	 * 获得官网列表关联设置信息计数
	 * @param unknown $where
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getWhereHomeListLinkCount($where){
		$count = M('home_list_link')->where($where)->count();
		return $count > 0 ? $count : 0 ;
	}
}