<?php
class Member_wechat_infoModel extends Model{

	protected $_validate =array(
		array('nickname','require','昵称不能为空',1),
		array('gender','require','性别必须选择',1),
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
	 * 获得 指定 条件的 注册会员数量
	 * @param unknown $memberRegisterCountWhere
	 */
	public function getMemberWechatCount($memberWechatCountWhere){
		$count = M('Member_wechat_info')->where($memberWechatCountWhere)->count();
		return $count > 0 ? $count : 0 ;
	}
	/**
	 * 获得 指定 条件的 详情
	 * @param unknown $memberRegisterCountWhere
	 */
	public function getMemberWechatInfo($memberWechatCountWhere){
		return M('Member_wechat_info')->where($memberWechatCountWhere)->find();
	}
}