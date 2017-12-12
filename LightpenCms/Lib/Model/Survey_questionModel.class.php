<?php
class Survey_questionModel extends Model{
	protected $_validate =array(
		array('name','require','问题名称不能为空',1),
		array('type','require','问题类型不能为空',1),
		array('isvereist','require','是否必填不能为空',1),
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
	public function getSurveyQuestionInfo($where){
		return M('survey_question')->where($where)->field('id,tid,name,type,isvereist,A,B,C,D,E,F,G,sort')->find();
	}
	public function getSurveyQuestionList($where){
		return M('survey_question')->where($where)->field('id,tid,name,type,isvereist,A,B,C,D,E,F,G,sort')->order('sort ASC,id DESC')->select();
	}
}