<?php
class Member_card_info_setModel extends Model{

	protected $_validate =array(
		array('cardbackgroundcolor','require','会员卡背景色必须选择',1),
		array('name','require','会员卡中文名称必须设置',1),
		array('enname','require','会员卡英文名称必须设置',1),
		array('cardfieldcontentcolor','require','字段内容颜色必须选择',1),
		array('cardlogo','require','会员卡LOGO必须选择',1),
		//array('cardbeautifypic','require','会员卡装饰图片必须选择',1),
		array('cardnum','number','会员卡号必须填写数字',1),
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
	public function getMemberCardInfoSet(){
		return M('member_card_info_set')->where(array('companyid'=>session('cid')))->field('id,name,enname,cardbackgroundcolor,cardfieldcontentcolor,cardlogo,cardbeautifypic,cardnumprefix,cardnum')->find();
	}
}