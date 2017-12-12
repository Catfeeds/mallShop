<?php
class Magazine_pageModel extends Model{
	protected $_validate =array(
		array('img','require','页面图片必须选择',1),
		array('sort','require','排序不能为空',1),
	);
	protected $_auto = array (
		array('adduid','getuid',self::MODEL_INSERT,'callback'),
		array('edituid','getuid',self::MODEL_BOTH,'callback'),
		array('companyid','getcompanyid',self::MODEL_INSERT,'callback'),
		array('shopsid','getshopsid',self::MODEL_BOTH,'callback'),
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
	public function getMagazinePageInfo($where){
		return M('magazine_page')->where($where)->field('id,mtid,img,pngImg,pngWay,isshowstyle,custombutton,title,url,sort,isshow')->find();
	}
	public function getMagazinePageList($where){
		return M('magazine_page')->where($where)->field('id,mtid,img,pngImg,pngWay,isshowstyle,custombutton,title,url,sort')->order('sort ASC,id DESC')->select();
	}
}