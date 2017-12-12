<?php
/**
 * （微信、官方）客服
 * Enter description here ...
 * @author yaochengkai
 */
class MemberCustomServiceAction extends UserAction{
	
	private $uid;
	
	private $companyid;
	
	private $shopsid;
	
	public function __construct(){
		parent::__construct();
		$this->uid 		 = session('uid');
		$this->companyid = session('cid');
		$this->shopsid   = session('shopsid');
	}
	/**
	 * 微信客服-最新
	 */
	public function wechatsNew(){
		$this->checkCompanyScrm5Permissions(10,TRUE);
		$this->wechatManage();
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'我的API接入','url'=>U('Wechat/lists'),'rel'=>'','target'=>''),array('name'=>'微信客服','url'=>'','rel'=>'','target'=>'')));
		$count['returnCount'] = M()->table('tp_member_wechat_24hourmessage as 24h')->join(array('LEFT JOIN tp_member_wechat_info AS wechat on wechat.mid=24h.mid'))->where(array('24h.companyid'=>$this->companyid,'24h.msgtype'=>'2','wechat.wechatmessageisreply'=>'1'))->count('distinct(wechat.mid)');
		$count['notReturnCount'] = M()->table('tp_member_wechat_24hourmessage as 24h')->join(array('LEFT JOIN tp_member_wechat_info AS wechat on wechat.mid=24h.mid'))->where(array('24h.companyid'=>$this->companyid,'24h.msgtype'=>'2','wechat.wechatmessageisreply'=>'2'))->count('distinct(wechat.mid)');
		$this->assign('count',$count);
		$count = M()->table('tp_member_wechat_24hourmessage as 24h')->join(array('LEFT JOIN tp_member_wechat_info AS wechat on wechat.mid=24h.mid'))->where(array('24h.companyid'=>$this->companyid,'24h.msgtype'=>'2','wechat.wechatmessageisreply'=>array('in','1,2')))->count('distinct(wechat.mid)');
		$page = new NewPage($count,15);
		$list = M()->query("SELECT 24hm.id,24hm.mid,24hm.info,24hm.isread,24hm.isreply,24hm.createtime,wechat.openid,wechat.nickname,wechat.city,wechat.country,wechat.headimgurl,wechat.wechatmessagetime FROM (SELECT id,mid,info,isread,isreply,companyid,msgtype,createtime FROM tp_member_wechat_24hourmessage ORDER BY id DESC) AS 24hm LEFT JOIN tp_member_wechat_info AS wechat ON wechat.mid = 24hm.mid WHERE ( 24hm.companyid = '".$this->companyid."' ) AND ( 24hm.msgtype = '2' ) AND (wechat.wechatmessageisreply='1' OR wechat.wechatmessageisreply='2') GROUP BY 24hm.mid ORDER BY 24hm.isread,24hm.id DESC LIMIT ".$page->firstRow.",".$page->listRows);
		if($list){
			foreach($list as $Key=>$Val){
				$country = M('area')->where(array('id'=>$Val['country']))->getField('name');
				$city = M('area')->where(array('id'=>$Val['city']))->getField('name');
				$list[$Key]['address'] = $country.$city;
				$list[$Key]['individualCount'] = M('member_wechat_24hourmessage')->where(array('companyid'=>$this->companyid,'mid'=>$Val['mid']))->count();
			}
		}
		$this->assign('list',$list);
		$this->assign('page',$page->show());
		$this->display();
	}
	/**
	 * 微信客服-未回复
	 */
	public function wechatsNotReturn(){
		$this->checkCompanyScrm5Permissions(10,TRUE);
		$this->wechatManage();
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'我的API接入','url'=>U('Wechat/lists'),'rel'=>'','target'=>''),array('name'=>'微信客服','url'=>'','rel'=>'','target'=>'')));
		$count['returnCount'] = M()->table('tp_member_wechat_24hourmessage as 24h')->join(array('LEFT JOIN tp_member_wechat_info AS wechat on wechat.mid=24h.mid'))->where(array('24h.companyid'=>$this->companyid,'24h.msgtype'=>'2','wechat.wechatmessageisreply'=>'1'))->count('distinct(wechat.mid)');
		$count['notReturnCount'] = M()->table('tp_member_wechat_24hourmessage as 24h')->join(array('LEFT JOIN tp_member_wechat_info AS wechat on wechat.mid=24h.mid'))->where(array('24h.companyid'=>$this->companyid,'24h.msgtype'=>'2','wechat.wechatmessageisreply'=>'2'))->count('distinct(wechat.mid)');
		$page = new NewPage($count['notReturnCount'],15);
		$list = M()->query("SELECT 24hm.id,24hm.mid,24hm.info,24hm.isread,24hm.createtime,wechat.openid,wechat.nickname,wechat.city,wechat.country,wechat.headimgurl,wechat.wechatmessagetime FROM (SELECT id,mid,info,isread,isreply,companyid,msgtype,createtime FROM tp_member_wechat_24hourmessage ORDER BY id DESC) AS 24hm LEFT JOIN tp_member_wechat_info AS wechat ON wechat.mid = 24hm.mid WHERE ( 24hm.companyid = '".$this->companyid."' ) AND ( 24hm.msgtype = '2' ) AND ( wechat.wechatmessageisreply = '2' ) GROUP BY 24hm.mid ORDER BY 24hm.isread,24hm.id DESC LIMIT ".$page->firstRow.",".$page->listRows);
		if($list){
			foreach($list as $Key=>$Val){
				$country = M('area')->where(array('id'=>$Val['country']))->getField('name');
				$city = M('area')->where(array('id'=>$Val['city']))->getField('name');
				$list[$Key]['address'] = $country.$city;
				$list[$Key]['individualCount'] = M('member_wechat_24hourmessage')->where(array('companyid'=>$this->companyid,'mid'=>$Val['mid']))->count();
			}
		}
		$this->assign('list',$list);
		$this->assign('count',$count);
		$this->assign('page',$page->show());
		$this->display();
	}
	/**
	 * 微信客服-已回复
	 */
	public function wechatsReturn(){
		$this->checkCompanyScrm5Permissions(10,TRUE);
		$this->wechatManage();
		$this->makeTopUrl = $this->makeTopUrl_User(array(array($this->MANAGE_WELCOME,'name'=>'我的API接入','url'=>U('Wechat/lists'),'rel'=>'','target'=>''),array('name'=>'微信客服','url'=>'','rel'=>'','target'=>'')));
		$count['returnCount'] = M()->table('tp_member_wechat_24hourmessage as 24h')->join(array('LEFT JOIN tp_member_wechat_info AS wechat on wechat.mid=24h.mid'))->where(array('24h.companyid'=>$this->companyid,'24h.msgtype'=>'2','wechat.wechatmessageisreply'=>'1'))->count('distinct(wechat.mid)');
		$count['notReturnCount'] = M()->table('tp_member_wechat_24hourmessage as 24h')->join(array('LEFT JOIN tp_member_wechat_info AS wechat on wechat.mid=24h.mid'))->where(array('24h.companyid'=>$this->companyid,'24h.msgtype'=>'2','wechat.wechatmessageisreply'=>'2'))->count('distinct(wechat.mid)');
		$page = new NewPage($count['returnCount'],15);
		$list = M()->query("SELECT 24hm.id,24hm.mid,24hm.info,24hm.createtime,wechat.openid,wechat.nickname,wechat.city,wechat.country,wechat.headimgurl,wechat.wechatmessagetime,wechat.wechatmessageisreply FROM (SELECT id,mid,info,isread,isreply,companyid,msgtype,createtime FROM tp_member_wechat_24hourmessage ORDER BY id DESC) AS 24hm LEFT JOIN tp_member_wechat_info AS wechat ON wechat.mid = 24hm.mid WHERE ( 24hm.companyid = '".$this->companyid."' ) AND ( 24hm.msgtype = '2' ) AND ( wechat.wechatmessageisreply = '1' ) GROUP BY 24hm.mid ORDER BY 24hm.id DESC LIMIT ".$page->firstRow.",".$page->listRows);
		if($list){
			foreach($list as $Key=>$Val){
				$country = M('area')->where(array('id'=>$Val['country']))->getField('name');
				$city = M('area')->where(array('id'=>$Val['city']))->getField('name');
				$list[$Key]['address'] = $country.$city;
				$list[$Key]['individualCount'] = M('member_wechat_24hourmessage')->where(array('companyid'=>$this->companyid,'mid'=>$Val['mid']))->count();
			}
		}
		$this->assign('list',$list);
		$this->assign('count',$count);
		$this->assign('page',$page->show());
		$this->display();
	}
}
?>