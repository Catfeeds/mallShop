<?php
class CouponModel extends Model{

	protected $_validate =array(
		array('title','require','标题不能为空',1),
		array('statdate','require','开始时间不能为空',1),			
		array('enddate','require','结束时间不能为空',1),
		array('enddate', 'checkdate', '结束时间不能小于开始时间',Model::MUST_VALIDATE,'callback',3),
		array('activitydescription','require','优惠券活动说明不能为空',1),
		array('userdescription','require','兑换券使用说明不能为空',1),
		array('endtitle','require','活动结束公告主题不能为空',1),
		array('enddescription','require','活动结束说明不能为空',1),
		array('first','require','优惠券1名称不能为空',1),
		array('firstnum','require','优惠券1数量不能为空',1)
	);
	protected $_auto = array (
		array('adduid','getuid',self::MODEL_INSERT,'callback'),
		array('edituid','getuid',self::MODEL_BOTH,'callback'),
		array('companyid','getcompanyid',self::MODEL_INSERT,'callback'),
		array('shopsid','getshopsid',self::MODEL_INSERT,'callback'),
		array('statdate','getstatdate',self::MODEL_BOTH,'callback'),
		array('enddate','getenddate',self::MODEL_BOTH,'callback'),
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
	function getstatdate(){
		return strtotime($_POST['statdate']);
	}
	function getenddate(){
		return strtotime($_POST['enddate']);
	}
	function checkdate(){	
		 if(strtotime($_POST['enddate'])<strtotime($_POST['statdate'])){
			 return false;
		}else{
			return true;
		}
	}
	
}