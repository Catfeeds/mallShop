<?php
/**
 * SCRM5   只写导出任务
 * 
 * @author    Asa<859333176@qq.com>
 * @since     2016-10-29
 * @version   1.0
 */
class AjaxExportAction extends BaseAction{
	
	public function __construct(){
		parent::__construct();
		ignore_user_abort();
		set_time_limit(0);
		$this->companyid = session('cid');
	}
	/**
	 * 增值-sms短息服务：sms短信推送日志
	 */
	public function ajaxZsms(){
		$info = M("log_sms_send")->where(array("companyid"=>$this->companyid,"sid"=>$this->_request("id"),'smsstate'=>array("in","1,3")))
		->select();
		$option['rule'] = M()->getLastSql();
		$option['type'] = 15;
		$option['name'] = "sms推送失败日志";
		echo json_encode($this->export($option));
	}
	/**
	 * CRM-积分制-积分商城-订单管理：快递配送订单管理+到店领取订单管理
	 */
	public function ajaxCRMzorder(){
	//订单编号
		$orderid = $this->_request('orderid');
		if($orderid){
			$where['morder.orderid'] = array('like','%'.$orderid.'%');
		}
		//商品名称
		$ordertitle = $this->_request('ordertitle');
		if($ordertitle){
			$where['morder.ordertitle'] = array('like','%'.$ordertitle.'%');
		}
		//会员手机
		$mphone = $this->_request('mphone');
		if($mphone){
			$where['register.moblie'] = $mphone;
		}
		//积分
		$orderint1 = $this->_request('orderint1');
		$orderint2 = $this->_request('orderint2');
		if($orderint1 && $orderint2 && $orderint1<=$orderint2){
			$where['morder.orderint'] = array('between',array($orderint1,$orderint2));
		}elseif($orderint1){
			$where['morder.orderint'] = array('egt',$orderint1);
		}elseif($orderint2){
			$where['morder.orderint'] = array('elt',$orderint2);
		}
		//订单提交时间
		$createtime1 = strtotime($this->_request('createtime1'));
		$createtime2 = strtotime($this->_request('createtime2'));
		if($createtime1 && $createtime2 && $createtime1<=$createtime2){
			$where['morder.createtime'] = array('between',array($createtime1,$createtime2));
		}elseif($createtime1){
			$where['morder.createtime'] = array('egt',$createtime1);
		}elseif($createtime2){
			$where['morder.createtime'] = array('elt',$createtime2);
		}
		//订单状态
		$orderstatus = $this->_request('orderstatus');
		if($orderstatus){
			$where['morder.orderstatus'] = $orderstatus;
		}
		$where['morder.companyid'] = $this->companyid;
		$type = $this->_request('type');
		$where['morder.type'] = $type;
		$list = M()->table('tp_mall_member_integral_order_info as morder')->join(array('tp_mall_member_integral_order_goods AS goods ON morder.orderid=goods.orderid','tp_member_register_info AS register ON morder.mid=register.id'))->where($where)->field('morder.id,morder.orderid,morder.borderid,morder.ordertitle,morder.orderstatus,morder.shippingtime,morder.receivaltime,morder.offtime,morder.orderint,morder.consigneename,morder.consigneephone,morder.consigneeaddress,morder.logisticsid,morder.logisticsnum,morder.createtime,register.name,register.moblie')->order('morder.createtime DESC')->select();
		if($list){
			if($type==1){
				$option['type'] = 16;
				$option['name'] = "快递配送订单";
			}else{
				$option['type'] = 17;
				$option['name'] = "到店领取订单";
			}
			$option['rule'] = M()->getLastSql();
			$ajax = $this->export($option);
		}else{
			$ajax['code'] = 300;
			$ajax['msg'] = "暂无数据";
		}
		echo json_encode($ajax);
	}
	/**
	 * CRM-积分制-积分商城-订单管理：虚拟订单管理
	 */
	public function ajaxCRMzorder2(){
		//订单编号
		$orderid = $this->_request('orderid');
		if($orderid){
			$where['morder.orderid'] = array('like','%'.$orderid.'%');
			$this->assign('orderid',$orderid);
		}
		//商品名称
		$ordertitle = $this->_request('ordertitle');
		if($ordertitle){
			$where['morder.ordertitle'] = array('like','%'.$ordertitle.'%');
			$this->assign('ordertitle',$ordertitle);
		}
		//会员手机
		$mphone = $this->_request('mphone');
		if($mphone){
			$where['register.moblie'] = $mphone;
			$this->assign('mphone',$mphone);
		}
		//积分
		$orderint1 = $this->_request('orderint1');
		$orderint2 = $this->_request('orderint2');
		if($orderint1 && $orderint2 && $orderint1<=$orderint2){
			$where['morder.orderint'] = array('between',array($orderint1,$orderint2));
		}elseif($orderint1){
			$where['morder.orderint'] = array('egt',$orderint1);
		}elseif($orderint2){
			$where['morder.orderint'] = array('elt',$orderint2);
		}
		$this->assign('orderint1',$orderint1);
		$this->assign('orderint2',$orderint2);
		//订单提交时间
		$createtime1 = strtotime($this->_request('createtime1'));
		$createtime2 = strtotime($this->_request('createtime2'));
		if($createtime1 && $createtime2 && $createtime1<=$createtime2){
			$where['morder.createtime'] = array('between',array($createtime1,$createtime2));
		}elseif($createtime1){
			$where['morder.createtime'] = array('egt',$createtime1);
		}elseif($createtime2){
			$where['morder.createtime'] = array('elt',$createtime2);
		}
		$this->assign('createtime1',$this->_request('createtime1'));
		$this->assign('createtime2',$this->_request('createtime2'));
		// 是否核销
		$isused = $this->_request('isused');
		if($isused){
			$where['vouchers.isused'] = $isused;
			$this->assign('isused',$isused);
		}
		
		$where['morder.companyid'] = $this->companyid;
		$where['morder.goodtype'] = 2;
		$list = M()->table('tp_mall_member_integral_order_info as morder')->join(array('tp_mall_member_integral_order_goods AS goods ON morder.orderid=goods.orderid','tp_member_register_info AS register ON morder.mid=register.id','tp_member_vouchers AS vouchers ON morder.orderid=vouchers.orderid'))->where($where)->field('morder.id,morder.orderid,morder.orderint,morder.createtime,goods.goodname,register.name,register.moblie,vouchers.sn,vouchers.isused')->order('morder.createtime DESC')->select();
		if($list){
			$option['type'] = 18;
			$option['name'] = "虚拟礼品订单";
			$option['rule'] = M()->getLastSql();
			$ajax = $this->export($option);
		}else{
			$ajax['code'] = 300;
			$ajax['msg'] = "暂无数据";
		}
		echo json_encode($ajax);
	}
	/**
	 * eshop订单
	 */
	public function ajaxCehsoporder(){
		$ordertype = $this->_request('ordertype');
		$where['morder.companyid'] = $this->companyid;
		$where['morder.ordertype'] = $ordertype;
		//订单状态
		$orderstatus = $this->_request('orderstatus');
		if($orderstatus){
			$where['morder.orderstatus'] = $orderstatus;
		}
		//订单编号
		$orderid = $this->_request('orderid');
		if($orderid){
			$where['morder.orderid'] = $orderid;
		}
		//商品名称
		$ordertitle = $this->_request('ordertitle');
		if($ordertitle){
			$where['morder.ordertitle'] = array('like','%'.$ordertitle.'%');
		}
		//购买人手机
		$mphone = $this->_request('mphone');
		if($mphone){
			$where['register.moblie'] = array('like','%'.$mphone.'%');
		}
		//订单金额
		$orderprice1 = $cardvalue = $this->_request('orderprice1');
		$orderprice2 = $this->_request('orderprice2');
		if($orderprice1 && $orderprice2 && $orderprice1 <= $orderprice2){
			$where['morder.orderprice'] = array('between',array($orderprice1,$orderprice2));
		}elseif ($orderprice1){
			$where['morder.orderprice'] = array('egt',$orderprice1);
		}elseif ($orderprice2 > 0){
			$where['morder.orderprice'] = array('elt',$orderprice2);
		}
		//订单提交时间
		$createtime1 = strtotime($this->_request('createtime1'));
		$createtime2 = strtotime($this->_request('createtime2'))-1;
		if($createtime1 && $createtime2 && $createtime1 <= $createtime2){
			$where['morder.createtime'] = array('between',array($createtime1,$createtime2));
		}elseif($createtime1){
			$where['morder.createtime'] = array('egt',$createtime1);
		}elseif($createtime2 > 0){
			$where['morder.createtime'] = array('elt',$createtime2);
		}
		$orderList = M()->table('tp_mall_order_info as morder')->join(array('tp_member_register_info AS register ON morder.mid=register.id'))->where($where)->field('morder.companyid,morder.out_trade_no,morder.orderid,morder.ordertype,morder.truegoodtype,morder.orderstatus,morder.paytime,morder.orderprice,morder.ordersubtotal,morder.consigneename,morder.consigneeaddress,morder.consigneephone,morder.membernote,morder.createtime,register.name,register.moblie')->order('morder.createtime DESC')->select();
		if($orderList){
			if($ordertype == 1) $orderTitle = '实物';
			elseif($ordertype == 2) $orderTitle = '券';
			elseif($ordertype == 3) $orderTitle = '记次卡';
			elseif($ordertype == 4) $orderTitle = '团购';
			elseif($ordertype == 5) $orderTitle = '门票';
			$filename = $orderTitle."订单信息";
			$option['rule'] = M()->getLastSql();
			$option['name'] = $filename;
			if($ordertype == 1){
				$option['type'] = 19;
			}elseif($ordertype == 3){
				$option['type'] = 20;
			}else{
				$option['type'] = 21;
			}
			$ajax = $this->export($option);
		}else{
			$ajax['code'] = 300;
			$ajax['msg'] = "暂无数据";
		}
		echo json_encode($ajax);
	}
	/**
	 * 门店收银历史
	 * 
	 * @author Asa<asa@renlaifeng.cn>
	 * @since  2016-11-28
	 */
	public function ajaxCshoppaylog(){
		// 订单号
		$orderid = $this->_request('orderid');
		if($orderid){
			$where['orderid'] = array('LIKE','%'.$orderid.'%');
		}
		// 消费金额
		$receivables1 = $this->_request('receivables1');
		$receivables2 = $this->_request('receivables2');
		if($receivables1){
			$receivablesWhere[] = array('egt',$receivables1);
		}
		if($receivables2){
			$receivablesWhere[] = array('elt',$receivables2);
		}
		if($receivables1 || $receivables2){
			$where['receivables'] = $receivablesWhere;
		}
		// 实收金额
		$actualamount1 = $this->_request('actualamount1');
		$actualamount2 = $this->_request('actualamount2');
		if($actualamount1){
			$actualamountWhere[] = array('egt',$actualamount1);
		}
		if($actualamount2){
			$actualamountWhere[] = array('elt',$actualamount2);
		}
		if($actualamount1 || $actualamount2){
			$where['actualamount'] = $actualamountWhere;
		}
		// 会员手机号
		$mobile = $this->_request('mobile');
		if($mobile){
			$where['mobile'] = $mobile;
		}
		// 处理人
		$adminname = $this->_request('adminname');
		if($adminname){
			$where['adminname'] = $adminname;
		}
		// 所属门店
		$shopid = $this->_request('shopid');
		if($shopid){
			$where['shopid'] = $shopid;
		}
		// 收银时间
		$collectiontime1 = $this->_request('collectiontime1');
		$collectiontime2 = $this->_request('collectiontime2');
		if($collectiontime1){
			$collectionWhere[] = array('egt',strtotime($collectiontime1.' 00:00:00'));
		}
		if($collectiontime2){
			$collectionWhere[] = array('elt',strtotime($collectiontime2.' 23:59:59'));
		}
		if($collectiontime1 || $collectiontime2){
			$where['collectiontime'] = $collectionWhere;
		}
		$where['companyid'] = $this->companyid;
		$where['orderstate'] = '2';
		// $list = M()->table('tp_shop_cashier AS cashier')->join('tp_company_shops AS shops ON cashier.shopid=shops.id')->where($where)->field('cashier.id,cashier.orderid,cashier.receivables,cashier.actualamount,cashier.mobile,cashier.collectiontime,cashier.collectionendtime,cashier.adminname,cashier.type,shops.shopname,shops.name')->order('cashier.createtime DESC , cashier.id DESC')->select();
		$list = M('shop_cashier')->where($where)->field('id,shopname,orderid,receivables,actualamount,mobile,collectiontime,collectionendtime,adminname,type')->order('createtime DESC , id DESC')->select();
		
		if($list){
			$filename = "门店收银历史";
			$option['rule'] = M()->getLastSql();
			$option['name'] = $filename;
			$option['type'] = 22;
			$ajax = $this->export($option);
		}else{
			$ajax['code'] = 300;
			$ajax['msg'] = "暂无数据";
		}
		echo json_encode($ajax);
	}
	/**
	 * 卡券核销历史
	 * 
	 * @author Asa<asa@renlaifeng.cn>
	 * @since  2016-11-28
	 */
	public function ajaxCvocherslog(){
		$vouchernumber = $this->_request('vouchernumber');   // 券号
		if($vouchernumber){
			$where['vouchernumber'] = array('LIKE','%'.$vouchernumber.'%');
		}
		$mobile = $this->_request('mobile');   // 会员手机号
		if($mobile){
			$where['mobile'] = array('LIKE','%'.$mobile.'%');
		}
		$vouchertype = $this->_request('vouchertype');  // 券类型
		if($vouchertype){
			$where['vouchertype'] = $vouchertype;
		}
		$staffname = $this->_request('staffname');   // 处理人
		if($staffname){
			$where['staffname'] = $staffname;
		}
		$vouchername = $this->_request('vouchername');  // 券名称
		if($vouchername){
			$where['vouchername'] = array('LIKE','%'.$vouchername.'%');
		}
		$shopid = $this->_request('shopid');  // 所属门店
		if($shopid){
			$where['shopid'] = $shopid;
		}
		// 核销时间
		$usetime1 = $this->_request('usetime1');
		$usetime2 = $this->_request('usetime2');
		if($usetime1){
			$usetimeWhere[] = array('egt',strtotime($usetime1.' 00:00:00'));
		}
		if($usetime2){
			$usetimeWhere[] = array('elt',strtotime($usetime2.' 23:59:59'));
		}
		if($usetime1 || $usetime2){
			$where['usetime'] = $usetimeWhere;
		}
		$where['companyid'] = $this->companyid;
		// $list = M()->table('tp_use_vouchers AS vouchers')->join('tp_company_shops AS shops ON vouchers.shopid=shops.id')->where($where)->field('vouchers.id,vouchers.vouchernumber,vouchers.vouchertype,vouchers.vouchername,vouchers.utility,vouchers.mobile,vouchers.usetime,vouchers.staffname,vouchers.shopid,vouchers.type,shops.shopname,shops.name')->order('usetime DESC')->select();
		$list = M('use_vouchers')->where($where)->field('id,shopname,vouchernumber,vouchertype,vouchername,utility,mobile,usetime,staffname,shopid,type')->order('usetime DESC')->select();
		if($list){
			$filename = "卡券核销历史";
			$option['rule'] = M()->getLastSql();
			$option['name'] = $filename;
			$option['type'] = 23;
			$ajax = $this->export($option);
		}else{
			$ajax['code'] = 300;
			$ajax['msg'] = "暂无数据";
		}
		echo json_encode($ajax);
	}
	/**
	 * 闪惠订单
	 * @author Asa<asa@renlaifeng.cn>
	 * @since  2016-11-30
	 */
	public function ajaxCshanhuiorder(){
		$shopid = $this->_get('shopid');
		if($shopid){
			$orderid = $this->_get('orderid');
			if($orderid){
				$where['orderid'] = array('LIKE', '%'.$orderid.'%');
			}
			$name = $this->_get('name');
			if($name){
				$where['rinfo.name'] = array('LIKE', '%'.$name.'%');
			}
			$receivables1 = $this->_get('receivables1');
			if($receivables1){
				$receivablesWhere[] = array('egt',$receivables1);
			}
			$receivables2 = $this->_get('receivables2');
			if($receivables2){
				$receivablesWhere[] = array('elt',$receivables2);
			}
			if($receivables1 || $receivables2){
				$where['receivables'] = $receivablesWhere;
			}
			$mobile = $this->_get('mobile');
			if($mobile){
				$where['rinfo.moblie'] = array('LIKE', '%'.$mobile.'%');
			}
			$actualamount1 = $this->_get('actualamount1');
			if($actualamount1){
				$actualamountWhere[] = array('egt',$actualamount1);
			}
			$actualamount2 = $this->_get('actualamount2');
			if($actualamount2){
				$actualamountWhere[] = array('elt',$actualamount2);
			}
			if($actualamount1 || $actualamount2){
				$where['actualamount'] = $actualamountWhere;
			}
			$paydonetime1 = $this->_get('paydonetime1');
			if($paydonetime1){
				$paydonetimeWhere[] = array('egt',strtotime($paydonetime1.' 00:00:00'));
			}
			$paydonetime2 = $this->_get('paydonetime2');
			if($paydonetime2){
				$paydonetimeWhere[] = array('elt',strtotime($paydonetime2.' 23:59:59'));
			}
			if($paydonetime1 || $paydonetime2){
				$where['paydonetime'] = $paydonetimeWhere;
			}
			$shopInfo = M('company_shops')->where(array('companyid'=>$this->companyid, 'id'=>$shopid))->field('id,shopname,name')->find();
			if($shopInfo){
				$shopname = '-';
				$shopname .= $shopInfo['shopname']?$shopInfo['shopname']:$shopInfo['name'];
			}
			$where['sorder.companyid'] = $this->companyid;
			$where['shopid'] = $shopid;
			$where['paystate'] = '2';
			$list = M()->table('tp_shanhui_order AS sorder')->join('tp_member_register_info AS rinfo ON sorder.mid=rinfo.id')->where($where)->field('sorder.companyid,sorder.id,sorder.shopid,sorder.orderid,sorder.out_trade_no,sorder.receivables,sorder.actualamount,sorder.shanhuidiscount,sorder.dmsdiscount,sorder.paydonetime,sorder.note, rinfo.name,rinfo.moblie')->order('paydonetime DESC')->select();
			if($list){
				$filename = "闪惠订单-".$shopname;
				$option['rule'] = M()->getLastSql();
				$option['name'] = $filename;
				$option['type'] = 24;
				$ajax = $this->export($option);
			}else{
				$ajax['code'] = 300;
				$ajax['msg'] = "暂无数据";
			}
			echo json_encode($ajax);
		}
	}
	/**
	 * 手机预订
	 * @author Asa<asa@renlaifeng.cn>
	 * @since  2016-11-30
	 */
	public function ajaxCyuding(){
		$commonBookWhere['companyid'] = $this->companyid;
		$commonBookWhere['bookshopid'] = $sid = $this->_request('sid');   // 门店id
		//获得 搜索条件
		//订单号
		$orderid = $this->_request('orderid');
		if($orderid){
			$commonBookWhere['orderid'] = array('like','%'.$orderid.'%');
		}
		//联系人姓名
		$bookname = $this->_request('bookname');
		if($bookname){
			$commonBookWhere['bookname'] = array('like','%'.$bookname.'%');
		}
		//联系人手机
		$bookmobile = $this->_request('bookmobile');
		if($bookmobile){
			$commonBookWhere['bookmobile'] = array('like','%'.$bookmobile.'%');
		}
		//项目名称
		$bookprejectname = $this->_request('bookprejectname');
		if($bookprejectname){
			$commonBookWhere['bookprejectname'] = array('like','%'.$bookprejectname.'%');
		}
		//订金
		$booktotal1 = $this->_request('booktotal1');
		$booktotal2 = $this->_request('booktotal2');
		if($booktotal1 && $booktotal2 && format_number($booktotal1) <= format_number($booktotal2)){
			$commonBookWhere['booktotal'] = array('between',array(format_number($booktotal1),format_number($booktotal2)));
		}elseif ($booktotal1){
			$commonBookWhere['booktotal'] = array('egt',format_number($booktotal1));
		}elseif ($booktotal2){
			$commonBookWhere['booktotal'] = array('elt',format_number($booktotal2));
		}
		//预约时间
		$bookupdatetime1 = strtotime($this->_request('bookupdatetime1'));
		$bookupdatetime2 = strtotime($this->_request('bookupdatetime2'));
		if($bookupdatetime1 && $bookupdatetime2 && $bookupdatetime1 <= $bookupdatetime2){
			$commonBookWhere['bookupdatetime'] = array('between',array($bookupdatetime1,$bookupdatetime2));
		}elseif ($bookupdatetime1){
			$commonBookWhere['bookupdatetime'] = array('egt',$bookupdatetime1);
		}elseif ($bookupdatetime2){
			$commonBookWhere['bookupdatetime'] = array('elt',$bookupdatetime2);
		}
		//交易状态
		$bookstatus = $this->_request('bookstatus');
		if($bookstatus){
			$commonBookWhere['bookstatus'] = $bookstatus;
		}
		//今日接待时间
		$bookdate1 = strtotime($this->_request('bookdate1'));
		$bookdate2 = strtotime($this->_request('bookdate2'));
		if($bookdate1 && $bookdate2 && $bookdate1 <= $bookdate2){
			$commonBookWhere['bookdate'] = array('between',array($bookdate1,$bookdate2-1));
		}elseif ($bookdate1){
			$commonBookWhere['bookdate'] = array('egt',$bookdate1);
		}elseif ($bookdate2){
			$commonBookWhere['bookdate'] = array('elt',$bookdate2-1);
		}
		$shopInfo = M('company_shops')->where(array('companyid'=>$this->companyid, 'id'=>$sid))->field('id,shopname,name')->find();
		if($shopInfo){
			$shopname = $shopInfo['shopname']?$shopInfo['shopname']:$shopInfo['name'];
		}
		//列表
		$list = M('mobile_book_order as mbo')
		->join('tp_member_register_info AS mri ON mbo.mid=mri.id')->where($commonBookWhere)
		->field('mbo.orderid,mbo.bookname,mbo.bookmobile,mbo.bookupdatetime,mbo.bookprejectname,mbo.booktotal,mbo.bookstatus,mri.name,mri.moblie')
		->order('createtime DESC')->select();
		if($list){
			$filename = "手机预订订单-".$shopname;
			$option['rule'] = M()->getLastSql();
			$option['name'] = $filename;
			$option['type'] = 25;
			$ajax = $this->export($option);
		}else{
			$ajax['code'] = 300;
			$ajax['msg'] = "暂无数据";
		}
		echo json_encode($ajax);
	}
	
