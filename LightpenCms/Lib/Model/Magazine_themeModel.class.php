<?php
class Magazine_themeModel extends Model{
	protected $_validate =array(
		array('title','require','杂志名称不能为空',1),
		//array('info','require','杂志摘要不能为空',1),
		//array('musicurl','require','背景音乐地址不能为空',1),
		array('shareimg','require','微信分享图片不能为空',1),
		array('sharefriendstitle','require','微信分享标题不能为空',1),
		array('sharedes','require','分享描述不能为空',1),
	);
	protected $_auto = array(
		array('adduid','getuid',self::MODEL_INSERT,'callback'),
		array('edituid','getuid',self::MODEL_BOTH,'callback'),
		array('companyid','getcompanyid',self::MODEL_INSERT,'callback'),
		array('shopsid','getshopsid',self::MODEL_BOTH,'callback'),
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
	public function getMagazineThemeInfo($where){
		return M('magazine_theme')->where($where)->field('id,title,info,musicurl,musicisshow,guaguaimg,guaguaisshow,shareimg,sharefriendstitle,sharedes,isopenhome,homeurl')->find();
	}
}