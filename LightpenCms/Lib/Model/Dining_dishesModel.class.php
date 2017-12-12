<?php
class Dining_dishesModel extends Model{

	protected $_validate =array(
		array('name','require','菜品名称不能为空',1),
		array('price','require','菜品价格不能为空',1),
		array('classid','require','菜品类别必须选择',1),
		array('pic','require','菜品照片必须选择',1),
	);
	protected $_auto = array (
		array('adduid','getuid',self::MODEL_INSERT,'callback'),
		array('edituid','getuid',self::MODEL_BOTH,'callback'),
		array('companyid','getcompanyid',self::MODEL_INSERT,'callback'),
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
	/**
	 * 获得 菜品 列表
	 * @param unknown $diningDishesWhere
	 */
	public function getDiningDishesList($diningDishesWhere){
		return M('dining_dishes')->where($diningDishesWhere)->field('id,name,pic,issale,price,saleprice,classid')->order('sort ASC,id DESC')->select();		
	}
	
}