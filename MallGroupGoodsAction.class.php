<?php
/**
 * 拼团商品
 * 
 * @author    Thomas<416369046@qq.com>
 * @since     2016-12-29
 * @version   1.0
 */
class MallGroupGoodsAction extends WapBaseAction{
	
	private $mid;
	
	private $companyid;
	
	public function __construct(){
		parent::__construct();
		$this->mid = session('mid'.session('wapcid'));
		$this->companyid = session('wapcid');
	}
	/**
	 * 开团成功
	 * @author Thomas<416369046@qq.com>
	 * @since  2016-12-29
	 */
	public function index(){
		$time = time();
		//获取openid(用于发送提醒的消息模板)
		if(!session('openid'.$this->companyid)){
			$this->NoSenseAccredit();
		}
		$sendOpenid = session('openid'.$this->companyid);
		$groupinfoid = $this->_request('groupinfoid');
		$isfirstCount = M('mall_groupon_info_member')->where(array('companyid'=>$this->companyid,'groupinfoid'=>$groupinfoid))->count();
		$this->assign('isfirstCount',$isfirstCount);
		$groupMemberInfoCount = M('mall_groupon_info_member')->where(array('companyid'=>$this->companyid,'groupinfoid'=>$groupinfoid,'openid'=>$sendOpenid))->count();
		if($groupMemberInfoCount){
			//表明对这个已参团
			$join = 1;	
		}else{
			//表明要参团
			$jion = 2;
		}
		$this->assign('join',$join);
		//获取开团的团长信息 以及相关团员结构信息
		$groupInfo = M('mall_groupon_info')->where(array('companyid'=>$this->companyid,'id'=>$groupinfoid))->field('id,groupid,goodid,grouporderid,goodskuid,groupstatus,groupnum,joingroupnum,createtime')->find();
		//获取商品的详细信息
		$goodsInfo = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$groupInfo['goodid']))->field('id,salenum,companyid,goodtype,isoffshelves,issoldout,title,pricetype,originalprice,saleprice,grouponprice,isopenvipprice,intprice,vouchertype,voucherimgurl,vouchersid,stockamount,canbuynum,goodnum,info,shareimg,sharefriendstitle,sharedes,usetimelimittype,usetimelimitset,useshopslimitset,backorderpolicyset,useinfo')->find();
		//商品图片以及SKU
		if($goodsInfo['goodtype'] == 1 || $goodsInfo['goodtype'] == 3 || $goodsInfo['goodtype'] == 4 || $goodsInfo['goodtype'] == 5 || $goodsInfo['goodtype'] == 6 || $goodsInfo['goodtype'] == 7){
			$goodsInfo['pic'] = M('mall_goods_pics')->where(array('companyid'=>$this->companyid,'goodid'=>$groupInfo['goodid']))->field('pic')->order('sort,createtime DESC')->find();
			if($goodsInfo['goodtype'] == 1 || $goodsInfo['goodtype'] == 3 || $goodsInfo['goodtype'] == 4 || $goodsInfo['goodtype'] == 5){
				$goodsInfo['sku'] = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$groupInfo['goodid'],'id'=>$groupInfo['goodskuid']))->field('id,name,originalprice,saleprice,intprice,stockamount,imgurl')->find();
				$goodsInfo['stockamount'] = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$groupInfo['goodid'],'id'=>$groupInfo['goodskuid']))->getField('stockamount');
				$goodsInfo['saleprice'] = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$groupInfo['goodid'],'id'=>$groupInfo['goodskuid']))->min('saleprice');
				$goodsInfo['grouponprice'] = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$groupInfo['goodid'],'id'=>$groupInfo['goodskuid']))->min('grouponprice');
			}else{
				$goodsInfo['grouponprice'] = $goodsInfo['grouponprice'];
			}
			if(!$goodsInfo['shareimg']){
				$goodsInfo['shareimg'] = $goodsInfo['pic'][0]['pic']; //分享图片
			}
			//商品图片
			if($goodsInfo['goodtype'] == 1){
				$goodsInfo['goodpic'] = $goodsInfo['sku']['imgurl'];
			}else{
				$goodsInfo['goodpic'] = $goodsInfo['pic']['pic'];
			}
		}else{
			$goodsInfo['grouponprice'] = $goodsInfo['grouponprice'];
			$goodsInfo['goodpic'] = $goodsInfo['voucherimgurl'];
		}
		$groupMemberInfo = M('mall_groupon_info_member')->where(array('companyid'=>$this->companyid,'groupinfoid'=>$groupinfoid,'ispay'=>1))->field('id,mid,groupid,groupinfoid,orderid,nickname,headimgurl,base64nickname,isleader,createtime')->order('isleader asc,createtime asc')->select();
		//获取拼团活动得有效期时间
		$groupActivityInfo = M('mall_groupon_activity')->where(array('companyid'=>$this->companyid,'id'=>$groupInfo['groupid']))->field('goodid,goodtype,limitbuy,limitnum,groupnum,qrcode,status,starttime,endtime')->find();
		//查询此用户是否关注公众号
		//获取此用户的关注状态
		$wechats = M('wechats')->where(array('companyid'=>$this->companyid))->field('appid,appsecret,wxname,weixin')->find();
		$wechatOptions = array('appid'=>$wechats['appid'],'appsecret'=>$wechats['appsecret']);
		$wechat  = new Wechat($wechatOptions);
		$userInfoSub = $wechat->getUserInfo($sendOpenid);
		
		$this->assign('userInfoSub',1);//$userInfoSub['subscribe']
		$wechatInfo = M('member_wechat_info')->where(array('companyid'=>$this->companyid,'openid'=>$sendOpenid))->field('nickname')->find();
		$this->assign('groupinfoid',$groupinfoid);
		$this->assign('groupInfo',$groupInfo);
		$this->assign('goodsInfo',$goodsInfo);
		$this->assign('groupMemberInfo',$groupMemberInfo);
		$this->assign('groupActivityInfo',$groupActivityInfo);
		
		if($groupInfo['groupstatus'] == 3){
			$this->setPageTitle(array('title'=>'组团失败'));
			//分享的信息
			$info['sharefriendstitle'] = $wechatInfo['nickname'].'花了'.$goodsInfo['grouponprice'].'元拼了'.$goodsInfo['title'];   // 分享标题
			$info['sharedes'] = get_text($goodsInfo['info']);  																			// 分享描述
			$info['shareurl'] = C('site_url').U('MallGroupGoods/index',array('companyid'=>$this->companyid,'groupinfoid'=>$groupinfoid));
			$info['shareimg'] =  C('site_url').$goodsInfo['goodpic'];
			$this->assign('info',$info);
			$this->display('groupdefault');
		}else{
			if($groupActivityInfo['starttime'] < $time && $groupActivityInfo['endtime'] > $time){
				if($groupInfo['groupnum'] == $groupInfo['joingroupnum']){
					$this->setPageTitle(array('title'=>'组团成功'));
					//分享的信息
					$info['sharefriendstitle'] = $wechatInfo['nickname'].'花了'.$goodsInfo['grouponprice'].'元拼了'.$goodsInfo['title'];   // 分享标题
					$info['sharedes'] = $wechatInfo['nickname'].'刚刚成功拼团，你也快来开个团吧'; // 分享描述
					$info['shareurl'] = C('site_url').U('MallGroupGoods/index',array('companyid'=>$this->companyid,'groupinfoid'=>$groupinfoid));
					$info['shareimg'] = C('site_url').$goodsInfo['goodpic'];
					$this->assign('info',$info);
					$this->display('groupsuccess');
				}else{
					$this->setPageTitle(array('title'=>'开团成功'));
					//分享的信息
					$info['sharefriendstitle'] = $wechatInfo['nickname'].'花了'.$goodsInfo['grouponprice'].'元拼了'.$goodsInfo['title'];   // 分享标题
					$info['sharedes'] = get_text($goodsInfo['info']);  // 分享描述
					$info['shareurl'] = C('site_url').U('MallGroupGoods/index',array('companyid'=>$this->companyid,'groupinfoid'=>$groupinfoid));
					$info['shareimg'] =  C('site_url').$goodsInfo['goodpic'];
					$this->assign('info',$info);
					$this->display('index');
				}
			}else{
				if($groupInfo['groupstatus'] == 2){
					$this->setPageTitle(array('title'=>'组团成功'));
					//分享的信息
					$info['sharefriendstitle'] = $wechatInfo['nickname'].'花了'.$goodsInfo['grouponprice'].'元拼了'.$goodsInfo['title'];   // 分享标题
					$info['sharedes'] = $wechatInfo['nickname'].'刚刚成功拼团，你也快来开个团吧'; // 分享描述
					$info['shareurl'] = C('site_url').U('MallGroupGoods/index',array('companyid'=>$this->companyid,'groupinfoid'=>$groupinfoid));
					$info['shareimg'] = C('site_url').$goodsInfo['goodpic'];
					$this->assign('info',$info);
					$this->display('groupsuccess');
				}else{
					$this->setPageTitle(array('title'=>'组团失败'));
					//分享的信息
					$info['sharefriendstitle'] = $wechatInfo['nickname'].'花了'.$goodsInfo['grouponprice'].'元拼了'.$goodsInfo['title'];   // 分享标题
					$info['sharedes'] = get_text($goodsInfo['info']);  // 分享描述
					$info['shareurl'] = C('site_url').U('MallGroupGoods/index',array('companyid'=>$this->companyid,'groupinfoid'=>$groupinfoid));
					$info['shareimg'] =  C('site_url').$goodsInfo['goodpic'];
					$this->assign('info',$info);
					$this->display('groupdefault');
				}
			}
		}
	}
	/**
	 * 【页面定时任务】
	 * 当停留页面超时30分钟，倒计时为0
	 * 执行这个退款方法
	 * @author Thomas<416369046@qq.com>
	 * @since  2017-1-11
	 */
	public function ajaxGroupDefault(){
		$groupid = $this->_post('groupid');
		$time = time();
		$where['ginfo.companyid'] = $this->companyid;
		$where['ginfo.id'] = $groupid;					
		$groupInfoList = M()->table('tp_mall_groupon_info as ginfo')->join(array("LEFT JOIN tp_mall_groupon_info_member as ginfom ON ginfom.groupinfoid = ginfo.id","LEFT JOIN tp_mall_order_info as oinfo ON oinfo.orderid = ginfom.orderid"))->where($where)->field('ginfo.id,oinfo.mid,oinfo.orderid,oinfo.companyid,oinfo.orderpaymethod,oinfo.ordertitle')->select();
		if($groupInfoList){
			foreach($groupInfoList as $gkey=>$gVal){
				//发送消息模板的openid
				$openid = M('member_wechat_info')->where(array('companyid'=>$gVal['companyid'],'mid'=>$gVal['mid']))->getField('openid');
				//未退款的订单发送消息模板【退款中已发送过消息模板所以不再发送】
				if($gVal['isrefund'] == 2){
					//发送组团失败的消息模板
					$this->WeChatTemplateMessageSend(46, $openid,$gVal['companyid'],'','',array('组团失败','Eshop微商城'),array(htmlspecialchars_decode($gVal['ordertitle'])));
				}
				// 将拼团状态改为组团失败
				$groupInfoData['groupstatus'] = 3;
				$groupInfoData['updatetime'] = $time;
				$groupInfoReturn = M('mall_groupon_info')->where(array('id'=>$gVal['id']))->save($groupInfoData);
				//获取支付方式；1：微信支付 ； 7： 储值支付；
				if($gVal['orderpaymethod'] == 1){
					//请求微信退款的方法进行申请退款
					http_get(C('site_url').'/Payapi/Wxpay/payAct/orderRefund.php?orderid='.$gVal['orderid']);
				}elseif($gVal['orderpaymethod'] == 7){
					//根据订单的详细信息进行退款  退款状态改为退款中
					$refundData['isrefund'] = 3;
					$refundData['updatetime'] = $time;
					$orderRefundReturn = M('mall_order_info')->where(array('orderid'=>$gVal['orderid']))->save($refundData);
					//【调用共用方法】退款成功后记录消费+退款+不退积分
					//获取spending表中borderid
					$borderid = M('member_spending')->where(array('companyid'=>$gVal['companyid'],'linkorderid'=>$gVal['orderid']))->getField('borderid');
					$option['cid'] = $gVal['companyid'];
					$option['type'] = '110';
					$option['borderid'] = $borderid;
					$memberResult = $this->changeMemberBusinessSCRM5($option);
					if($memberResult){
						//根据订单的详细信息进行退款 并且将订单状态改为过期退状态 退款状态改为已退款
						$orderData['orderstatus'] = 8;
						$orderData['isrefund'] = 1;
						$orderData['updatetime'] = $orderData['returntime'] = $time;
						$orderInfoReturn = M('mall_order_info')->where(array('orderid'=>$gVal['orderid']))->save($orderData);
						if(!$orderInfoReturn){
							$data['id'] = guidNow();
							$data['companyid'] = $gVal['companyid'];
							$data['log'] = date('Y-m-d H:i:s',time).'拼团商品储值订单退款修改订单状态失败';
							$data['createtime'] = time();
							$refundResult = M('log_refund')->add($data);
						}
					}else{
						$data['id'] = guidNow();
						$data['companyid'] = $gVal['companyid'];
						$data['log'] = date('Y-m-d H:i:s',time).'拼团商品交易记录失败';
						$data['createtime'] = time();
						$refundResult = M('log_refund')->add($data);
					}
				}
			}
		}
	}
	/**
	 * 拼团商品的微信退款记录交易 (不退积分哦)
	 * @author Thomas<416369046@qq.com>
	 * @since  2017-1-12
	 */
	/* public function wechatRefundGroupGoods(){
		$time = time();
		$orderid = $this->_request('orderid');
		$orderInfo = M('mall_order_info')->where(array('orderid'=>$orderid,'isrefund'=>3))->field('id,companyid,mid,orderid,out_trade_no,orderprice,orderstatus,orderpaymethod,isrefund')->find();
		if($orderInfo){
			//获取spending表中borderid
			$borderid = M('member_spending')->where(array('companyid'=>$orderInfo['companyid'],'linkorderid'=>$orderInfo['orderid']))->getField('borderid');
			// 退款成功后记录消费+退款+不退积分
			$option['cid'] = $orderInfo['companyid'];
			$option['type'] = '110';
			$option['borderid'] = $borderid;
			$memberResult = $this->changeMemberBusinessSCRM5($option);
			if($memberResult){
				//根据订单的详细信息进行退款 并且将订单状态改为过期退状态 退款状态改为已退款
				$orderData['orderstatus'] = 8;
				$orderData['isrefund'] = 1;
				$orderData['updatetime'] = $orderData['returntime'] = $time;
				$orderInfoReturn = M('mall_order_info')->where(array('orderid'=>$orderid))->save($orderData);
				if(!$orderInfoReturn){
					$data['id'] = guidNow();
					$data['companyid'] = $orderInfo['companyid'];
					$data['log'] = date('Y-m-d H:i:s',time).'支付订单状态修改失败';
					$data['createtime'] = time();
					$refundResult = M('log_refund')->add($data);
				}
			}
		}
	} */
}
?>