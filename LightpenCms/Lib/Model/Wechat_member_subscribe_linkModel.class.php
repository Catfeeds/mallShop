<?php
class Wechat_member_subscribe_linkModel extends Model{
	
	/**
	 * 获得 公司 时间段内公众号的关注量
	 * @param unknown $companyDiningBookWhere
	 */
	public function getComapnyWechatMemberSubscribeNum($companyWechatMemberSubscribeNumWhere){
		$count = M('wechat_member_subscribe_link')->where($companyWechatMemberSubscribeNumWhere)->count();
		return $count > 0 ? $count : 0 ;
	}
	/**
	 * 获得 会员 关注公众号列表
	 * @param unknown $companyDiningBookWhere
	 */
	public function getMemberSubscribeWechatList($companyid,$mid){
		return M()->table('tp_wechat_member_subscribe_link AS subscribeLink')->join(array('tp_wechats AS wechats ON wechats.token=subscribeLink.token','tp_quick_response_code AS code ON code.content = subscribeLink.scene_id'))
					->where(array('wechats.companyid'=>$companyid,'subscribeLink.mid'=>$mid))->field('subscribeLink.type,subscribeLink.subscribetime,subscribeLink.unsubscribetime,code.name AS codeName')->select();
	}
	/**
	 * 统计 数据
	 * @param unknown $where
	 */
	public function getWhereMemberSubscribeCountNum($where){
		$count = M()->table('tp_member_wechat_info AS wechat')->join('tp_wechat_member_subscribe_link AS link ON link.openid=wechat.openid')->where($where)->count();
		return $count > 0 ? $count : 0 ;
	}
}