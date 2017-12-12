<?php
class Company_shop_photo_listModel extends Model{

	protected $_validate =array(
			array('title','require','相册名称不能为空',1),
			array('picurl','require','相册封面图不能为空',1),
			array('sort','require','显示顺序不能为空',1),
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
	public function getcompanyshopphotolist($Where){
		return M('Company_shop_photo_list')->field('companyid,id,title,picurl')->where($Where)->order('sort ASC,id DESC')->select();
	}
}
?>