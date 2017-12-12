<?php
/**
 * 代理经销用户
 */
class MallExhibitionPartnerAction extends WapBaseAction{
	
	private $mid;
	
	private $companyid;
	
	public function __construct(){
		parent::__construct();
		$this->mid = session('mid'.session('wapcid'));
		$this->companyid = session('wapcid');
	}
	/**
	 * 代理经销用户
	 */
	public function index(){
		if($this->mid){
			// 获取用户的类型
			$info = M('member_register_info')->where(array('id'=>$this->mid))->field('id,salestype')->find();
			if($info['salestype'] == 1){
				$title = '代理用户';
			}elseif($info['salestype'] == 2){
				$title = '经销用户';
			}elseif($info['salestype'] == 3){
				$title = '申请成为营销伙伴';
			}
			$this->assign('info',$info);
			$this->setPageTitle(array('title'=>$title));
		}else{
			session('historyUrl','http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]);// 用于登陆后跳回本页面
			$this->checkMemberAutoLogin();// 检测是否登录弹框
		}
		// 接受代理分享的openid
		$partneropenid = $this->_get('partneropenid');
		//dump($partneropenid);
		$this->assign('partneropenid',$partneropenid);
		$this->display();
	}
	/**
	 * 申请成为经销/区域代理
	 */
	public function ajaxApply(){
		$time = time();
		$type = $this->_get('type'); // 申请类型：1：区域代理；2：经销用户；
		if($type == 1){
			$applying = M('mall_exhibition_partner_list')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'applytype'=>$type,'status'=>1))->count();
			if(!$applying){
				$partneropenid = $this->_post('partneropenid');
				if($partneropenid){
					// 给分享的这个代理绑定当前登录用的mid
					$bdata['zpartnermid'] = $this->mid;
					$bdata['updatetime'] = $time;
					$bdata['bangtime'] = $time;
					$memResult = M('member_wechat_info')->where(array('openid'=>$partneropenid))->save($bdata);
				}
				$province = $this->_post('province');
				$city = $this->_post('city');
				$area = $this->_post('area');
				$town = $this->_post('town');
				$data['province'] = $province;
				$data['city'] = $city;
				$data['area'] = $area;
				$data['town'] = $town;
				$data['name'] = $this->_post('name');
				$data['mobile'] = $this->_post('mobile');
				$data['address'] = $province.$city.$area.$town;
				$data['applytype'] = $type;
				$data['status'] = 1;
				$data['id'] = guidNow();
				$data['companyid'] = $this->companyid;
				$data['mid'] = $this->mid;
				$data['createtime'] = $data['updatetime'] = $time;
				$result = M('mall_exhibition_partner_list')->add($data);
				$data2['province'] = $province;
				$data2['city'] = $city;
				$data2['area'] = $area;
				$data2['town'] = $town;
				if($data2){
					$data2['companyid'] = $this->companyid;
					$data2['mid'] = $this->mid;
					$data2['id'] =  guidNow();
					$data2['createtime'] = $data2['updatetime'] = $time;
					$result2 = M('mall_exhibition_partner_areamanage')->add($data2);
				}
				if($result){
					$return['code'] = 200;
					$return['status'] = $data['status'];
					$return['tips'] = '申请成功';
				}else{
					$return['code'] = 300;
					$return['tips'] = '申请失败';
				}
			}else{
				$return['code'] = 300;
				$return['tips'] = '您已经提交申请，请勿重复提交';
			}
		}elseif($type == 2){
			$applying = M('mall_dms_list')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'applytype'=>$type,'status'=>1))->count();
			if(!$applying){
				$data['applytype'] = $type;
				$data['status'] = 1;
				$data['id'] = guidNow();
				$data['companyid'] = $this->companyid;
				$data['mid'] = $this->mid;
				$data['createtime'] = $data['updatetime'] = $time;
				$result = M('mall_dms_list')->add($data);
				if($result){
					$return['code'] = 200;
					$return['status'] = $data['status'];
					$return['tips'] = '申请成功';
				}else{
					$return['code'] = 300;
					$return['tips'] = '申请失败';
				}
			}else{
				$return['code'] = 300;
				$return['tips'] = '您已经提交申请，请勿重复提交';
			}
		}
		echo json_encode($return);
	} 
	/**
	 * 申请代理/申请经销
	 */
	public function apply(){
		$applyType = $this->_get('applyType');
		if($applyType == 1){
			$partneropenid = $this->_get('partneropenid');
			$this->assign('partneropenid',$partneropenid);
			$title = '申请代理';
			$baseInfo = M('mall_exhibition_partner_base')->where(array('companyid'=>$this->companyid))->field('id,recruitplan')->find();
		}elseif($applyType == 2){
			$title = '申请经销';
			$baseInfo = M('mall_dms_base')->where(array('companyid'=>$this->companyid))->field('id,distriplan')->find();
		}
		$this->assign('applyType',$applyType);
		$this->setPageTitle(array('title'=>$title));
		$this->assign('baseInfo',$baseInfo);
		$this->display();
	}
	/**
	 * 代理用户
	 */
	public function partnerInfo(){
		$this->setPageTitle(array('title'=>'代理用户'));
		$baseInfo = M('mall_exhibition_partner_base')->where(array('companyid'=>$this->companyid))->field('id,recruitplan')->find();
		$this->assign('baseInfo',$baseInfo);
		$this->display();
	}
	/**
	 * 查看代理用户
	 */
	public function partnerCheck(){
		$this->setPageTitle(array('title'=>'查看代理用户'));
		$info = M('mall_exhibition_partner_list')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid))->find();
		$this->assign('info',$info);
		$addressList = M('mall_exhibition_partner_areamanage')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid))->select();
		$this->assign('addressList',$addressList);
		$this->display();
	}
	/**
	 * 代理协议
	 */
	public function agencyAgreement(){
		$this->setPageTitle(array('title'=>'代理协议'));
		$baseInfo = M('mall_exhibition_partner_base')->where(array('companyid'=>$this->companyid))->field('id,recruitplan')->find();
		$this->assign('baseInfo',$baseInfo);
		$this->display();
	}
	/**
	 * 专属销售链接
	 */
	public function salesLink(){
		$this->setPageTitle(array('title'=>'专属销售链接'));
		$goodList = M('mall_goods')->where(array('companyid'=>$this->companyid))->field('id,salenum,companyid,goodtype,isoffshelves,issoldout,title,pricetype,originalprice,saleprice,grouponprice,isopenvipprice,intprice,stockamount,canbuynum,goodnum,info,shareimg,sharefriendstitle,sharedes,freighttype,freighttplid')->select();
		foreach($goodList as $key=>$val){
			//商品图片以及SKU
			$goodList[$key]['pic'] = M('mall_goods_pics')->where(array('goodid'=>$val['id']))->order('sort,createtime DESC')->getfield('pic');
			$goodList[$key]['totalstock'] = M('mall_goods_sku')->where(array('goodid'=>$val['id']))->sum('stockamount');
			$goodList[$key]['saleprice'] = M('mall_goods_sku')->where(array('goodid'=>$val['id']))->min('saleprice');
		}
		$this->assign('goodList',$goodList);
		$this->display();
	}
	/**
	 * 专属销售链接详情
	 */
	public function salesLinkInfo(){
		$this->setPageTitle(array('title'=>'专属销售链接详情'));
		$id = $this->_get('id');
		$goodInfo = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$id))->field('id,salenum,companyid,goodtype,isoffshelves,issoldout,title,pricetype,originalprice,saleprice,grouponprice,isopenvipprice,intprice,stockamount,canbuynum,goodnum,info,shareimg,sharefriendstitle,sharedes,freighttype,freighttplid')->find();
		//商品图片以及SKU
		$goodInfo['pic'] = M('mall_goods_pics')->where(array('goodid'=>$goodInfo['id']))->field('pic')->order('sort,createtime DESC')->select();
		$goodInfo['totalstock'] = M('mall_goods_sku')->where(array('goodid'=>$goodInfo['id']))->sum('stockamount');
		$goodInfo['saleprice'] = M('mall_goods_sku')->where(array('goodid'=>$goodInfo['id']))->min('saleprice');
		$this->assign('goodInfo',$goodInfo);
		// 用户静默授权获取openid
		if(!session('openid'.session('wapcid'))){
			$this->checkMemberAutoLogin();
		}
		if(!$info['sharefriendstitle']){
			$info['sharefriendstitle'] = $goodInfo['title'];   // 分享标题
		}
		if(!$info['sharedes']){
			$info['sharedes'] = get_substr(get_text($goodInfo['info']), 100);  // 分享描述
		}
		if($info['shareimg']==''){
			$info['shareimg'] = 'http://www.mobiwind.cn/Tpl/Wap/default/common/img/common.jpg';
		}else{
			if(strpos($info['shareimg'], C('site_url')) === false){
				$info['shareimg'] = C('site_url').$goodInfo['shareimg'];
			}
		}
		// 分享链接
		$info['shareurl']= C('site_url').U('MallGoods/goodInfo',array('companyid'=>$this->companyid,'id'=>$id,'shareopenid'=>session('openid'.session('wapcid'))));
		$this->assign('info',$info);
		$this->display();
	}
	/**
	 * 专属销售海报
	 */
	public function salesLinkPoster(){
		$this->setPageTitle(array('title'=>'专属销售海报'));
		// 获取用户的类型
		$info = M('member_register_info')->where(array('id'=>$this->mid))->field('id,salestype')->find();
		// 获取专属销售链接
		if($info['salestype'] == 1){
			$baseInfo = M('mall_exhibition_partner_base')->where(array('companyid'=>$this->companyid))->field('id,invitationlink')->find();
		}elseif($info['salestype'] == 2){
			$baseInfo = M('mall_dms_base')->where(array('companyid'=>$this->companyid))->field('id,invitationlink')->find();
		}
		//$goodInfo = M('mall_goods')->where(array('companyid'=>$this->companyid))->field('id')->order('id DESC')->find();
		//$shareLink = C('site_url').U('MallGoods/goodInfo',array('companyid'=>$this->companyid,'id'=>$goodInfo['id'],'shareopenid'=>session('openid'.session('wapcid'))));
		$shareLink = htmlspecialchars_decode($baseInfo['invitationlink']).'&shareopenid='.session('openid'.session('wapcid'));
		$this->assign('shareLink',$shareLink);
		$this->display();
	}
	/**
	 * 我的销售订单
	 */
	public function mySalesOrder(){
		$this->setPageTitle(array('title'=>'我的销售订单'));
		// 获取用户的类型
		$info = M('member_register_info')->where(array('id'=>$this->mid))->field('id,salestype')->find();
		$this->assign('info',$info);
		$this->display();
	}
	/**
	 * 我的订单类型
	 */
	public function mySalesOrderType(){
		$type = $this->_get('type');
		if($type == 1){
			$title = '我的直销订单';
		}elseif($type == 2){
			$title = '我的经销订单';
		}elseif($type == 3){
			$title = '我的区域订单';
		}
		$this->assign('type',$type);
		$this->setPageTitle(array('title'=>$title));
		$this->display();
	}
	/**
	 * 我的订单列表
	 */
	public function mySalesOrderList(){
		// 获取用户的类型
		$info = M('member_register_info')->where(array('id'=>$this->mid))->field('id,salestype')->find();
		$this->assign('info',$info);
		$type = $this->_get('type');
		$orderstatus = $this->_get('orderstatus');
		if($orderstatus == 9){
			$title = '已完成订单';
			$where['oi.orderstatus'] = 4;
		}elseif($orderstatus == 8){
			$title = '未完成订单';
			$where['oi.orderstatus'] = array('in','1,2,3');
		}elseif($orderstatus == 10){
			$title = '疑难订单';
			$where['oi.orderstatus'] = 10;
		}
		$this->setPageTitle(array('title'=>$title));
		if($info['salestype'] == 1){
			$where['meorder.type'] = $type;
			$where['meorder.mid'] = $this->mid;
			//$where['oi.orderstatus'] = $orderstatus;
			$orderList = M()->table("tp_mall_order_info oi")
                ->join("tp_mall_order_goods og on oi.orderid=og.orderid")
                ->join("tp_mall_goods_sku gs on gs.id=og.goodskuid")
				->join("tp_mall_exhibition_partner_order as meorder ON meorder.orderid = oi.orderid")
                ->where($where)->field("oi.id,oi.orderid,oi.orderstatus,og.goodname,og.goodpic,og.goodid,og.goodprice,og.goodnum,gs.name,gs.saleprice,oi.consigneename,oi.consigneephone,oi.consigneeaddress")->select();
		}else{
			$where['dorder.mid'] = $this->mid;
			//$where['oi.orderstatus'] = $orderstatus;
			$orderList = M()->table("tp_mall_order_info oi")
                ->join("tp_mall_order_goods og on oi.orderid=og.orderid")
                ->join("tp_mall_goods_sku gs on gs.id=og.goodskuid")
				->join("tp_mall_dms_order as dorder ON dorder.orderid = oi.orderid")
                ->where($where)->field("oi.id,oi.orderid,oi.orderstatus,og.goodname,og.goodpic,og.goodid,og.goodprice,og.goodnum,gs.name,gs.saleprice,oi.consigneename,oi.consigneephone,oi.consigneeaddress")->select();
		}
		$this->assign("orderstatus",$orderstatus);
		$this->assign('orderList',$orderList);
		//dump($orderList);
		$this->display();
	}
	/**
	 * 返利统计
	 */
	public function myExhibitionOrder(){
		$time = time();
		$this->setPageTitle(array('title'=>'返利统计'));
		// 获取用户的类型
		$info = M('member_register_info')->where(array('id'=>$this->mid))->field('id,salestype')->find();
		$this->assign('info',$info);
		if($info['salestype'] == 1){
			// 代理
			// 经销
			$where['dorder.companyid'] = $this->companyid;
			$where['dorder.orderstatus'] = 4;
			$where['dorder.mid'] = $this->mid;
			$where['bill.billtype'] = array('in','1,4');
			$list = M()->table('tp_mall_exhibition_partner_order as dorder')->join(array("LEFT JOIN tp_mall_exhibition_partner_bill as bill ON bill.orderid = dorder.orderid"))->where($where)->field('dorder.id,dorder.orderid,dorder.orderprice,dorder.commission,dorder.createtime,bill.id as bid')->select();
			foreach($list as $key=>$val){
				$list[$key]['ordermoney'] = $val['orderprice'];
				$list[$key]['wagesmoney'] = $val['commission'];
			}
			$this->assign('list',$list);
			// 查询提现总表
			$totalList = M('mall_exhibition_partner_bill')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'billtype'=>array('in','1,2,3,4')))->select();
			$totalMoney = 0;
			foreach($totalList as $key=>$val){
				$totalMoney += $val['wages'];
			}
			$this->assign('totalMoney',format_number($totalMoney,2));
			// 查询提现总表
			$billList = M('mall_exhibition_partner_bill')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'billtype'=>3))->select();
			$withTotal = 0;
			foreach($billList as $bKey=>$bVal) {
				$withTotal += $bVal['wages'];
			}
			$this->assign('withTotal',format_number($withTotal,2));
			// 剩余提现总数
			$leaveTotal = $totalMoney - $withTotal;
			$this->assign('leaveTotal',format_number($leaveTotal,2));
		}elseif($info['salestype'] == 2){
			// 经销
			$where['dorder.companyid'] = $this->companyid;
			$where['dorder.orderstatus'] = 4;
			$where['dorder.mid'] = $this->mid;
			$where['bill.billtype'] = array('in','1,4');
			$list = M()->table('tp_mall_dms_order as dorder')->join(array("LEFT JOIN tp_mall_dms_bill as bill ON bill.orderid = dorder.orderid"))->where($where)->field('dorder.id,dorder.orderid,dorder.ordermoney,dorder.wagesmoney,dorder.createtime,bill.id as bid')->select();
			$this->assign('list',$list);
			// 查询提现总表
			$totalList = M('mall_dms_bill')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'billtype'=>array('in','1,2,3,4')))->select();
			$totalMoney = 0;
			foreach($totalList as $key=>$val){
				$totalMoney += $val['wages'];
			}
			$this->assign('totalMoney',format_number($totalMoney,2));
			// 查询提现总表
			$billList = M('mall_dms_bill')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'billtype'=>3))->select();
			$withTotal = 0;
			foreach($billList as $bKey=>$bVal) {
				$withTotal += $bVal['wages'];
			}
			$this->assign('withTotal',format_number($withTotal,2)); 
			// 剩余提现总数
			$leaveTotal = $totalMoney - $withTotal;
			$this->assign('leaveTotal',format_number($leaveTotal,2));
		}
		$this->display();
	}
	/**
	 * 异步确认提现
	 */
	public function ajaxSubmitWithDrawCash(){
		$time = time();
		// 获取用户的类型
		$info = M('member_register_info')->where(array('id'=>$this->mid))->field('id,salestype')->find();
		$this->assign('info',$info);
		if($info['salestype'] == 1){
			$id = $this->_post('id');
			$where['companyid'] = $this->companyid;
			$where['mid'] = $this->mid;
			$where['id'] = array('in',$id);
			// 可提现的佣金
			$list = M('mall_exhibition_partner_bill')->where($where)->field('id,orderid,wages')->select();
			foreach($list as $key=>$val){
				$data['id'] = $wcashid = guidNow();
				$data['companyid'] = $this->companyid;
				$data['mid'] =$this->mid;
				$data['orderid'] = $val['orderid'];
				$data['withdrawcash'] = $val['wages'];
				$data['state'] = 1;
				$data['createtime'] = $data['updatetime'] = $data['applytime'] = $time;
				$result = M('mall_exhibition_partner_withdrawcash')->add($data);
			
				$bdata['billtype'] = 2;  //申请提现;
				$bdata['applyforid'] = $wcashid;
				$bdata['updatetime'] = $time;
				$bresult = M('mall_exhibition_partner_bill')->where(array('id'=>$val['id']))->save($bdata);
				// 将可提现的佣金减去
				$availableData['availablemoney'] =  array('exp', '`availablemoney`-'.$val['wages']);
				$availableData['updatetime'] = $time;
				$availableReturn = M('mall_exhibition_partner_list')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid))->save($availableData);
			}
			if($result && $bresult){
				$return['code'] = 200;
			}else{
				$return['code'] = 300;
			}
		}elseif($info['salestype'] == 2){
			$id = $this->_post('id');
			$where['companyid'] = $this->companyid;
			$where['mid'] = $this->mid;
			$where['id'] = array('in',$id);
			// 可提现的佣金
			$list = M('mall_dms_bill')->where($where)->field('id,orderid,wages')->select();
			foreach($list as $key=>$val){
				$data['id'] = $wcashid = guidNow();
				$data['companyid'] = $this->companyid;
				$data['mid'] =$this->mid;
				$data['orderid'] = $val['orderid'];
				$data['withdrawcash'] = $val['wages'];
				$data['state'] = 1;
				$data['createtime'] = $data['updatetime'] = $data['applytime'] = $time;
				$result = M('mall_dms_withdrawcash')->add($data);
			
				$bdata['billtype'] = 2;  //申请提现;
				$bdata['applyforid'] = $wcashid;
				$bdata['updatetime'] = $time;
				$bresult = M('mall_dms_bill')->where(array('id'=>$val['id']))->save($bdata);
				// 将可提现的佣金减去
				$availableData['availablemoney'] =  array('exp', '`availablemoney`-'.$val['wages']);
				$availableData['updatetime'] = $time;
				$availableReturn = M('mall_dms_list')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid))->save($availableData);
			}
			if($result && $bresult){
				$return['code'] = 200;
			}else{
				$return['code'] = 300;
			}
		}
		echo json_encode($return);
	}
	/**
	 * 我的提现记录
	 */
	public function myExhibitionWithDrawCashList(){
		$this->setPageTitle(array('title'=>'我的提现'));
		$limit = 15;
		// 获取用户的类型
		$info = M('member_register_info')->where(array('id'=>$this->mid))->field('id,salestype')->find();
		$this->assign('info',$info);
		if($info['salestype'] == 1){
			// 提现申请
			$where['companyid'] = $this->companyid;
			$where['mid'] = $this->mid;
			$where['billtype'] = 3;
			$count = M('mall_exhibition_partner_bill')->where($where)->count();
			$withDrawCashList = M('mall_exhibition_partner_bill')->where($where)->field('id,billtype,wages,createtime')->order('createtime DESC')->limit($limit)->select();
			$this->assign('withDrawCashList',$withDrawCashList);
			$this->assign('count',$count);
		}elseif($info['salestype'] == 2){
			// 提现申请
			$where['companyid'] = $this->companyid;
			$where['mid'] = $this->mid;
			$where['billtype'] = 3;
			$count = M('mall_dms_bill')->where($where)->count();
			$withDrawCashList = M('mall_dms_bill')->where($where)->field('id,billtype,wages,createtime')->order('createtime DESC')->limit($limit)->select();
			//dump($withDrawCashList);
			$this->assign('withDrawCashList',$withDrawCashList);
			$this->assign('count',$count);
		}
		$this->display();
	}
	/**
	 * 渠道招募海报
	 */
	public function mySellPoster(){
		$this->setPageTitle(array('title'=>'渠道招募海报'));
		// 渠道招募海报链接
		$salesPoster = C('site_url').U('Wap/MallExhibitionPartner/index',array('companyid'=>$this->companyid,'partneropenid'=>session('openid'.session('wapcid'))));
		$this->assign('salesPoster',$salesPoster);
		$this->display(); 
	}
	/**
	 * 我的经销用户
	 */
	public function mySalesCustomer(){
		// 获取的所有经销用户
		$where['wechat.companyid'] = $this->companyid;
		$where['wechat.zpartnermid'] = $this->mid;
		$salesList = M()->table('tp_member_register_info AS register')->join(array('LEFT JOIN tp_member_wechat_info AS wechat ON register.id = wechat.mid'))->where($where)->field('wechat.nickname,register.mobile,wechat.headimgurl,wechat.openid,wechat.bangtime,wechat.mid')->select();
		foreach($salesList as $sKey=>$sVal){
			$salesList[$sKey]['ordernum'] = M('mall_dms_order')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'orderstatus'=>4))->count();
		}
		$this->assign('salesList',$salesList);
		$this->display();
	}
	/**
	 *	经销已完成订单
	 */
	public function completedOrder(){
		$mid = $this->_get('mid');
		$where['dorder.mid'] = $mid;
		$where['oi.orderstatus'] = 4;
		$orderList = M()->table("tp_mall_order_info oi")
			->join("tp_mall_order_goods og on oi.orderid=og.orderid")
			->join("tp_mall_goods_sku gs on gs.id=og.goodskuid")
			->join("tp_mall_dms_order as dorder ON dorder.orderid = oi.orderid")
			->where($where)->field("oi.id,oi.orderid,oi.orderstatus,og.goodname,og.goodpic,og.goodid,og.goodprice,og.goodnum,gs.name,gs.saleprice,oi.consigneename,oi.consigneephone,oi.consigneeaddress")->select();
		//echo M()->getLastSql();
		$this->assign('orderList',$orderList);
		$this->display();
	}
}
?>