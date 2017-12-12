<?php
class Member_vouchersModel extends Model{

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
	/**
	 * 获得 会员电子券
	 */
	public function getMemberVouchersList($memberVouchersWhere){
		return  M('member_vouchers')->table('tp_member_vouchers as vouchers')
		->join(array('LEFT JOIN tp_member_marketing_activities_voucher AS voucher ON voucher.id = vouchers.voucherid'))
		->where($memberVouchersWhere)
		->field('voucher.name AS vouchername,vouchers.useendtime,voucher.vouchertype,vouchers.id,vouchers.sn')->order('vouchers.useendtime DESC')->select();
	}
	/**
	 * 获得 会员电子券列表
	 */
	public function getMemberVouchersLinkVoucherInfoList($Where){
		return  M()->table('tp_member_vouchers as vouchers')
		->join(array('LEFT JOIN tp_member_marketing_activities_voucher_info AS vinfo ON vinfo.id = vouchers.voucherinfoid','tp_member_register_info as register on register.id=vouchers.mid','LEFT JOIN tp_member_card_info AS card ON register.id = card.mid','tp_member_card_rank as rank on card.rankid=rank.id'))
		->where($Where)->field('vouchers.id,vouchers.sn,register.name,card.cardnum,rank.name as rankname,vouchers.mid,vinfo.title as vtitle,vouchers.usestarttime,vouchers.useendtime,vinfo.vouchertype,vinfo.issystemtips,vinfo.issmstips,vinfo.iswechattips')->select();
	}
	/**
	 * 获得 会员电子券详情
	 */
	public function getMemberVouchersLinkVoucherInfoInfo($Where){
		return  M()->table('tp_member_vouchers as vouchers')
		->join(array('LEFT JOIN tp_member_register_info AS register ON register.id = vouchers.mid','LEFT JOIN tp_member_marketing_activities_voucher_info AS vinfo ON vinfo.id = vouchers.voucherinfoid','LEFT JOIN tp_member_card_info AS card ON register.id = card.mid'))
		->where($Where)->field('register.name,card.cardnum,vouchers.mid,vinfo.title,vouchers.usestarttime,vouchers.useendtime,vinfo.vouchertype,vinfo.issystemtips,vinfo.issmstips,vinfo.iswechattips')->find();
	}
}