<?php
class Mall_goodsModel extends Model{
	protected $_validate = array(
		array('title','require','商品标题不能为空',1),
	);
	protected $_auto = array(
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
	public function getMallGoodsInfo($where){
		return M('mall_goods')->where($where)->field('id,goodtype,title,tags,goodnum,salenum,info,pricetype,originalprice,saleprice,isopenvipprice,intprice,canbuynum,weight,freighttype,freighttplid,stockamount,sort,isoffshelves,issoldout,updatetime,shareimg,sharefriendstitle,sharedes')->find();
	}
	public function getMallGoodsList($where){
		return M('mall_goods')->where($where)->field('id,goodtype,title,tags,goodnum,info,pricetype,originalprice,saleprice,isopenvipprice,intprice,canbuynum,weight,freighttype,freighttplid,vouchertype,voucherimgurl,vouchersid,prefix,stockamount,updatetime')->order('id DESC')->select();
	}
}