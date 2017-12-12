<?php
    class HostModel extends Model{
    protected $_validate = array(
            array('keyword','require','关键词不能为空',1),
            array('title','require','标题不能为空',1),
            array('ppicurl','require','图文封面必须选择',1),
            array('info','require','简介不能为空',1),
            array('address','require','预约地址不能为空',1),
            array('tel','require','预订电话不能为空',1),
            array('headpic','require','页面头部图片必须选择',1),
            array('info2','require','订单说明不能为空',1),
            array('renameOrderName','require','重命名订单不能为空',1),
            array('renameOrderInfo','require','重命名订单说明不能为空',1),
            array('renameOrderTel','require','重命名预订电话不能为空',1),
     );
    protected $_auto = array (
		array('adduid','getuid',self::MODEL_INSERT,'callback'),
		array('edituid','getuid',self::MODEL_BOTH,'callback'),
		array('companyid','getcompanyid',self::MODEL_INSERT,'callback'),
		array('shopsid','getshopsid',self::MODEL_BOTH,'callback'),
		array('createtime','time',self::MODEL_INSERT,'function'),
		array('updatetime','time',self::MODEL_BOTH,'function'),
		array('token','gettoken',self::MODEL_INSERT,'callback'),
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
	function gettoken(){
		return session('token');
	}
	
}

?>