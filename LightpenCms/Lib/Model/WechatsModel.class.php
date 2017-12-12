<?php
class WechatsModel extends Model{
	protected $_validate =array(
		array('wxname','require','公众号名称不能为空',1),
		array('wxid','require','公众号原始id不能为空',1),
		array('weixin','require','微信号不能为空',1),
		array('headerpic','require','头像地址不能为空',1),
		//array('token','require','TOKEN不能为空',1),
		//array('token','','token已经存在！',1,'unique',1),
		
	);
	protected $_auto = array (
		array('tpltypeid','1',self::MODEL_INSERT),
		array('tpltypename','11_index',self::MODEL_INSERT),
		array('tpllistid','1',self::MODEL_INSERT),
		array('tpllistname','11_list',self::MODEL_INSERT),
		array('tplcontentid','1',self::MODEL_INSERT),
		array('tplcontentname','11_content',self::MODEL_INSERT),
		array('adduid','getuid',self::MODEL_INSERT,'callback'),
		array('edituid','getuid',self::MODEL_BOTH,'callback'),
		array('companyid','getcompanyid',self::MODEL_INSERT,'callback'),
		array('createtime','time',self::MODEL_INSERT,'function'),
		array('updatetime','time',self::MODEL_BOTH,'function'),
	);
	public function getuid(){
		return session('uid');
	}
	public function getcompanyid(){
		return session('cid');
	}
	/**
	 * 获得 公司 公众号 信息
	 * @param number $companyid
	 * @return Ambigous <mixed, boolean, NULL, string, unknown, multitype:, multitype:multitype: , void>
	 */
	public function getCompanyWechatss($where){
		return M('wechats')->where($where)->field('')->order('id DESC')->select();
	}
	/**
	 * 获得 公司 某个公众号 信息
	 * @param number $companyid
	 * @return Ambigous <mixed, boolean, NULL, string, unknown, multitype:, multitype:multitype: , void>
	 */
	public function getCompanyWechatsInfo($where){
		return M('wechats')->where($where)->find();
	}
	/**
	 * 获得 公司 所有 公众号粉丝数
	 * @param number $companyid
	 * @return Ambigous <mixed, boolean, NULL, string, unknown, multitype:, multitype:multitype: , void>
	 */
	public function getCompanyAllWechatssFansNumber($companyid = 0){
		return M('wechats')->where(array('companyid'=>$companyid))->sum('fansnumber');
	}
	
	
}