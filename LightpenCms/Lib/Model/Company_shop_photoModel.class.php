<?php
class Company_shop_photoModel extends Model{

	protected $_validate =array(
			array('title','require','相册名称不能为空',1),
			array('picurl','require','相册封面图不能为空',1),
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
	
	public function getcompanyShoPhoto($where){
		return M('company_shop_photo')->field('id,title,picurl')->where($where)->find();
	}
	/**
	 * 网页链接+URL
	 * @param unknown $where
	 */
	public function getCompanyShoPhotoList($where){
		return M('company_shop_photo')->table('tp_company_shop_photo AS photo')->join('tp_company_shops AS shops ON photo.shopid=shops.id')->field('photo.id,photo.shopid,photo.companyid,shops.name,photo.scannum')->where($where)->order('id desc')->select();
	}
}
?>