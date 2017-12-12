<?php
class Survey_themeModel extends Model{
	protected $_validate =array(
		array('name','require','网页标题不能为空',1),
		array('starttime','require','开始时间不能为空',1),
		array('endtime','require','结束时间不能为空',1),
		array('info','require','调研说明不能为空',1),
		array('shareimg','require','微信分享图片不能为空',1),
		array('sharefriendstitle','require','微信分享标题不能为空',1),
		array('sharedes','require','分享描述不能为空',1),
	);
	protected $_auto = array (
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
	public function getSurveyThemeList($where){
		return M('survey_theme')->where($where)->field('id,companyid,name')->order('sort ASC,id DESC')->select();
	}
	public function getSurveyThemeInfo($where){
		return M('survey_theme')->where($where)->find();
	}
}