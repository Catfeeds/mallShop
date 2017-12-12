<?php
class Diymen_classModel extends Model{
	protected $_validate = array(
		array('token','require','关联公众号必须选择',1),
		array('title','require','主菜单名称必须填写',1),
	 );
	protected $_auto = array (
		array('adduid','getuid',self::MODEL_INSERT,'callback'),
		array('edituid','getuid',self::MODEL_BOTH,'callback'),
		array('companyid','getcompanyid',self::MODEL_INSERT,'callback'),
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
	 * 获得菜单
	 * @param unknown $where
	 */
	public function getWechatDiymenList($where){
		if($where['pid'] == 0 ){
			return M('diymen_class')->where($where)->order('sort ASC,id ASC')->field('id,pid,sort,title,keyword,url,is_show,num,content,replytype,contenttype')->limit(3)->select();
		}else{
			return M('diymen_class')->where($where)->order('sort ASC,id ASC')->field('id,pid,sort,title,keyword,url,is_show,num,content,replytype,contenttype')->limit(5)->select();
		}
		
	}
	/**
	 * 获得菜单
	 * @param unknown $where
	 */
	public function getWechatDiymenLists($where){
		if($where['pid'] == 0 ){
			return M('diymen_class')->where($where)->order('sort ASC,id ASC')->field('id,pid,sort,title,keyword,url,is_show,num,content,replytype,contenttype')->select();
		}else{
			return M('diymen_class')->where($where)->order('sort ASC,id ASC')->field('id,pid,sort,title,keyword,url,is_show,num,content,replytype,contenttype')->select();
		}
	
	}
}
?>