	public function ajaxCdmsordernum(){
		$keyid = $this->_get('keyid');
		$dmsname = M("dms_discoukey")->where(array("id"=>$keyid))->getField("discoukey");
		$type = $this->_get('type');
		$orderWhere['dorder.companyid'] = $this->companyid;
		$orderWhere['dorder.keyid'] = $keyid;
		$orderWhere['dorder.ordertype'] =  $type;
		if($type!=4){
			//$startMonth = mktime(0,0,0,date('m'),1,date('Y'));   // 本月开始时间戳
			//$orderWhere['dorder.confirmtime'] = array('egt', $startMonth);
			$orderWhere['dorder.orderstatus'] = '4';    // 成功支付
			$list = M()->table('tp_dms_order as dorder')->join(array('tp_dms_discoukey AS ddiscoukey ON ddiscoukey.id = dorder.keyid'))->where($orderWhere)->field('dorder.id,dorder.mid,dorder.companyid,dorder.keyid,dorder.wagesmoney,dorder.ordermoney,dorder.orderid,dorder.orderstatus,dorder.ordertype,dorder.discoukey,dorder.confirmtime,dorder.createtime,dorder.discoutype,dorder.startdiscoumoney,dorder.discoumoney,dorder.discouratio,ddiscoukey.startdiscoumoney8,ddiscoukey.discoumoney8,dorder.giftname')->order('createtime DESC')->select();
		}else{
			$orderWhere['dorder.orderstatus'] = array('IN','4,5');
			$list = M()->table('tp_dms_order AS dorder')->join('tp_shanhui_order AS sorder ON dorder.orderid=sorder.dmsorderid')->where($orderWhere)->field('dorder.id,dorder.ordertype,dorder.companyid,dorder.mid,sorder.orderid,sorder.receivables,sorder.actualamount,sorder.shanhuidiscount,sorder.dmsdiscount,sorder.paydonetime')->order('sorder.paydonetime DESC')->select();
		}
		if($type==1){
			$asatype = 26; $asaname="eshop订单";
		}elseif($type==2){
			$asatype = 26; $asaname="门店收银订单";
		}elseif($type==4){ $asaname="闪惠订单";
			$asatype = 28;
		}else $asatype = '';
		if($list){
			$filename = $dmsname."-".$asaname;
			$option['rule'] = M()->getLastSql();
			$option['name'] = $filename;
			$option['type'] = $asatype;
			$ajax = $this->export($option);
		}else{
			$ajax['code'] = 300;
			$ajax['msg'] = "暂无数据";
		}
		echo json_encode($ajax);
	}
	/**
	 * 写导出方法的
	 * rule  需要执行的SQL
	 * name  下载的文件名字
	 * type  模板类型
	 */
	public function export($option){
		$data = $option;
		//$data['name'] .="-".date("YmdHis",time());
		$data['companyid'] = $this->companyid;
		$data['createtime'] = $data['updatetime'] = time();
		$id = guidNow();
		$data['remarkname'] = $id;
		$addSuc = M('export_task')->add($data);
		if($addSuc){
			$ajax['code'] = 200;
			$ajax['msg'] = '任务创建成功';
		}else{
			$ajax['code'] = 300;
			$ajax['msg'] = '任务创建失败';
		}
		return $ajax;
	}

}
?>