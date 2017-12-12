<?php
class Member_group_linkModel extends Model{

	/**
	 * 获得条件下的 计数
	 * @param unknown $groupid
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getMemberGrouplinkCount($where){
		$count = M('member_group_link')->where($where)->count();
		return $count > 0 ? $count : 0 ;
	}
	/**
	 * 获得条件下的列表
	 * @param unknown $groupid
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getMemberGrouplinkMemberList($where){
		//return M('member_group_link')->table('tp_member_group_link AS link')->join('tp_member_register_info AS register ON link.mid=register.id ')->where($where)->field('register.id,register.lastspendingtime,register.birthday')->select();
		return M('member_group_link')->table('tp_member_group_link AS link')->join(array('tp_member_register_info AS register ON link.mid=register.id ','tp_member_card_info as card on card.mid=register.id','tp_member_card_rank as rank on card.rankid=rank.id'))->where($where)->field('rank.name as rankname,register.id,register.name,card.cardnum,register.lastspendingtime,register.birthday,register.weddingday,register.loverbirthday,register.fatherbrithday,register.motherbirthday,register.childbirthday,register.createtime')->order('register.id DESC')->select();
		//return M('member_group_link')->table('tp_member_group_link AS link')->join(array('tp_member_register_info AS register ON link.mid=register.id ','tp_member_card_rank as rank on register.cid=rank.id','tp_member_card_info as card on card.mid=register.id'))->where($where)->field('rank.name as rankname,register.id,register.name,card.cardnum,register.lastspendingtime,register.birthday,register.weddingday,register.loverbirthday,register.fatherbrithday,register.motherbirthday,register.childbirthday,register.childbirthday2,register.childbirthday3,register.friendbirthday,register.friendbirthday2,register.friendbirthday3,register.friendbirthday4,register.friendbirthday5,register.createtime')->order('register.id DESC')->select();
	}
	/**
	 * 获得条件下的列表
	 * @param unknown $groupid
	 * @return Ambigous <mixed, boolean, NULL, multitype:>
	 */
	public function getMemberGrouplinkMemberList2($where){
		return M('member_group_link')->table('tp_member_group_link AS link')->join(array('tp_member_register_info AS register ON link.mid=register.id ','tp_member_card_info as card on register.id=card.mid','tp_member_card_rank as rank on card.rankid=rank.id'))->where($where)->field('rank.name as rankname,register.id,register.name,register.cardnum,register.lastspendingtime,register.birthday,register.weddingday,register.loverbirthday,register.fatherbrithday,register.motherbirthday,register.childbirthday,register.childbirthday2,register.childbirthday3,register.friendbirthday,register.friendbirthday2,register.friendbirthday3,register.friendbirthday4,register.friendbirthday5,register.createtime')->order('register.id DESC')->select();
	}
}