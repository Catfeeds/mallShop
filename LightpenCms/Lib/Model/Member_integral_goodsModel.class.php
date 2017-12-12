<?php
class Member_integral_goodsModel extends Model{

	protected $_validate =array(
		array('name','require','商品名称不能为空',1),
		array('picurl','require','主图地址不能为空',1),
		array('isshow','require','是否上架不能为空',1),
		array('requiredintegral','require','所需积分不能为空',1),
		array('stocknumber','require','库存数量不能为空',1,),
		array('sort','require','排序默认不能为空',1),
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
	
}