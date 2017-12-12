<?php
class UsersModel extends Model{

	//自动验证
	protected $_validate=array(
		array('username','require','用户名称必须填写！',0,'',3),
		array('username','','用户名称已经存在！',0,'unique',3),
		array('password','require','用户密码必须填写！',0,'',3),
		array('repeat_password','password','两次密码不一致',0,'confirm'), 
		//array('phone','require','手机必须填写！',0,'',3),
		//array('phone','/^(13[0-9]|15[012356789]|18[0-9]|14[57])[0-9]{8}$/','手机格式不正确',3),
		//array('phone','','手机已经存在！',0,'unique',3),
		//array('email','email','邮箱格式不正确',0,'',3),
		//array('email','','邮箱已经存在！',0,'unique',1),
	);
	protected $_auto = array (
		array('parentid','getuid',self::MODEL_INSERT,'callback'),
		array('companyid','getcompanyid',self::MODEL_INSERT,'callback'),
		array('createtime','time',self::MODEL_INSERT,'function'),
		array('updatetime','time',self::MODEL_BOTH,'function'),
		array('lasttime','time',self::MODEL_INSERT,'function'),
		array('createip','getip',self::MODEL_INSERT,'callback'),
		array('lastip','getip',self::MODEL_BOTH,'callback'),
	);
	public function getip(){
		return get_client_ip(0);
	}
	public function getcompanyid(){
		return session('cid');
	}
	public function getuid(){
		return session('uid');
	}
	public function getCompanyAllUsersInfo(){
		return M('users')->where(array('companyid'=>session('cid')))->field('id,truename')->select();
	}
}