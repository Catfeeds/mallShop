<?php
class System_contact_usModel extends Model{
	protected $_validate =array(
		array('name','require','您的姓名不能为空',1),
		array('moblie','require','您的手机不能为空',1),
		array('info','require','您想说的不能为空',1),
	);
	protected $_auto = array (
		array('createtime','time',self::MODEL_INSERT,'function'),
		array('updatetime','time',self::MODEL_BOTH,'function'),
	);
}