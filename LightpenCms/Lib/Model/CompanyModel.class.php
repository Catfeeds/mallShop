<?php
class CompanyModel extends Model{
    protected $_validate =array(
		array('name','require','公司名称不能为空',1),
		//array('tel','require','公司电话不能为空',1),
		array('logourl','require','公司LOGO必须选择',1),
		array('address','require','公司地址不能为空',1),
		array('longitude','require','经度不能为空',1),
		array('latitude','require','纬度不能为空',1),
	);
	protected $_auto = array (
		array('createtime','time',self::MODEL_INSERT,'function'),
		array('updatetime','time',self::MODEL_BOTH,'function'),
	);
	/**
	 * 获得公司 信息
	 * @param unknown $companyid
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getComapnyInfo($companyid){
		return M('company')->where(array('id'=>$companyid))->field('id,name,tel,latitude,longitude,info,logourl,copyright,servicenumber,gid,wechatnum,viptime,lasttime,maximgspace')->find();
	}
	/**
	 * 获得正常可用的公司列表
	 * @return Ambigous <mixed, boolean, NULL, string, unknown, multitype:, multitype:multitype: , void>
	 */
	public function getTrueCompanyList(){
		return M('company')->where(array('_string'=>"isclose ='0' and  ((viptime > ".time().") or( gid=5))"))->field('id,permissions,mallorderautoset')->select();
	}
}
?>