<?php
/**
 * 订单处理
 * Enter description here ...
 * @author Tomas
 */
class MallOrderAction extends UserAction{ 
	
	private $uid;
	
	private $companyid;
	
	public function __construct(){
		parent::__construct();
		$this->uid 		 = session('uid');
		$this->companyid = session('cid');
		$this->shopsid   = session('shopsid');
		$this->ordertype = $this->_request('ordertype');
	}
	/**
	 * 订单列表
	 */
	public function index(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'订单管理','url'=>U('MallOrder/index'),'rel'=>'','target'=>'')));
		$this->check_url = 'mallorderindex';
		$where['morder.companyid'] = $this->companyid;
		//$where['morder.ordertype'] = $this->ordertype;
		
		//订单状态
		$orderstatus = $this->_request('orderstatus');
		if($orderstatus){
			$where['morder.orderstatus'] = $orderstatus;
			$this->assign('orderstatus',$orderstatus);
		}
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
		//购买人手机
		$mphone = $this->_request('mphone');
		if($mphone){
			$where['register.mobile'] = array('like','%'.$mphone.'%');
			$this->assign('mphone',$mphone);
		}
		//订单金额
		$orderprice1 = $cardvalue = $this->_request('orderprice1');
		$orderprice2 = $this->_request('orderprice2');
		if($orderprice1 && $orderprice2 && $orderprice1 <= $orderprice2){
			$where['morder.orderprice'] = array('between',array($orderprice1,$orderprice2));
			$this->assign('orderprice1',$this->_request('orderprice1'));
			$this->assign('orderprice2',$this->_request('orderprice2'));
		}elseif ($orderprice1){
			$where['morder.orderprice'] = array('egt',$orderprice1);
			$this->assign('orderprice1',$this->_request('orderprice1'));
		}elseif ($orderprice2 > 0){
			$where['morder.orderprice'] = array('elt',$orderprice2);
			$this->assign('orderprice2',$this->_request('orderprice2'));
		}
		//订单提交时间
		$createtime1 = strtotime($this->_request('createtime1'));
		$createtime2 = strtotime($this->_request('createtime2'))-1;
		if($createtime1 && $createtime2 && $createtime1 <= $createtime2){
			$where['morder.createtime'] = array('between',array($createtime1,$createtime2));
			$this->assign('createtime1',$this->_request('createtime1'));
			$this->assign('createtime2',$this->_request('createtime2'));
		}elseif($createtime1){
			$where['morder.createtime'] = array('egt',$createtime1);
			$this->assign('createtime1',$this->_request('createtime1'));
		}elseif($createtime2 > 0){
			$where['morder.createtime'] = array('elt',$createtime2);
			$this->assign('createtime2',$this->_request('createtime2'));
		}
		$orderCount = M()->table('tp_mall_order_info as morder')->join(array('tp_member_register_info AS register ON morder.mid=register.id'))->where($where)->count();
		$page = new NewPage($orderCount,15);
		$orderList = M()->table('tp_mall_order_info as morder')->join(array('tp_member_register_info AS register ON morder.mid=register.id'))->where($where)->field('morder.id,morder.mid,morder.orderid,morder.ordertype,morder.truegoodtype,morder.orderstatus,morder.orderprice,morder.createtime,register.name as mname,register.mobile as mmobile')->order('morder.createtime DESC')->limit($page->firstRow.','.$page->listRows)->select();
		if($orderList){
			foreach($orderList as $olKey=>$olVal){
				if($olVal['ordertype']==1 && $olVal['truegoodtype']==2){
					$orderList[$olKey]['mall'] = M('member_delivery_voucher')->where(array('companyid'=>$this->companyid,'orderid'=>$olVal['orderid']))->field('name,sn')->select();
				}else{
					$orderList[$olKey]['mall'] = M('mall_order_goods')->where(array('companyid'=>$this->companyid,'orderid'=>$olVal['orderid']))->field('pricetype,goodname,goodpic,goodprice,goodint,goodnum,goodskuname')->select();
				}
			}
		}
		$this->assign('list',$orderList);
		$this->assign('page',$page->diyshow());
		//自动关闭订单设置
		$info = M('company')->where(array('id'=>session('cid')))->field('id,mallorderautoset')->find();
		$this->assign('info',$info);
		$this->display();
	}
	/**
	 * 实物订单详情
	 */
	public function info(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'订单管理','url'=>U('MallOrder/index'),'rel'=>'','target'=>''),array('name'=>'订单详情','url'=>'','rel'=>'','target'=>'')));
		
		$where['morder.id'] = $this->_get('id');
		$where['morder.companyid'] = $this->companyid;
		$orderInfo = M()->table('tp_mall_order_info as morder')
            ->join(array('tp_member_register_info AS register ON morder.mid=register.id','tp_member_card_info AS card ON register.id=card.mid','tp_member_wechat_info AS wechat ON register.id=wechat.mid'))->where($where)->field('morder.id,morder.mid,morder.orderid,morder.out_trade_no,morder.borderid,morder.ordertype,morder.truegoodtype,morder.orderstatus,morder.membernote,morder.orderprice,morder.orderderateprice,morder.ordersubtotal,morder.createtime,morder.paytime,morder.shippingtime,morder.receivaltime,morder.offtime,morder.orderpaymethod,morder.orderinvoice,morder.orderinvoicetype,morder.orderinvoicetitle,morder.orderinvoicemailingaddress,morder.ordernote,morder.membernote,morder.consigneename,morder.consigneephone,morder.consigneeaddress,morder.logisticsid,morder.logisticsnum,morder.vouchertitle,morder.vouchermoney,wechat.nickname as mnickname,register.name as mname,card.cardnum as mcardnum,register.mobile as mmobile')->find();
		if($orderInfo){
			if($orderInfo['ordertype']==1 && $orderInfo['truegoodtype']==2){
				$orderInfo['mall'] = M('member_delivery_voucher')->where(array('companyid'=>$this->companyid,'orderid'=>$orderInfo['orderid']))->field('name,sn')->select();
			}else{
				//实物
				$orderInfo['mall'] = M('mall_order_goods')->where(array('companyid'=>$this->companyid,'orderid'=>$orderInfo['orderid'],'goodtype'=>'1'))->field('goodtype,goodid,goodname,goodpic,goodprice,goodint,goodnum,goodskuname')->select();
			}
		}
		$this->assign('info',$orderInfo);
		$this->display();
	}
	/**
	 * 修改订单状态
	 */
	public function ajaxCloseOrder(){
		M()->startTrans();//事务开启
		$id = $this->_post('id');
		$returnDate['code'] = '300';
		if($id){
			$where['id'] = $id;
			$where['companyid'] = $this->companyid;
			$orderstatus = $this->_post('orderstatus');
			$saveData['orderstatus'] = $orderstatus;
			$saveData['updatetime'] = time();
			if($orderstatus == 2){
				$saveData['paytime'] = time();
			}
			if($orderstatus == 3){
				$saveData['shippingtime'] = time();
			}
			$billSuc = 1;
			$dmsOrderSuc = 1;
			$pillSuc = 1;
			$partnerOrderSuc = 1;
			$orderid = M('mall_order_info')->where($where)->getField('orderid');
			$dmsOrderInfo = M('mall_dms_order')->where(array('companyid'=>$this->companyid,'orderid'=>$orderid))->field('id,mid,ordermoney,wagesmoney')->find();
			$partnerOrderInfo = M('mall_exhibition_partner_order')->where(array('companyid'=>$this->companyid,'orderid'=>$orderid))->field('id,mid,orderprice,commission')->select();
			if($orderstatus == 4){
				$saveData['receivaltime'] = time();
				// DMS
				if($dmsOrderInfo){
					//佣金流水记录
					$time = time();
					$start = mktime(0,0,0,date("m",$time),date("d",$time),date("Y",$time));
					$dillData['id'] = guidNow();
					$dillData['companyid'] = $this->companyid;
					$dillData['billtype'] = 1;
					$dillData['borderid'] = get_order_id();
					$dillData['money'] = $dmsOrderInfo['ordermoney'];
					$dillData['wages'] = $dmsOrderInfo['wagesmoney'];
					$dillData['orderid'] = $orderid;
					$dillData['mid'] = $dmsOrderInfo['mid'];
					$dillData['searchtime'] = $start;
					$dillData['createtime'] = $time;
					$billSuc = M('mall_dms_bill')->add($dillData);
					// 累计经销订单数
					$totalOrderReturn = M('mall_dms_list')->where(array('companyid'=>$this->companyid,'mid'=>$dmsOrderInfo['mid']))->setInc('totalorder');
					// 累计佣金
					$totalMoneyData['totalmoney'] = array('exp', '`totalmoney`+'.$dmsOrderInfo['wagesmoney']);
					$totalMoneyData['updatetime'] = $time;
					$totalMoneyReturn = M('mall_dms_list')->where(array('companyid'=>$this->companyid,'mid'=>$dmsOrderInfo['mid']))->save($totalMoneyData);
					// 佣金成为可提现的
					$availableData['availablemoney'] =  array('exp', '`availablemoney`+'.$dmsOrderInfo['wagesmoney']);
					$availableData['updatetime'] = $time;
					$availableReturn = M('mall_dms_list')->where(array('companyid'=>$this->companyid,'mid'=>$dmsOrderInfo['mid']))->save($availableData);
				}
				if($partnerOrderInfo){
					foreach($partnerOrderInfo as $pKey=>$pVal){
						//佣金流水记录
						$time = time();
						$start = mktime(0,0,0,date("m",$time),date("d",$time),date("Y",$time));
						$pillData['id'] = guidNow();
						$pillData['companyid'] = $this->companyid;
						$pillData['billtype'] = 1;
						$pillData['borderid'] = get_order_id();
						$pillData['money'] = $pVal['orderprice'];
						$pillData['wages'] = $pVal['commission'];
						$pillData['orderid'] = $orderid;
						$pillData['mid'] = $pVal['mid'];
						$pillData['searchtime'] = $start;
						$pillData['createtime'] = $time;
						$pillSuc = M('mall_exhibition_partner_bill')->add($pillData);
						// 累计代理直销订单数
						$totalOrderReturn2 = M('mall_exhibition_partner_list')->where(array('companyid'=>$this->companyid,'mid'=>$pVal['mid']))->setInc('totalorder');
						// 累计佣金
						$totalMoneyData2['totalmoney'] = array('exp', '`totalmoney`+'.$pVal['commission']);
						$totalMoneyData2['updatetime'] = $time;
						$totalMoneyReturn2 = M('mall_exhibition_partner_list')->where(array('companyid'=>$this->companyid,'mid'=>$pVal['mid']))->save($totalMoneyData2);
						// 佣金成为可提现的
						$availableData2['availablemoney'] =  array('exp', '`availablemoney`+'.$pVal['commission']);
						$availableData2['updatetime'] = $time;
						$availableReturn2 = M('mall_exhibition_partner_list')->where(array('companyid'=>$this->companyid,'mid'=>$pVal['mid']))->save($availableData2);
					}
				}
			}
			//修改mall_dms_order订单状态
			if($dmsOrderInfo){
				$dmsSaveData['orderstatus'] = $orderstatus;
				if($orderstatus == 4){
					$dmsSaveData['confirmtime'] = time();
				}
				$dmsSaveData['updatetime'] = time();
				$dmsOrderSuc = M('mall_dms_order')->where(array('companyid'=>$this->companyid,'orderid'=>$orderid))->save($dmsSaveData);
			}
			//修改mall_exhibition_partner_order订单状态
			if($partnerOrderInfo){
				$partnerSaveData['orderstatus'] = $orderstatus;
				$partnerSaveData['updatetime'] = time();
				$partnerOrderSuc = M('mall_exhibition_partner_order')->where(array('companyid'=>$this->companyid,'orderid'=>$orderid))->save($partnerSaveData);
			}
			$saveReturn = M('mall_order_info')->where($where)->save($saveData);
			if($saveReturn && $billSuc && $dmsOrderSuc && $pillSuc && $partnerOrderSuc){
				M()->commit();//事务提交
				$returnDate['code'] = '200';
				$returnDate['shippingtime'] = format_time($saveData['shippingtime'],'ymdhi');
				$returnDate['receivaltime'] = format_time($saveData['receivaltime'],'ymdhi');
			}else{
				M()->rollback();//事务回滚
			}
		}
		echo json_encode($returnDate);
	}
	/**
	 * 修改物流信息
	 */
	public function saveOrderInfo(){
		$return['code'] = 'error';
		$return['tips'] = 'error:500';
		
		C('TOKEN_ON',false);
		$id = $this->_post('id');
		if($id){
			$where['id'] = $id;
			$where['companyid'] = $this->companyid;
			$orderstatus = $this->_post('orderstatus');
			$_POST['updatetime'] = time();
			if($orderstatus == 3){
				$_POST['shippingtime'] = time();
			}
			$orderInfo = $this->saveStatus('Mall_order_info',$where);
			if($orderInfo){
				$return['code'] = 'success';
				$return['tips'] = '操作成功';
			}
		}
		echo json_encode($return);
	}
	/**
	 * 修改收货信息
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-9-5
	 */
	public function saveConsigneeInfo(){
		$return['code'] = 'error';
		$return['tips'] = 'error:500';
	
		C('TOKEN_ON',false);
		$id = $this->_post('consigneeid');
		if($id){
			$where['id'] = $id;
			$where['companyid'] = $this->companyid;
			$_POST['updatetime'] = time();
			$orderInfo = $this->saveStatus('Mall_order_info',$where);
			if($orderInfo){
				$return['code'] = 'success';
				$return['tips'] = '操作成功';
			}
		}
		echo json_encode($return);
	}
	/**
	 * 记次卡/权益卡核销记录
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-10-21
	 */
	public function poolRecordsList(){
		$string = '';
		$where['companyid'] = $this->companyid;
		$where['vouchernumber'] = $this->_post('sn');
		//$list = M()->table('tp_use_vouchers AS vouchers')->join(array('LEFT JOIN tp_company_shops AS shops ON vouchers.shopid=shops.id'))->where($where)->field('vouchers.usetime,vouchers.staffname,shops.shopid,shops.shopname')->order('vouchers.usetime DESC')->select();
		$list = M('use_vouchers')->where($where)->field('usetime,staffname,shopid,shopname')->order('usetime DESC')->select();
		if($list){
			foreach($list as $key=>$val){
				$string .= '<tr><td>'.format_time($val['usetime'],'ymdhi').'</td><td>';
				if($val['shopid'] == '-1'){
					$string .= '总部';
				}else{
					$string .= $val['shopname'];
				}
				$string .= '</td><td>'.$val['staffname'].'</td></tr>';
			}
		}else{
			$string .= '<tr class="text-center not-hover"><td colspan="3">暂无</td></tr>';
		}
		echo json_encode($string);
	}
	/**
	 * 修改服务备注信息
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-9-5
	 */
	public function saveServiceInfo(){
		$return['code'] = 'error';
		$return['tips'] = 'error:500';
	
		C('TOKEN_ON',false);
		$id = $this->_post('serviceid');
		if($id){
			$where['id'] = $id;
			$where['companyid'] = $this->companyid;
			$_POST['updatetime'] = time();
			$orderInfo = $this->saveStatus('mall_order_service',$where);
			if($orderInfo){
				$return['code'] = 'success';
				$return['tips'] = '操作成功';
			}
		}
		echo json_encode($return);
	}
	/**
	 * 售后服务
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-8-22
	 */
	public function ajaxHandleService(){
		$return['code'] = 'error';
		$return['tips'] = 'error:500';
		
		$id = $this->_post('id');
		if($id){
			$where['id'] = $id;
			$where['companyid'] = $this->companyid;
			$service = M('mall_order_service')->where($where)->save(array('handle'=>1,'updatetime'=>time()));
			if($service){
				$return['code'] = 'success';
				$return['tips'] = '操作成功';
			}
		}
		echo json_encode($return);
	}
	/**
	 * 删除订单
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-8-14
	 */
	public function ajaxDelOrder(){
		$where['companyid'] = $this->companyid;
		$where['orderid'] = $this->_post('orderid');
	
		M()->startTrans();
		$delOrderInfo = M('mall_order_info')->where($where)->delete();
		$delOrderGood = M('mall_order_goods')->where($where)->delete();
		if($delOrderInfo && $delOrderGood){
			M()->commit();
			$return['code'] = 'success';
			$return['tips'] = '操作成功';
		}else{
			M()->rollback();
			$return['code'] = 'error';
			$return['tips'] = 'error:500';
		}
		echo json_encode($return);
	}
	/**
	 * 自动关闭订单设置
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-8-16
	 */
	public function ajaxMallorderautoset(){
		$return['code'] = 'error';
		$return['tips'] = 'error:500';
	
		$mallorderautoset = M('company')->where(array('id'=>$this->companyid))->save(array('mallorderautoset'=>$this->_post('mallorderautoset'),'updatetime'=>time()));
		if($mallorderautoset){
			$return['code'] = 'success';
			$return['tips'] = '操作成功';
		}
		echo json_encode($return);
	}
	/**
	 * EXCEL导出
	 */
	public function exportExcel(){
		set_time_limit(0);
		C('TOKEN_ON',false);
		$ordertype = $this->_request('ordertype');
		$where['morder.companyid'] = $this->companyid;
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
			$where['register.mobile'] = array('like','%'.$mphone.'%');
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
		$data = array('0'=>array('0'=>'','1'=>'','2'=>'','3'=>'','4'=>'','5'=>'','6'=>'','7'=>'','8'=>'','9'=>'','10'=>'','11'=>'','12'=>'','13'=>'','14'=>'','15'=>'','16'=>'','17'=>''));
		$orderList = M()->table('tp_mall_order_info as morder')->join(array('tp_member_register_info AS register ON morder.mid=register.id'))->where($where)->field('morder.out_trade_no,morder.orderid,morder.ordertype,morder.truegoodtype,morder.orderstatus,morder.paytime,morder.orderprice,morder.ordersubtotal,morder.consigneename,morder.consigneeaddress,morder.consigneephone,morder.membernote,morder.createtime,register.name,register.mobile')->order('morder.createtime DESC')->select();
		if($orderList){
			foreach($orderList as $key=>$val){
				$out_trade_no = $val['out_trade_no'] ? $val['out_trade_no'] : ''; // 商户订单号
				if($val['orderstatus'] == 1){ // 订单状态
					$orderstatus = '待付款';
				}elseif($val['orderstatus'] == 2){
					$orderstatus = '待发货';
				}elseif($val['orderstatus'] == 3){
					$orderstatus = '配送中';
				}elseif($val['orderstatus'] == 4){
					$orderstatus = '已安装';
				}elseif($val['orderstatus'] == 5){
					$orderstatus = '已取消';
				}elseif($val['orderstatus'] == 6){
					$orderstatus = '卡券已发送';
				}elseif($val['orderstatus'] == 7){
					$orderstatus = '确认到账中';
				}elseif($val['orderstatus'] == 8){
					$orderstatus = '退货退款';
				}elseif($val['orderstatus']==9 || $val['orderstatus']==10){
					$orderstatus = '已退单';
				}
				// 商品名称
				if($val['ordertype']==1 && $val['truegoodtype']==2){
					$mallGoods = M('member_delivery_voucher')->where(array('companyid'=>$this->companyid,'orderid'=>$val['orderid']))->field('name,sn')->select();
				}else{
					$mallGoods = M('mall_order_goods')->where(array('companyid'=>$this->companyid,'orderid'=>$val['orderid']))->field('id,goodname,vouchersid,goodtype,goodprice,goodnum')->select();
				}
				foreach($mallGoods as $gKey=>$gVal){
					if($val['ordertype']==1 && $val['truegoodtype']==2){
						$orderList[$key]['goodname'] .= str_replace('&nbsp;','',$gVal['name']).';';
						$orderList[$key]['goodprice'] .= '0.00;';
						$orderList[$key]['goodnum'] .= '1;';
						$orderList[$key]['tsn'] .= $gVal['sn'].';';
					}else{
						$orderList[$key]['goodname'] .= str_replace('&nbsp;','',$gVal['goodname']).';';
						$orderList[$key]['goodprice'] .= $gVal['goodprice'].';';
						$orderList[$key]['goodnum'] .= $gVal['goodnum'].';';
					}
					if($gVal['goodtype'] == 2){
						$vouchers = M('member_vouchers')->where(array('companyid'=>$this->companyid,'voucherinfoid'=>$gVal['vouchersid'],'orderid'=>$val['orderid'],'getvouchertype'=>6))->field('id,useendtime,sn,isused')->select();
					}elseif($gVal['goodtype'] == 3 || $gVal['goodtype'] == 4 || $gVal['goodtype'] == 5 || $gVal['goodtype'] == 6){
						$vouchers = M('member_vouchers')->where(array('companyid'=>$this->companyid,'mallordergoodsid'=>$gVal['id']))->field('id,useendtime,sn,isused')->select();
					}
					if($vouchers){
						foreach($vouchers as $vKey=>$vVal){
							$orderList[$key]['sn'] .= $vVal['sn'].';';
							if($vVal['useendtime'] && $vVal['useendtime']<time()){
								$orderList[$key]['isused'] .= '已过期;';
							}else{
								if($vVal['isused'] == 1){
									$orderList[$key]['isused'] .= '是;';
								}else if($vVal['isused'] == 2){
									$orderList[$key]['isused'] .= '否;';
								}else{
									$orderList[$key]['isused'] .= '已冻结;';
								}
							}
						}
					}else{
						$orderList[$key]['sn'] .= ';';
						$orderList[$key]['isused'] .= ';';
					}
					unset($vouchers);
				}
				$data[$key] = array($val['orderid'],$out_trade_no,$orderstatus,format_time($val['createtime'],'ymdhi'),$val['ordersubtotal'],$val['orderprice'],substr($orderList[$key]['goodname'], 0, -1),substr($orderList[$key]['goodprice'], 0, -1),substr($orderList[$key]['goodnum'], 0, -1),substr($orderList[$key]['tsn'], 0, -1),$val['consigneename'],$val['consigneeaddress'],$val['consigneephone'],substr($orderList[$key]['sn'], 0, -1),substr($orderList[$key]['isused'], 0, -1),$val['membernote'],$val['name'],$val['mobile']);
				unset($val,$out_trade_no,$orderstatus,$orderList);
			}
		}
		$filename = "eshop商品订单";
		$headArr = array('订单号','商户订单号','订单状态','下单时间','订单金额','实付金额','商品名称','售价','数量','提货券号','收件人姓名','收件人地址','收件人电话','卡券号','核销状态','买家留言','会员姓名','会员手机号');
		$this->getExcel($filename,$headArr,$data);
	}
	/**
	 * 打印快递单
	 */
	public function printExpress(){
		$logisticsid = $this->_get('logisticsid');
		$where['id'] = $this->_get('id');
		$where['companyid'] = $this->companyid;
		$orderInfo=M('mall_order_info')->where($where)->field('orderid,ordertype,consigneename,consigneephone,consigneeaddress')->find();
		$memberDeliveryVoucherModel =M('member_delivery_voucher');
		if($orderInfo){
			if($orderInfo['ordertype'] == 1){
				$mall = M('mall_order_goods')->where(array('companyid'=>$this->companyid,'orderid'=>$orderInfo['orderid']))->field('goodname,goodnum')->select();
				if($mall){
					foreach($mall as $gkey=>$gval){
						if($gkey<5){
							$orderInfo['malls'] .= '<li><p>&nbsp;'.$gval['goodname'].'</p><span>×'.$gval['goodnum'].'</span></li>';
						}
					}
				}
			}elseif ($orderInfo['ordertype'] == 2){
				$mall = $memberDeliveryVoucherModel->where(array('companyid'=>$this->companyid,'orderid'=>$orderInfo['orderid']))->field('name')->select();
				foreach ($mall as $mKey=>$mVal){
					$orderInfo['malls'] .= '<li><p>&nbsp;'.$mVal['name'].'</p><span>×1</span></li>';
				}
			}
		}
		$companyinfo = M('company')->where(array('id'=>$this->companyid))->field('name,address,tel')->find();
		$orderInfo['cname'] = $companyinfo['name'];
		$orderInfo['caddress'] = $companyinfo['address'];
		$orderInfo['ctel'] = $companyinfo['tel'];
		$this->assign('info',$orderInfo);
		$this->assign('logisticsid',$logisticsid);
		$this->display('printExpress'.$logisticsid);
	}
}
?>