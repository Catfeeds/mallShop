<?php
class KeywordModel extends Model{

	/* protected $_validate =array(
		array('keyword','require','触发关键词不能为空',1),
		array('title','require','图文标题不能为空',1),
		array('picurl','require','图文封面不能为空',1),
		array('content','require','图文简介不能为空',1),
	); */
	protected $_auto = array (
		array('companyid','getcompanyid',self::MODEL_INSERT,'callback'),
		array('shopsid','getshopsid',self::MODEL_INSERT,'callback'),
		array('adduid','getuid',self::MODEL_INSERT,'callback'),
		array('edituid','getuid',self::MODEL_BOTH,'callback'),
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
	 * 获得条件下的关键词列表
	 * @param unknown $where
	 * @return Ambigous <mixed, boolean, NULL, string, unknown, multitype:, multitype:multitype: , void>
	 */
	public function getKeywordList($where){
		return M('keyword')->where($where)->field('id,keyword,title,module,url,shownum,clicknum,clickrate')->select();
	}
	
}