<?php
class Company_shopsModel extends Model{
    protected $_validate =array(
		array('name','require','门店名称不能为空',1),
		array('tel','require','门店电话不能为空',1),
		array('logourl','require','门店照片必须选择',1),
		array('address','require','门店地址不能为空',1),
		array('longitude','require','经度不能为空',1),
		array('latitude','require','纬度不能为空',1),
		array('sort','require','排序不能为空',1),
		array('sort','number','排序必须为数字',1),
		array('isshow','require','是否显示必须选择',1),
	);
	protected $_auto = array (
		array('adduid','getuid',self::MODEL_INSERT,'callback'),
		array('edituid','getuid',self::MODEL_BOTH,'callback'),
		array('companyid','getcompanyid',self::MODEL_INSERT,'callback'),
		array('createtime','time',self::MODEL_INSERT,'function'),
		array('updatetime','time',self::MODEL_BOTH,'function'),
	);
	public function getcompanyid(){
		return session('cid');
	}
	public function getuid(){
		return session('uid');
	}
	/**
	 * 获得所有的门店信息
	 */
	public function getCompanyAllShopsInfo(){
		return M('company_shops')->where(array('companyid'=>session('cid'),'isshow'=>1))->field('id,name,address,logourl,latitude,longitude')->order('sort ASC,id DESC')->select();
	}
	/**
	 * 获得条件下的门店信息
	 */
	public function getWhereCompanyShopsList($where){
		return M('company_shops')->where($where)->field('id,companyid,tel,name,shopname,address,logourl,latitude,longitude,isopenmobilebook,isopenspamobilebook,isopenmobilebookrepast,isopenshanhui,isopentakeout')->order('sort ASC,id DESC')->select();
	}
	/**
	 * 获得条件下的门店信息
	 */
	public function getWhereCompanyShopsInfo($where){
		return M('company_shops')->where($where)->field('id,companyid,logourl,name,latitude,scannum,longitude')->order('sort ASC,id DESC')->select();
	}
	/**
	 * 获得条件下的门店信息
	 */
	public function getCompanyShopsInfo($where){ 
		return M('company_shops')->where($where)->field('id,companyid,tel,name,address,logourl,latitude,longitude,isopentakeout')->find();
	}
	/**
	 * 大众点评  网页链接+URL
	 */
	public function getDaZhongDianPingList($where){
		return M('company_shops')->table('tp_company_shops AS shop')->join('tp_company_shop_comments_dianping AS dianping ON shop.id = dianping.shopid')->field('shop.id as sid,shop.name,dianping.companyid')->where($where)->order('shop.sort ASC,shop.id DESC')->select();
	}
	/**
	 * tripadvisor 网页链接+URL
	 */
	public function getTripadvisorList($where){
		return M('company_shops')->table('tp_company_shops AS shop')->join('tp_company_shop_comments_tripadvisor AS tripadvisor ON shop.id = tripadvisor.shopid')->field('shop.id as sid,shop.name,tripadvisor.companyid')->where($where)->order('shop.sort ASC,shop.id DESC')->select();
	}
}
?>