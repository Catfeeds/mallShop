<?php
/**
 * 我的订单
 * Enter description here ...
 * @author zhaoyongfei
 */
class MemberMallOrderAction extends WapBaseAction{
	
	private $companyid;
	
	private $mid;
	
	public function __construct(){
		parent::__construct();
		$this->companyid = session('wapcid');
		$this->mid = session('mid'.session('wapcid'));
		$this->limit = 8;
	}
	/**
	 * 交易订单
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2017-1-17
	 */
	public function index(){
		$this->setPageTitle(array('title'=>'交易订单'));
		if($this->mid){
			$where['companyid'] = $this->companyid;
			$where['mid'] = $this->mid;
			// 全部
			$this->count = M('mall_order_info')->where($where)->count();
			$list = M('mall_order_info')->where($where)->field('id,orderid,ordertype,truegoodtype,orderstatus,orderprice,groupinfoid')->order('createtime DESC')->limit($this->limit)->select();
			if($list){
				foreach($list as $key=>$val){
					if($val['ordertype']==1 && $val['truegoodtype']==2){
						$list[$key]['mall'] = M('member_delivery_voucher')->where(array('companyid'=>$this->companyid,'orderid'=>$val['orderid']))->field('name')->select();
					}else{
						$list[$key]['mall'] = M('mall_order_goods')->where(array('companyid'=>$this->companyid,'orderid'=>$val['orderid']))->field('goodid,goodname,goodpic,goodprice,goodnum,goodskuname')->select();
					}
				}
			}
			$this->assign('list', $list);
			// 待付款
			$where1 = $where;
			$where1['orderstatus'] = 1;
			$this->count1 = M('mall_order_info')->where($where1)->count();
			$list1 = M('mall_order_info')->where($where1)->field('id,orderid,ordertype,truegoodtype,orderstatus,orderprice,groupinfoid')->order('createtime DESC')->limit($this->limit)->select();
			if($list1){
				foreach($list1 as $key1=>$val1){
					if($val1['ordertype']==1 && $val1['truegoodtype']==2){
						$list1[$key1]['mall'] = M('member_delivery_voucher')->where(array('companyid'=>$this->companyid,'orderid'=>$val1['orderid']))->field('name')->select();
					}else{
						$list1[$key1]['mall'] = M('mall_order_goods')->where(array('companyid'=>$this->companyid,'orderid'=>$val1['orderid']))->field('goodid,goodname,goodpic,goodprice,goodnum,goodskuname')->select();
					}
				}
			}
			$this->assign('list1', $list1);
			// 待发货
			$where2 = $where;
			$where2['ordertype'] = 1;
			$where2['orderstatus'] = 2;
			$this->count2 = M('mall_order_info')->where($where2)->count();
			$list2 = M('mall_order_info')->where($where2)->field('id,orderid,ordertype,truegoodtype,orderstatus,orderprice')->order('createtime DESC')->limit($this->limit)->select();
			if($list2){
				foreach($list2 as $key2=>$val2){
					if($val2['ordertype']==1 && $val2['truegoodtype']==2){
						$list2[$key2]['mall'] = M('member_delivery_voucher')->where(array('companyid'=>$this->companyid,'orderid'=>$val2['orderid']))->field('name')->select();
					}else{
						$list2[$key2]['mall'] = M('mall_order_goods')->where(array('companyid'=>$this->companyid,'orderid'=>$val2['orderid']))->field('goodid,goodname,goodpic,goodprice,goodnum,goodskuname')->select();
					}
				}
			}
			$this->assign('list2', $list2);
			// 已发货
			$where3 = $where;
			$where3['ordertype'] = 1;
			$where3['orderstatus'] = 3;
			$this->count3 = M('mall_order_info')->where($where3)->count();
			$list3 = M('mall_order_info')->where($where3)->field('id,orderid,ordertype,truegoodtype,orderstatus,orderprice')->order('createtime DESC')->limit($this->limit)->select();
			if($list3){
				foreach($list3 as $key3=>$val3){
					if($val3['ordertype']==1 && $val3['truegoodtype']==2){
						$list3[$key3]['mall'] = M('member_delivery_voucher')->where(array('companyid'=>$this->companyid,'orderid'=>$val3['orderid']))->field('name')->select();
					}else{
						$list3[$key3]['mall'] = M('mall_order_goods')->where(array('companyid'=>$this->companyid,'orderid'=>$val3['orderid']))->field('goodid,goodname,goodpic,goodprice,goodnum,goodskuname')->select();
					}
				}
			}
			$this->assign('list3', $list3);
			// 已签收
			$where4 = $where;
			$where4['orderstatus'] = 4;
			$this->count4 = M('mall_order_info')->where($where4)->count();
			$list4 = M('mall_order_info')->where($where4)->field('id,orderid,ordertype,truegoodtype,orderstatus,orderprice')->order('createtime DESC')->limit($this->limit)->select();
			if($list4){
				foreach($list4 as $key4=>$val4){
					if($val4['ordertype']==1 && $val4['truegoodtype']==2){
						$list4[$key4]['mall'] = M('member_delivery_voucher')->where(array('companyid'=>$this->companyid,'orderid'=>$val4['orderid']))->field('name')->select();
					}else{
						$list4[$key4]['mall'] = M('mall_order_goods')->where(array('companyid'=>$this->companyid,'orderid'=>$val4['orderid']))->field('goodid,goodname,goodpic,goodprice,goodnum,goodskuname')->select();
					}
				}
			}
			$this->assign('list4', $list4);
			// 退货退款
			$where8 = $where;
			$where8['orderstatus'] = 8;
			$this->count8 = M('mall_order_info')->where($where8)->count();
			$list8 = M('mall_order_info')->where($where8)->field('id,orderid,ordertype,truegoodtype,orderstatus,orderprice')->order('createtime DESC')->limit($this->limit)->select();
			if($list8){
				foreach($list8 as $key8=>$val8){
					if($val8['ordertype']==1 && $val8['truegoodtype']==2){
						$list8[$key8]['mall'] = M('member_delivery_voucher')->where(array('companyid'=>$this->companyid,'orderid'=>$val8['orderid']))->field('name')->select();
					}else{
						$list8[$key8]['mall'] = M('mall_order_goods')->where(array('companyid'=>$this->companyid,'orderid'=>$val8['orderid']))->field('goodid,goodname,goodpic,goodprice,goodnum,goodskuname')->select();
					}
				}
			}
			$this->assign('list8', $list8);
		}else{
			session('historyUrl','http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]);// 用于登陆后跳回本页面
			$this->checkMemberLoginBox();// 检测是否登录弹框
			$list = array();
		}
		$this->assign('isshow', $this->_get('isshow'));
		$this->assign('currentPage', 4); // 通底图标点亮 
		$this->display();
	}
	/**
	 * ajax获得我的订单
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2017-1-17
	 */
	public function ajaxgetorderlist(){
		$orderstatus = $this->_post('orderstatus'); // 订单状态
		$startNumber = $this->_post('startNumber'); // 本次查询的开始条数
		$startNumber = $startNumber ? $startNumber : 0;
		$htmlString = '';
		
		$where['companyid'] = $this->companyid;
		$where['mid'] = $this->mid;
		if($orderstatus){
			$where['orderstatus'] = $orderstatus;
			if($orderstatus==2 || $orderstatus==3){
				$where['ordertype'] = 1;
			}
		}
		$list = M('mall_order_info')->where($where)->field('id,orderid,ordertype,truegoodtype,orderstatus,orderprice')->order('createtime DESC')->limit($startNumber, $this->limit)->select();
		if($list){
			foreach($list as $key=>$val){
				$htmlString .= '<div class="details-Image-Text"><div class="details-Image-Text-head"><span>';
				if($val['orderstatus']==1){ $htmlString .= '待付款';}elseif($val['orderstatus']==2){ $htmlString .= '待发货';}elseif($val['orderstatus']==3){ $htmlString .= '已发货';}elseif($val['orderstatus']==4){ $htmlString .= '已签收';}elseif($val['orderstatus']==5){ $htmlString .= '已取消';}elseif($val['orderstatus']==7){ $htmlString .= '确认到账中';}elseif($val['orderstatus']==8){ $htmlString .= '退货';}elseif($val['orderstatus']==11){ $htmlString .= '待成团';}
				$htmlString .= '</span><div class="details-Text--ml"><a href="javascript:void(0);">订单号：'.$val['orderid'].'</a></div></div>';
				$htmlString .= '<ul class="good-list-selected Image-Text-ul" style="margin-bottom: 0;">';
				if($val['ordertype']==1 && $val['truegoodtype']==2){ 
					$mallList = M('member_delivery_voucher')->where(array('companyid'=>$this->companyid,'orderid'=>$val['orderid']))->field('name')->select();
					foreach($mallList as $mkey=>$mval){
						$htmlString .= '<li><img class="good-img" src="http://www.mobiwind.cn/Tpl/User/default/common/images/default-ticket.jpg"><div class="good-info-cover"><p class="good-name">'.$mval['name'].'</p><p class="good-size"><!-- 规格：65g/2份 --></p><p class="good-price">￥0.00 <span class="good-num">x1</span></p></div></li>';
					}
				}else{
					$mallList = M('mall_order_goods')->where(array('companyid'=>$this->companyid,'orderid'=>$val['orderid']))->field('goodid,goodname,goodpic,goodprice,goodnum,goodskuname')->select();
					foreach($mallList as $mkey=>$mval){
						$htmlString .= '<li><a href="'.U('MallGoods/goodInfo',array('companyid'=>$this->companyid,'id'=>$mval['goodid'])).'"><img class="good-img" src="'.$mval['goodpic'].'"><div class="good-info-cover"><p class="good-name">'.$mval['goodname'].'</p><p class="good-size">';if($mval['goodskuname']){ $htmlString .= '规格：'.$mval['goodskuname'];} $htmlString .= '</p><p class="good-price">￥'.$mval['goodprice'].' <span class="good-num">x'.$mval['goodnum'].'</span></p></div></a></li>';
					}
				}
				$htmlString .= '</ul><p class="order-nub-p">订单金额:￥'.$val['orderprice'].'</p><div class="Image-Text-btn-box"><a href="'.U('MemberMallOrder/info',array('companyid'=>$this->companyid,'id'=>$val['id'],'isshow'=>2)).'" class="btn Order-black-btn">订单详情</a>';
				if($val['ordertype']==1 && $val['orderstatus']==3){
					$htmlString .= '<a href="javascript:void(0);" class="btn Order-gold-btn">确认签收</a>';
				}
				if($val['orderstatus'] == 1){
					$htmlString .= '<a href="javascript:void(0);" class="btn Order-black-btn">取消订单</a>';
					$htmlString .= '<a href="'.C('site_url').'/Payapi/Wxpay/payAct/jsapi.php?orderid='.$val['orderid'].'" class="btn Order-gold-btn">立即付款</a>';
				}
				$htmlString .= '</div></div>';
			}
		}
		$return['html'] = $htmlString;
		echo json_encode($return);
	}
	/**
	 * 商城 订单详情
	 * @author Lando<806728685@qq.com>
	 * @since  2016-11-11
	 */
	public function info(){
		$this->setPageTitle(array('title'=>'订单详情'));
		$id = $this->_get('id');
		if($id){
			$where['id'] = $id;
			$where['mid'] = $this->mid;
			$where['companyid'] = $this->companyid;
			$info = M('mall_order_info')->where($where)->find();
			if($info){
				if($info['ordertype']==1 && $info['truegoodtype']==2){
					$info['mall'] = M('member_delivery_voucher')->where(array('companyid'=>$this->companyid,'orderid'=>$info['orderid']))->field('name')->select();
				}else{
					$info['mall'] = M('mall_order_goods')->where(array('companyid'=>$this->companyid,'orderid'=>$info['orderid']))->field('id,goodtype,goodid,goodname,goodpic,goodprice,goodnum,goodskuname')->select();
					foreach($info['mall'] as $key=>$val){
						$info['mall'][$key]['seid'] = M('mall_order_service')->where(array('companyid'=>$this->companyid,'orid'=>$info['id'],'ogid'=>$val['id']))->getField('id');
					}
				}
				$this->assign('info',$info);
				$this->assign('isshow',$this->_get('isshow'));
				$this->display();
			}else{
				$this->redirect(U('System/notFound',array('companyid'=>$this->companyid)));
			}
		}else{
			$this->redirect(U('System/notFound',array('companyid'=>$this->companyid)));
		}
	}
	/**
	 * 修改订单状态
	 * @author 姚成凯<kevin@renlaifeng.cn>
	 * @since  2016-8-26
	 */
	public function ajaxCloseOrder(){
		$id = $this->_post('id');
		if($id){
			M()->startTrans();//事务开启
			$orderstatus = $this->_post('orderstatus');
			$saveData['orderstatus'] = $orderstatus;
			$saveData['updatetime'] = time();
			
			$billSuc = 1;
			$ordernumIncSuc = 1;
			$totalpriceSuc = 1;
			$ordernumSuc = 1;
			$thismonthordersum = 1;
			$totalmoneySuc = 1;
			$thismonthmoneySuc = 1;
			$dmsOrderSuc = 1;
			$where['id'] = $id;
			$where['companyid'] = $this->companyid;
			$orderid = M('mall_order_info')->where($where)->getField('orderid');
			$dmsOrderInfo = M('dms_order')->where(array('companyid'=>$this->companyid,'orderid'=>$orderid))->field('id,mid,keyid,ordermoney,wagesmoney')->find();
			if($orderstatus == 4){
				$saveData['receivaltime'] = time();
				//商品销量
				/* $mallOrderGoods = M('mall_order_goods')->where(array('companyid'=>$this->companyid,'orderid'=>$orderid))->field('goodid,goodnum')->select();
				if($mallOrderGoods){
					foreach($mallOrderGoods as $key=>$val){
						M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$val['goodid']))->setInc('salenum',$val['goodnum']);
					}
				} */
				//DMS
				if($dmsOrderInfo){
					//佣金流水记录
					$time = time();
					$start = mktime(0,0,0,date("m",$time),date("d",$time),date("Y",$time));
					$dillData['id'] = guidNow();
					$dillData['companyid'] = $this->companyid;
					$dillData['keyid'] = $dmsOrderInfo['keyid'];
					$dillData['billtype'] = 1;
					$dillData['borderid'] = get_order_id();
					$dillData['money'] = $dmsOrderInfo['ordermoney'];
					$dillData['wages'] = $dmsOrderInfo['wagesmoney'];
					$dillData['orderid'] = $orderid;
					$dillData['mid'] = $dmsOrderInfo['mid'];
					$dillData['searchtime'] = $start;
					$dillData['createtime'] = $time;
					$billSuc = M('dms_bill')->add($dillData);
					//单个客户订单数
					$ordernumIncSuc = M('dms_customer')->where(array('companyid'=>$this->companyid,'mid'=>$dmsOrderInfo['mid'],'keyid'=>$dmsOrderInfo['keyid']))->setInc('ordernum');
					//单个客户消费金额
					$totalpriceSuc = M('dms_customer')->where(array('companyid'=>$this->companyid,'mid'=>$dmsOrderInfo['mid'],'keyid'=>$dmsOrderInfo['keyid']))->setInc('totalprice',$dmsOrderInfo['ordermoney']);
					//累计订单数、本月订单数
					$ordernumSuc = M('dms_discoukey')->where(array('companyid'=>$this->companyid,'id'=>$dmsOrderInfo['keyid']))->setInc('totalordersum');
					$thismonthordersum = M('dms_discoukey')->where(array('companyid'=>$this->companyid,'id'=>$dmsOrderInfo['keyid']))->setInc('thismonthordersum');
					if($dmsOrderInfo['wagesmoney'] > 0){
						$totalmoneySuc = M('dms_discoukey')->where(array('companyid'=>$this->companyid,'id'=>$dmsOrderInfo['keyid']))->setInc('totalmoney',$dmsOrderInfo['wagesmoney']);
						$thismonthmoneySuc = M('dms_discoukey')->where(array('companyid'=>$this->companyid,'id'=>$dmsOrderInfo['keyid']))->setInc('thismonthmoney',$dmsOrderInfo['wagesmoney']);
					}
				}
			}else if($orderstatus == 5){
				$saveData['offtime'] = time();
				$mallOrderGoods = M('mall_order_goods')->where(array('companyid'=>$this->companyid,'orderid'=>$orderid))->field('goodid,goodnum,goodskuid')->select();
				if($mallOrderGoods){
					foreach($mallOrderGoods as $key=>$val){
						if($val['goodskuid'] != 0){
							M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'id'=>$val['goodskuid']))->setInc('stockamount', $val['goodnum']);
						}
						M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$val['goodid']))->setInc('stockamount', $val['goodnum']);
					}
				}
			}
			//修改dms_order订单状态
			if($dmsOrderInfo){
				$dmsSaveData['orderstatus'] = $orderstatus;
				if($orderstatus == 4){
					$dmsSaveData['confirmtime'] = time();
				}
				$dmsSaveData['updatetime'] = time();
				$dmsOrderSuc = M('dms_order')->where(array('companyid'=>$this->companyid,'orderid'=>$orderid))->save($dmsSaveData);
			}
			$saveReturn = M('mall_order_info')->where($where)->save($saveData);
			if($saveReturn && $billSuc && $ordernumIncSuc && $totalpriceSuc && $ordernumSuc && $thismonthordersum && $totalmoneySuc && $thismonthmoneySuc && $dmsOrderSuc){
				M()->commit();//事务提交
				$returnDate['code'] = '200';
				$returnDate['tips'] = '操作成功'; 
			}else{
				M()->rollback();//事务回滚
				$returnDate['code'] = '300';
				$returnDate['tips'] = 'error：500';
			}
		}
		echo json_encode($returnDate);
	}
	/**
	 * 立即购买 确认订单
	 * @author Tomas<416369046@qq.com>
	 * @since  2017-11-08
	 */
	public function createBuyNowOrder(){
		$this->setPageTitle(array('title'=>'确认订单'));
		$goodsid = $this->_get('goodsid');
		$this->assign('goodsid',$goodsid);
		$goodsskuid = $this->_get('goodsskuid');
		$this->assign('goodsskuid',$goodsskuid);
		$goodsnum = $this->_get('goodsnum');
		$this->assign('goodsnum',$goodsnum);
		$goodtype = $this->_get('goodtype');
		$this->assign('goodtype',$goodtype);
		if($goodtype&&$goodsid&&$goodsnum){
			$time = time();
			$isDispatching = 1;
			$orderInfo['allPrice'] = '0.00';
			$orderInfo['allGoodsNum'] = '0';
			$orderInfo['allFreight'] = '0.00';
			$orderInfo['allWeight'] = '0.00';
			$good = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$goodsid,'isoffshelves'=>2))->field('id,goodtype,title,goodnum,pricetype,saleprice,isopenvipprice,intprice,canbuynum,weight,freighttype,freighttplid')->find();
			if(!$good){
				$this->redirect(U('System/notFound'),array('companyid'=>$this->companyid));
			}
			$good['goodnum'] = $goodsnum;
			if($goodtype == 1){
				$mallgoodssku = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$goodsid,'id'=>$goodsskuid))->field('name,saleprice,grouponprice,imgurl')->find();
				//$good['title'] .= '（'.$mallgoodssku['name'].'）';
				$good['saleprice'] = $mallgoodssku['saleprice'];
				$good['mallgoodsskuname'] = $mallgoodssku['name'];
				$good['pic'] = $mallgoodssku['imgurl'];	
			}
			$orderInfo['allPrice'] = format_number($good['goodnum']*$good['saleprice']);
			
			$addressInfo = M('member_shop_address')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'isdefault'=>1))->field('id,name,mobile,province,city,district,town,address,isdefault,postid')->find();
			if($addressInfo){
				$addressInfo['address'] = $addressInfo['province'].$addressInfo['city'].$addressInfo['district'].$addressInfo['town'].$addressInfo['address'];
			}else{
				$addressInfo['address'] = '';
			}
			// 收货地址列表
			$list = M('member_shop_address')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'isdefault'=>2))->field('id,name,mobile,province,city,district,town,address,isdefault,postid')->order('updatetime DESC,id DESC')->select();
			foreach($list as $key=>$val){
				$list[$key]['address'] = $val['province'].' '.$val['city'].' '.$val['district'].$val['town'].$val['address'];
			}
			
			$this->assign('addressInfo',$addressInfo);
			$this->assign('list',$list);
			
			$orderInfo['ordersubtotal'] = $orderInfo['allPrice'];
			//用于读取可用优惠券数量
			$orderPrice = $orderInfo['allPrice'];
			//********************* 优惠券 *******************************************************************************************************
			if($orderPrice>0){
				$where['companyid'] = $this->companyid;
				$where['mid'] = $this->mid;
				$where['isused'] = '2';
				$where['issend'] = '2';
				$where['_string'] = ' ((vouchertype = 7 || (vouchertype = 40 && usescenelimitset like "%,1,%")) AND useendtime >" '. time() . '")';
				if ($orderPrice) {
					$where['_string'] .= " AND (parvalue <= " . $orderPrice . " || parvalue = '0.00')";
				}
				//可使用的优惠券
				$vouchers['count'] = M('member_vouchers')->where($where)->count();
				$this->assign('vouchers',$vouchers);
			}
			if($orderInfo['allPrice'] <= 0){
				// 如果订单总价小于0 则默认最低价格为0.01
				$orderInfo['allPrice'] = '0.01';
			}
			$orderInfo['allPrice'] = format_number($orderInfo['allPrice']);
			$this->assign('orderInfo',$orderInfo);
			$this->assign('good',$good);
			$this->assign('isshowsystemdiymen','2');//隐藏自定义菜单
		}else{
			$this->redirect(U('System/notFound'),array('companyid'=>$this->companyid));
		}
		if(!$this->mid){
			session('historyUrl','http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]);// 用于登陆后跳回本页面
			$this->checkMemberLoginBox();// 检测是否登录弹框
		}
		$this->display();
	}
	/**
     * 我的优惠券
     */
    public function myVouchers(){
    	$this->setPageTitle(array('title' => '可用优惠券'));
    	//$this->checkMemberLogin();
    	$goodsid = $this->_get('goodsid');
    	$this->assign('goodsid', $goodsid);
    	$goodsskuid = $this->_get('goodsskuid');
    	$this->assign('goodsskuid', $goodsskuid);
    	$goodsnum = $this->_get('goodsnum');
    	$this->assign('goodsnum', $goodsnum);
    	$addressid = $this->_get('addressid');
    	$this->assign('addressid', $addressid);
    	$ordertype = $this->_get('ordertype');
    	$this->assign('ordertype', $ordertype);
    	$goodtype = $this->_get('goodtype');
    	$this->assign('goodtype', $goodtype);
    	$vouchersid = $this->_get('vouchersid');
    	$this->assign('vouchersid', $vouchersid);
    	$orderPrice = $this->_get('orderPrice');
    	$this->assign('orderPrice',$orderPrice);
    	$where['companyid'] = $this->companyid;
    	$where['mid'] = $this->mid;
    	$where['isused'] = '2';
    	$where['issend'] = '2';
    	$where['_string'] = ' (vouchertype=7 AND useendtime >" '. time() . '")';
    	if ($orderPrice) {
    		$where['_string'] .= " AND (parvalue <= " . $orderPrice . " || parvalue = '0.00')";
    	}
    	//未使用
    	$vouchers['count'] = M('member_vouchers')->where($where)->count();
    	$vouchers['list'] = M('member_vouchers')->where($where)->field('id,mid,sn,parvalue,usetimelimittype,useshopslimitset,useinfo,discounttype,minus,discount,fullminus,fulldiscount,eachfullminus,usestarttime,useendtime,vouchername')->order('id DESC')->select();
    	foreach($vouchers['list'] as $key=>$val){
    		if($val['discounttype'] == 1){
    			$vouchers['list'][$key]['derate'] = floor($val['minus']);
    		}elseif($val['discounttype'] == 2){
    			$vouchers['list'][$key]['derate'] = '折'.$val['discount'].'%';
    		}elseif($val['discounttype'] == 3){
    			$shouldPay = explode(',', $val['fullminus']);
    			$vouchers['list'][$key]['fullPrice'] = $shouldPay[0];
    			$vouchers['list'][$key]['derate'] = $shouldPay[1];
    		}elseif($val['discounttype'] == 4){
    			$shouldPay = explode(',', $val['fulldiscount']);
    			$vouchers['list'][$key]['fullPrice'] = $shouldPay[0];
    			$vouchers['list'][$key]['derate'] = '折'.$shouldPay[1].'%';
    		}elseif($val['discounttype'] == 5){
    			$shouldPay = explode(',', $val['eachfullminus']);
    			$vouchers['list'][$key]['fullPrice'] = $shouldPay[0];
    			$vouchers['list'][$key]['derate'] = $shouldPay[1];
    		}
    	}
    	$this->assign('vouchers', $vouchers);
    	$this->display();
    }
	 /**
     * 发票
     * @author 姚成凯<kevin@renlaifeng.cn>
     * @since  2016-7-25
     */
    public function orderinvoice(){
    	$this->setPageTitle(array('title' => '发票'));
    	//$this->checkMemberLogin();
    	$goodsid = $this->_get('goodsid');
    	$this->assign('goodsid', $goodsid);
    	$goodsskuid = $this->_get('goodsskuid');
    	$this->assign('goodsskuid', $goodsskuid);
    	$goodsnum = $this->_get('goodsnum');
    	$this->assign('goodsnum', $goodsnum);
    	$ordertype = $this->_get('ordertype');//1：购物车结算；2：立即购买；
    	$this->assign('ordertype', $ordertype);
    	$goodtype = $this->_get('goodtype');
    	$this->assign('goodtype', $goodtype);
    	$addressid = $this->_get('addressid');
    	$this->assign('addressid', $addressid);
    	$vouchersid = $this->_get('vouchersid');
    	$this->assign('vouchersid', $vouchersid);
    	$orderinvoice = $this->_get('orderinvoice');//订单发票：1：需要；2：不需要；
    	$this->assign('orderinvoice', $orderinvoice);
    	$orderinvoicetitle = $this->_get('orderinvoicetitle');//发票抬头
    	$this->assign('orderinvoicetitle', $orderinvoicetitle);
    	$this->display();
    }
	/**
	 * SCRM
	 * Ajax 提交即可购买订单
	 * @author Thomas<416369046@qq.com>
	 * @since  2017-11-08
	 */
	public function ajaxCreateBuyNowOrder(){
		$ajaxReturn['code'] = 300;
		$ajaxReturn['msg'] = '订单提交失败，请您稍后重试';
		$time = time();
		$goodsid = $this->_post('goodsid');
		$groupid = $this->_request('groupid');
		$groupinfoid = $this->_request('groupinfoid');
		$good = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$goodsid,'isoffshelves'=>2,'issoldout'=>2))->field('id,goodtype,title,pricetype,saleprice,stockamount,isopenvipprice,intprice,canbuynum')->find();
		if(!$good){
			$ajaxReturn['code'] = 300;
			$ajaxReturn['msg'] = '该商品已售罄（或下架）';
		}else{
			$goodskuid = $this->_post('goodsskuid');
			$good['goodnum'] = $goodnum = $this->_post('goodsnum');
			$mallgoodssku = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'id'=>$goodskuid))->field('name,saleprice,grouponprice,intprice,imgurl,stockamount')->find();
			$stockamount = $mallgoodssku['stockamount'];
			$good['ordertitle'] = $good['title'].'（'. $mallgoodssku['name'].'）';
			$good['saleprice'] = $mallgoodssku['saleprice'];
			$good['pic'] = $mallgoodssku['imgurl'];
			$orderprice = format_number($goodnum*$good['saleprice']);
			if($orderprice <= 0){
				$orderprice = '0.01';
			}
			if($goodnum > $stockamount){
				$ajaxReturn['code'] = 300;
				$ajaxReturn['msg'] = '商品库存不足';
			}else{
				$addressid = $this->_post('addressid');
				if($good['goodtype'] == 1){
					$defaultAddress = M('member_shop_address')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'id'=>$addressid))->field('name,mobile,province,city,town,district,address,postid')->find();
					if(!$defaultAddress){
						$ajaxReturn['msg'] = '请选择您的收货地址';
						echo json_encode($ajaxReturn);
						exit();
					}else{
						$orderInfo['consigneename'] = $defaultAddress['name'];
						$orderInfo['consigneephone'] = $defaultAddress['mobile'];
						$orderInfo['consigneeaddress'] = $defaultAddress['province'].$defaultAddress['city'].$defaultAddress['district'].$defaultAddress['address'];
					}
				}
				$orderInfo['ordersubtotal'] = format_number($orderprice); 				// 订单金额 = 商品金额
				$orderid = $this->newOrderID('2', 'E', $this->companyid);				// 订单号
				$discountPrice = $orderInfo['ordersubtotal'];			
				$derateAllPrice = '0.00';												// 优惠总价
				$vouchersid = $this->_post("vouchersid");								// 优惠券id
				$deratePrice = '0.00';
				//********************* 使用优惠券 *******************************************************************************************************
				$vouchersInfo = M('member_vouchers')->where(array('companyid'=>$this->companyid,'id'=>$vouchersid))->field('id,mid,sn,parvalue,usetimelimittype,useshopslimitset,useinfo,discounttype,minus,discount,fullminus,fulldiscount,eachfullminus,usestarttime,useendtime,vouchername')->find();
				if($vouchersInfo){
					if($vouchersInfo['discounttype'] == '1'){
						//立减
						$deratePrice = $vouchersInfo['minus'];
					}elseif($vouchersInfo['discounttype'] == '2'){
						//立折
						$deratePrice = $discountPrice*($vouchersInfo['discount']/100);
					}elseif($vouchersInfo['discounttype'] == '3'){
						//满减
						$shouldPay = explode(',', $vouchersInfo['fullminus']);
						if($discountPrice >= $shouldPay[0]){
							$deratePrice = $shouldPay[1];
						}
					}elseif($vouchersInfo['discounttype'] == '4'){
						//满折
						$shouldPay = explode(',', $vouchersInfo['fulldiscount']);
						if($discountPrice >= $shouldPay[0]){
							$deratePrice = $discountPrice*($shouldPay[1]/100);
						}
					}elseif($vouchersInfo['discounttype'] == '5'){
						//每满减
						$shouldPay = explode(',', $vouchersInfo['eachfullminus']);
						$deratePrice = (floor($discountPrice/$shouldPay[0]))*$shouldPay[1];
					}
					$orderInfo['vouchertitle'] = $vouchersInfo['vouchername'];	// 优惠券名称
					$orderInfo['vouchermoney'] = $deratePrice;					// 优惠券优惠金额
				}
				$vouchersPrice = format_number($discountPrice - $deratePrice);	  	// 使用优惠券优惠后的总价
				if($vouchersPrice <= 0){
					$vouchersPrice = '0.01';
				}
				$derateAllPrice = $derateAllPrice + $deratePrice;						// 使用优惠券优惠后的优惠总价
				
				$allPrice = format_number($vouchersPrice + $allFreight);				// 订单需要支付的实付总价
				$derateAllPrice = format_number($derateAllPrice);						// 订单最终的优惠总价
				if($allPrice <= 0){
					// 如果订单总价小于0 则默认最低价格为0.01
					$allPrice = '0.01';
				}
				$bandzpmid = 1;
				// 1、下单的时候判断是否是通过他人推广链接的产生的订单
				// 2、查询这个用户是否已经绑定过展业伙伴的mid
				// 3、这个商品不包含退货退款政策
				// 4、（不可与自身绑定）
				$zpartnerInfo = M('member_wechat_info')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid))->field('id,zpartnermid')->find();
				// 给这个订单标记为展业伙伴推荐人购买的订单
				if($zpartnerInfo['zpartnermid']){
					if($good['backorderpolicyset'] == ',' && session('zpartnermid') != $this->mid){
						// 获取这个展业伙伴的账号状态（非清退以及已审核）
						$zpartnerStatus = M('mall_exhibition_partner_list')->where(array('companyid'=>$this->companyid,'mid'=>$zpartnerInfo['zpartnermid'],'isclear'=>2,'status'=>2))->count();
						if($zpartnerStatus){
							$orderInfo['iszorder'] = 1; 	// 是否为展业订单：1：是；2:否；默认：2；
						}
					}
				}elseif(!$zpartnerInfo['zpartnermid']){
					if(session('zpartnermid') && $good['backorderpolicyset'] == ',' && session('zpartnermid') != $this->mid){
						// 查询这个人是否下过单 如果有则不再给这个人绑定展业关系，只有新用户从未下单的才能绑定关系
						$zOrderCount = M('mall_order_info')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'orderstatus'=>4))->count();
						if(!$zOrderCount){
							// 给此购买人绑定展业伙伴的mid
							$wechatData['zpartnermid'] = session('zpartnermid');
							$wechatData['updatetime'] = $time;
							$bandzpmid = M('member_wechat_info')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid))->save($wechatData);
						}
						// 获取这个展业伙伴的账号状态（非清退以及已审核）
						$zpartnerStatus = M('mall_exhibition_partner_list')->where(array('companyid'=>$this->companyid,'mid'=>session('zpartnermid'),'isclear'=>2,'status'=>2))->count();
						if($zpartnerStatus){
							$orderInfo['iszorder'] = 1; 	// 是否为展业订单：1：是；2:否；默认：2；
						}
					}
				}
				// 订单表主表
				$orderpaymethod = '1';  //付款方式 :1、微信支付；
				$orderInfo['id'] = $orderInfoReturn = guidNow();							// 订单id
				$orderInfo['companyid'] = $this->companyid;									// 公司id
				$orderInfo['mid'] = $this->mid;												// 会员id
				$orderInfo['orderid'] = $orderid;											// 订单号
				if($good['goodtype'] == 1){
					$orderInfo['truegoodtype'] = '1';										// 实物商品
				}
				$orderInfo['ordertitle'] = $good['ordertitle']?$good['ordertitle']:$good['title'];								// 订单标题
				$orderInfo['goodtype'] = $good['goodtype'];									// 商品类型
				$orderInfo['ordertype'] = $good['goodtype'];								// 订单类型
				$orderInfo['orderstatus'] = '1';											// 订单状态
				$orderInfo['temporderstatus'] = '1';										// 订单临时状态
				$orderInfo['orderprice'] = $allPrice;										// 订单实付金额
				$orderInfo['orderderateprice'] = $derateAllPrice; 							// 订单优惠金额
				$orderInfo['orderint'] = '0.00'; 											// 积分
				$orderInfo['orderfreight'] = $allFreight?$allFreight:'0.00'; 				// 订单运费
				$orderInfo['orderweight'] = $allWeight?$allWeight:'0'; 						// 订单重量
				$orderInfo['orderpaymethod'] = $orderpaymethod;								// 订单支付方式：1：微信支付；7：储值支付；
				$orderInfo['orderinvoice'] = $this->_post('orderinvoice');					// 是否需要发票
				$orderInfo['orderinvoicetitle'] = $this->_post('orderinvoicetitle');		// 发票抬头
				$orderInfo['eshopdiscounttitle'] = $good['ED']['title'];					// 整单优惠活动名称
				$orderInfo['eshopdiscountmoney'] = $good['ED']['discountPrice'];			// 整单优惠活动优惠总金额
				$orderInfo['membernote'] = $this->_post('membernote')?$this->_post('membernote'):'';		// 买家留言
				// 查询出设置订单自动取消的截止时间
				$mallorderautoset = M('company')->where(array('id'=>$this->companyid))->field('id,mallorderautoset')->find();
				$orderInfo['ordernopayendtime'] = $time + ($mallorderautoset['mallorderautoset']*3600); 	// 订单未付款通知的截止时间
				$orderInfo['issendmessage'] = 2; 															// 是否已经发送未付款通知消息
				$orderInfo['updatetime'] = $orderInfo['createtime'] = $time;
				M('mall_order_info')->add($orderInfo);
				// 订单商品表
				$orderGoodsData['id'] = guidNow();											// 订单商品id
				$orderGoodsData['companyid'] = $this->companyid;							// 公司id
				$orderGoodsData['mid'] = $this->mid;										// 会员id
				$orderGoodsData['orderid'] = $orderid;										// 订单号
				$orderGoodsData['goodtype'] = $good['goodtype'];							// 商品类型
				$orderGoodsData['vouchersid'] = 1;											// 优惠券id
				$orderGoodsData['prefix'] = '';				 								// 卡券前缀（废除）
				$orderGoodsData['pricetype'] = $good['pricetype']; 							// 定价策略
				$orderGoodsData['goodid'] = $goodsid;										// 商品id
				$orderGoodsData['goodname'] = $good['title'];								// 商品名称
				$orderGoodsData['goodpic'] = $good['pic'];									// 商品图片
				$orderGoodsData['goodprice'] = $good['saleprice'];							// 商品价格
				$orderGoodsData['goodint'] = $good['intprice'];								// 积分价格
				$orderGoodsData['goodnum'] = $goodnum;										// 商品数量
				$orderGoodsData['goodskuid'] = $goodskuid?$goodskuid:'';					// 商品skuid
				$orderGoodsData['goodweight'] = 1;										// 商品重量
				$orderGoodsData['usetimelimittype'] = $good['usetimelimittype'];			// 卡券有效期类型
				$orderGoodsData['usetimelimitset'] = $good['usetimelimitset'];				// 卡券有效期设置
				$orderGoodsData['useshopslimitset'] = $good['useshopslimitset'];			// 卡券使用门店设置
				$orderGoodsData['backorderpolicyset'] = $good['backorderpolicyset'];		// 退单政策
				$orderGoodsData['useinfo'] = $good['useinfo'];								// 卡券使用说明
				$orderGoodsData['goodskuname'] = $mallgoodssku['name'];						// 商品sku名称
				$orderGoodsData['updatetime'] = $orderGoodsData['createtime'] = time();
				$orderGoodsReturn = M('mall_order_goods')->add($orderGoodsData);
	
				// 从库存减掉商品购买数量
				if($good['goodtype'] == 1 || $good['goodtype'] == 3 || $good['goodtype'] == 4 || $good['goodtype'] == 5){
					M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'id'=>$goodskuid))->setDec('stockamount',$goodnum);
				}
				$stockamountReturn = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$goodsid))->setDec('stockamount',$goodnum);
	
				//已售罄：当商品库存为：0时，改变商品状态为：已售罄
				$issoldout = 1;
				$stockamount = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$goodsid))->getField('stockamount');
				if($stockamount < 1){
					$issoldout = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$goodsid))->save(array('issoldout'=>1));
				}
				//我的电子券
				$vouchersiduse = 1;
				if($vouchersid){
					//获取优惠券使用状态
					$vouchersIsUsed = M('member_vouchers')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'id'=>$vouchersid))->getField('isused');
					if($vouchersIsUsed == 2){
						$sn =  M('member_vouchers')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'id'=>$vouchersid))->getField('sn');
						$option['vouchertype'] = 1;
						$option['vouchernumber'] = $sn;
						$option['cid'] = $this->companyid;
						$option['usetype'] = 4;
						$option['users'] =  1;
						$option['getway'] = 2;
						$vouchersiduse = $this->verificationVouchersSCRM5($option);
					}else{
						$ajaxReturn['msg'] = '优惠券已经被使用，请重新选择';
						echo json_encode($ajaxReturn);
						exit();
					}
				}
				if($orderInfoReturn && $orderGoodsReturn && $vouchersiduse && $bandzpmid){
					M()->commit();
					$ajaxReturn['code'] = 200;
					$ajaxReturn['msg'] = '订单提交成功';
					$ajaxReturn['id'] = $orderInfoReturn;
					$ajaxReturn['orderid'] = $orderid;
				}else{
					M()->rollback();
					$ajaxReturn['msg'] = '订单提交失败，请稍候重试';
					if($isDispatching == 2){
						$ajaxReturn['msg'] = '该地区暂时不支持配送';
					}
				}
				
			}
		}
		echo json_encode($ajaxReturn);
	}
	/**
	 *	选择支付方式
	 */
	public function selectPayType(){
	    $this->checkMemberAutoLogin();
		$orderid = $this->_request('orderid');
		$this->assign('orderid',$orderid);
		$this->display();
	}
	/**
	 * 购物车提交 确认订单
	 */
	public function createOrder(){
		$this->setPageTitle(array('title'=>'确认订单'));
		if(!$this->mid){
			$this->checkMemberLoginBox();
		}
		if($_GET['id']){
			$time = time();
			$orderInfo['id'] = $_GET['id'];
			$orderInfo['goodsPrice'] = '0.00'; 	  // 商品总金额
			$orderInfo['orderFreight'] = '0.00';  // 运费
			$orderInfo['eshopDiscount'] = '0.00'; // 整单优惠活动金额
			$orderInfo['allPrice'] = '0.00'; 	  // 实际支付金额
			$orderInfo['allGoodsNum'] = '0'; 	  // 整单优惠条件判断（满件优惠）
			$orderInfo['vouchersCount'] = '0';    // 优惠券个数
			$orderInfo['address'] = M('member_shop_address')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'isdefault'=>1))->field('id,name,mobile,province,city,district,address,isdefault')->find();
			
			$goodSpdm = ''; // 商品编码（用于整单优惠）
			$this->goodType1Num = '0'; // 实物商品个数（用于运费）
			$this->goodsIdString = ''; // 商品id（用于收货地址）
			$cartList = M('mall_shopping_cart')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'id'=>array('in', $_GET['id'])))->field('goodid,goodskuid,goodnum')->select();
			foreach($cartList as $key=>$val){
				$list[$key]['good'] = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$val['goodid'],'isoffshelves'=>2,'issoldout'=>2))->field('id,goodtype,title,goodnum as spdm,saleprice,freighttype,freighttplid,voucherimgurl')->find();
				
				if($list[$key]['good']['goodtype']==1 || $list[$key]['good']['goodtype']==3 || $list[$key]['good']['goodtype']==4 || $list[$key]['good']['goodtype']==5){
					$mallgoodssku = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$val['goodid'],'id'=>$val['goodskuid']))->field('name,saleprice,imgurl')->find();
					$list[$key]['good']['saleprice'] = format_number($mallgoodssku['saleprice']);
				}
				// 规格
				$list[$key]['good']['skuname'] = $mallgoodssku['name'];
				// 商品图片
				if($list[$key]['good']['goodtype'] == 1){ // 实物商品
        			$list[$key]['good']['pic'] = $mallgoodssku['imgurl'];
					$this->goodType1Num++;
					$this->goodsIdString .= $list[$key]['good']['id'].',';
        		}elseif($list[$key]['good']['goodtype'] == 2){ // 券商品
        			$list[$key]['good']['pic'] = $list[$key]['good']['voucherimgurl'];
        		}else{
        			$list[$key]['good']['pic'] = M('mall_goods_pics')->where(array('companyid'=>$this->companyid,'goodid'=>$val['goodid']))->order('sort')->getField('pic');
        		}
				$list[$key]['good']['goodnum'] = $val['goodnum'];
				$orderInfo['goodsPrice'] += $list[$key]['good']['goodnum']*$list[$key]['good']['saleprice'];
				$goodSpdm .= $list[$key]['good']['spdm'].',';
				$orderInfo['allGoodsNum'] += $list[$key]['good']['goodnum'];
			}
		}
		$orderInfo['goodsPrice'] = format_number($orderInfo['goodsPrice']);
		//******************** 运费 **************************************************************************************
		$this->isDispatching = 1; // 是否可以配送 1、可以；2、不可以；
		if($this->goodType1Num > 0){
			foreach($list as $goodKey=>$goodVal){
				if($goodVal['good']['freighttype'] == 3){
					$listArr[$goodKey] = $goodVal['good']['freighttplid'];
				}
			}
			$listArr = array_merge(array_flip(array_flip($listArr)));
			foreach($listArr as $arrKey=>$arrVal){
				foreach($list as $goodKey2=>$goodVal2){
					if($arrVal == $goodVal2['good']['freighttplid']){
						$listArr2[$arrKey]['freighttplid'] = $goodVal2['good']['freighttplid'];
						$listArr2[$arrKey]['goodnum'] += $goodVal2['good']['goodnum'];
					}
				}
			}
			foreach($listArr2 as $laKey=>$laVal){
				$tplType = M('mall_freight_tpl')->where(array('companyid'=>$this->companyid,'id'=>$laVal['freighttplid']))->getField('type');
				$tplInfo = M('mall_freight_tpl_child')->where(array('companyid'=>$this->companyid,'tplid'=>$laVal['freighttplid'],'areanames'=>array('like','%'.$orderInfo['address']['city'].',%')))->field('firstpiece,continuedpiece,firstheavy,continuedheavy')->find();
				if($tplInfo){
					// 模板类型 1、按件数；2、按重量；
					if($tplType == 1){
						$orderInfo['orderFreight'] += $tplInfo['firstpiece']+$tplInfo['continuedpiece']*($laVal['goodnum']-1);
					}else{
						//$orderInfo['allFreight'] += $tplInfo['firstheavy']+(ceil($orderInfo['allWeight'])-1)*$tplInfo['continuedheavy'];
					}
				}else{
					$this->isDispatching = 2;
				}
			}
		}
		$orderInfo['orderFreight'] = format_number($orderInfo['orderFreight']);
		//******************** 整单优惠活动与参与商品设置 *****************************************************
		$eshopDiscount = M('eshop_discount')->where(array('companyid'=>$this->companyid,'isoff'=>1,'starttime'=>array('lt',$time),'endtime'=>array('gt',$time)))->field('id,title,starttime,endtime,memberclass,type,money,discount,fulljian,fullzhe,fullnumjian,fullnumzhe,isopen,codingno,codingok')->select();
		if($eshopDiscount){
			$goodSpdm = explode(',', substr($goodSpdm, 0,-1));
			$EDdiscountPrice = '100000000.00'; //参与活动后的订单金额
			foreach($eshopDiscount as $edkey=>$edval){
				$isImplement = 1;//1、执行；2、不执行；
				//判断商品是否参与活动
				if($edval['isopen'] == 1){
					$isImplement = 1;
				}elseif($edval['isopen'] == 2){
					$codingno = explode(',', $edval['codingno']);
					if(array_intersect($goodSpdm,$codingno)){
						$isImplement = 2;
					}
				}elseif($edval['isopen'] == 3){
					$codingok = explode(',', $edval['codingok']);
					if(array_intersect($goodSpdm,$codingok)){
						foreach($goodSpdm as $key=>$val){
							if(strstr($edval['codingok'],$val)==FALSE){
								$isImplement = 2;
								continue;
							}
						}
					}else{
						$isImplement = 2;
					}
				}
				//判断商品符合哪种参与人群
				if($isImplement==1 && $edval['memberclass']==2){
					$isOrderCount = M('mall_order_info')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid))->count();
					if($isOrderCount > 0){
						$isImplement = 2;
					}
				}
				//优惠活动
				if($isImplement==1){
					if($edval['type']==1 && $orderInfo['goodsPrice']>$edval['money']){ //立减优惠
						$title = $edval['title'].' 立减'.$edval['money'].'元';
						$discountPrice = format_number($edval['money']);
					}elseif($edval['type'] == 2){ //立折优惠
						$title = $edval['title'].' 立折'.$edval['discount'].'%';
						$discountPrice = format_number($orderInfo['goodsPrice']*($edval['discount']/100));
					}elseif($edval['type'] == 3){ //满减优惠
						$fulljian  = explode('|',$edval['fulljian']);
						foreach($fulljian as $key=>$fjval){
							$fulljianval = explode(',',$fjval);
							if($fulljianval[0] <= $orderInfo['goodsPrice']){
								$title = $edval['title'].' 满'.$fulljianval[0].'减'.$fulljianval[1].'元';
								$discountPrice = format_number($fulljianval[1]);
							}
						}
					}elseif($edval['type'] == 4){ //满折优惠
						$fullzhe = explode('|',$edval['fullzhe']);
						foreach($fullzhe as $key=>$fzval){
							$fullzheval = explode(',',$fzval);
							if($fullzheval[0] <= $orderInfo['goodsPrice']){
								$title = $edval['title'].' 满'.$fullzheval[0].'折'.$fullzheval[1].'%';
								$discountPrice = format_number($orderInfo['goodsPrice']*($fullzheval[1]/100));
							}
						}
					}elseif($edval['type'] == 5){ //满件减优惠
						$fullnumjian = explode('|',$edval['fullnumjian']);
						foreach($fullnumjian as $key=>$fnjval){
							$fullnumjianval = explode(',',$fnjval);
							if($fullnumjianval[0] <= $orderInfo['allGoodsNum']){
								$title = $edval['title'].' 满'.$fullnumjianval[0].'件减'.$fullnumjianval[1].'元';
								$discountPrice = format_number($fullnumjianval[1]);
							}
						}
					}elseif($edval['type'] == 6){ //满件折优惠
						$fullnumzhe = explode('|',$edval['fullnumzhe']);
						foreach($fullnumzhe as $key=>$fnzval){
							$fullnumzheval = explode(',',$fnzval);
							if($fullnumzheval[0] <= $orderInfo['allGoodsNum']){
								$title = $edval['title'].' 满'.$fullnumzheval[0].'件折'.$fullnumzheval[1].'%';
								$discountPrice = format_number($orderInfo['goodsPrice']*($fullnumzheval[1]/100));
							}
						}
					}
					$starttime = format_time($edval['starttime'],'ymdhi');
					$endtime = format_time($edval['endtime'],'ymdhi');
					if($discountPrice < $EDdiscountPrice){
						$EDtitle = $title;
						$EDstarttime = $starttime;
						$EDendtime = $endtime;
						$EDdiscountPrice = $discountPrice;
					}
				}
			}
			if($EDtitle){
				$orderInfo['ED']['title'] = $EDtitle;
				$orderInfo['ED']['starttime'] = $EDstarttime;
				$orderInfo['ED']['endtime'] = $EDendtime;
				$orderInfo['ED']['discountPrice'] = format_number($EDdiscountPrice);
				$orderInfo['eshopDiscount'] = $discountPrice;
			}
		}
		$orderInfo['allPrice'] = format_number($orderInfo['goodsPrice']+$orderInfo['orderFreight']-$orderInfo['eshopDiscount']);
		//******************** 读取可用优惠券 *****************************************************
		$orderPrice = $orderInfo['goodsPrice']-$orderInfo['eshopDiscount'];
		if($orderPrice > 0){
			$where['companyid'] = $this->companyid;
			$where['mid'] = $this->mid;
			$where['isused'] = '2';
			$where['issend'] = '2';
			$where['_string'] = ' (( vouchertype = 7 || (vouchertype = 40 && usescenelimitset like "%,1,%")) AND useendtime >" '. time() . '")';
			if($orderPrice){
				$where['_string'] .= " AND (parvalue <= " . $orderPrice . " || parvalue = '0.00')";
			}
			$orderInfo['vouchersCount'] = M('member_vouchers')->where($where)->count();
			$orderInfo['vouchersList'] = M('member_vouchers')->where($where)->field('id,mid,sn,parvalue,usetimelimittype,useshopslimitset,useinfo,discounttype,minus,discount,fullminus,fulldiscount,eachfullminus,usestarttime,useendtime,vouchername')->order('id DESC')->select();
			foreach($orderInfo['vouchersList'] as $key=>$val){
				if($val['discounttype'] == 1){
					$orderInfo['vouchersList'][$key]['derate'] = format_number($val['minus']);
				}elseif($val['discounttype'] == 2){
					$orderInfo['vouchersList'][$key]['derate'] = '折'.$val['discount'].'%';
				}elseif($val['discounttype'] == 3){
					$shouldPay = explode(',', $val['fullminus']);
					$orderInfo['vouchersList'][$key]['fullPrice'] = $shouldPay[0];
					$orderInfo['vouchersList'][$key]['derate'] = format_number($shouldPay[1]);
				}elseif($val['discounttype'] == 4){
					$shouldPay = explode(',', $val['fulldiscount']);
					$orderInfo['vouchersList'][$key]['fullPrice'] = $shouldPay[0];
					$orderInfo['vouchersList'][$key]['derate'] = '折'.$shouldPay[1].'%';
				}elseif($val['discounttype'] == 5){
					$shouldPay = explode(',', $val['eachfullminus']);
					$orderInfo['vouchersList'][$key]['fullPrice'] = $shouldPay[0];
					$orderInfo['vouchersList'][$key]['derate'] = format_number($shouldPay[1]);
				}
			}
		}
		$this->assign('list', $list);
		$this->assign('orderInfo', $orderInfo);
		$this->assign('isshowsystemdiymen', '2');//隐藏自定义菜单
		$this->display();
	}
	/**
	 * ajax 提交订单
	 */
	public function ajaxCreateOrder(){
		$ajaxReturn['code'] = 300;
		$ajaxReturn['msg'] = '订单提交失败，请您稍后重试';
		
		//-------------------- 变量字段设置 --------------------
		$time = time();
		$orderInfo['goodsPrice'] = '0.00'; // 商品总金额 
		$orderInfo['orderFreight'] = '0.00'; // 运费
		$orderInfo['allGoodsNum'] = '0'; // 整单优惠条件判断（满件优惠）
		$orderInfo['eshopDiscount'] = '0.00'; // 整单优惠活动金额
		$orderInfo['voucherDiscount'] = '0.00'; // 券的优惠金额
		$orderInfo['dmsDiscount'] = '0.00'; // DMS优惠金额
		$orderInfo['allPrice'] = '0.00'; // 实际支付金额
		$goodSpdm = ''; // 商品编码（用于整单优惠）
		$goodType1Num = '0'; // 实物商品个数（用于运费）
		$goodsIdString = ''; // 商品id（用于收货地址）
		$mallShoppingCartNum = '0'; // 判断购买商品数量
		$mallShoppingCartName = ''; // 判断购买商品数量
		$ordertitle = ''; // 订单标题，用于微信支付（订单主表）
		
		$cartList = M('mall_shopping_cart')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'id'=>array('in', $_POST['id'])))->field('id,goodid,goodskuid,goodnum')->select();
		if($cartList){
			foreach($cartList as $key=>$val){
				//-------------------- 购买商品信息 --------------------
				$list[$key]['good'] = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$val['goodid'],'isoffshelves'=>2,'issoldout'=>2))->field('id,goodtype,title,goodnum as spdm,saleprice,freighttype,freighttplid,voucherimgurl,vouchersid,stockamount,usetimelimittype,usetimelimitset,useshopslimitset,backorderpolicyset,useinfo')->find();
				$ordertitle .= $list[$key]['good']['title'];
				$list[$key]['good']['cartId'] = $val['id'];
				if($list[$key]['good']['goodtype']==1 || $list[$key]['good']['goodtype']==3 || $list[$key]['good']['goodtype']==4 || $list[$key]['good']['goodtype']==5){
					$mallgoodssku = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$val['goodid'],'id'=>$val['goodskuid']))->field('id,name,saleprice,imgurl,stockamount')->find();
					$list[$key]['good']['saleprice'] = format_number($mallgoodssku['saleprice']);
					$list[$key]['good']['skuId'] = $mallgoodssku['id'];
					$list[$key]['good']['skuName'] = $mallgoodssku['name'];
					$list[$key]['good']['skuStock'] = $mallgoodssku['stockamount'];
					$ordertitle .= '（'.$mallgoodssku['name'].'）';
				}
				// 商品图片
				if($list[$key]['good']['goodtype'] == 1){ // 实物商品
					$list[$key]['good']['pic'] = $mallgoodssku['imgurl'];
					$goodType1Num++;
					$goodsIdString .= $list[$key]['good']['id'].',';
				}elseif($list[$key]['good']['goodtype'] == 2){ // 券商品
					$list[$key]['good']['pic'] = $list[$key]['good']['voucherimgurl'];
				}else{
					$list[$key]['good']['pic'] = M('mall_goods_pics')->where(array('companyid'=>$this->companyid,'goodid'=>$val['goodid']))->order('sort')->getField('pic');
				}
				$list[$key]['good']['goodnum'] = $val['goodnum'];
				$orderInfo['goodsPrice'] += $list[$key]['good']['goodnum']*$list[$key]['good']['saleprice'];
				$goodSpdm .= $list[$key]['good']['spdm'].',';
				$orderInfo['allGoodsNum'] += $list[$key]['good']['goodnum'];
					
				//-------------------- 判断购买商品数量 --------------------
				if($list[$key]['good']['goodtype']==2 || $list[$key]['good']['goodtype']==6 || $list[$key]['good']['goodtype']==7){
					if($list[$key]['good']['goodnum'] > $list[$key]['good']['stockamount']){
						$mallShoppingCartNum++;
						$mallShoppingCartName = $list[$key]['good']['title'];
						break;
					}
				}else{
					if($list[$key]['good']['goodnum'] > $list[$key]['good']['skuStock']){
						$mallShoppingCartNum++;
						$mallShoppingCartName = $list[$key]['good']['skuName'];
						break;
					}
				}
			}
		}else{
			$ajaxReturn['msg'] = '订单已下单成功，请重新选择商品';
			echo json_encode($ajaxReturn);
			exit();
		}
		if($mallShoppingCartNum > 0){
			$ajaxReturn['msg'] = '商品规格：'.$mallShoppingCartName.'库存不足';
			echo json_encode($ajaxReturn);
			exit();
		}
		
		$isDispatching = 1; // 是否可以配送 1、可以；2、不可以；
		if($goodType1Num > 0){
			//-------------------- 地址收货地址 --------------------
			$address = M('member_shop_address')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'id'=>$_POST['addressid']))->field('name,mobile,province,city,district,address')->find();
			if(!$address){
				$ajaxReturn['msg'] = '请选择您的收货地址';
				echo json_encode($ajaxReturn);
				exit();
			}
			
			//-------------------- 运费 --------------------
			foreach($list as $goodKey=>$goodVal){
				if($goodVal['good']['freighttype'] == 3){
					$listArr[$goodKey] = $goodVal['good']['freighttplid'];
				}
			}
			$listArr = array_merge(array_flip(array_flip($listArr)));
			foreach($listArr as $arrKey=>$arrVal){
				foreach($list as $goodKey2=>$goodVal2){
					if($arrVal == $goodVal2['good']['freighttplid']){
						$listArr2[$arrKey]['freighttplid'] = $goodVal2['good']['freighttplid'];
						$listArr2[$arrKey]['goodnum'] += $goodVal2['good']['goodnum'];
					}
				}
			}
			foreach($listArr2 as $laKey=>$laVal){
				$tplType = M('mall_freight_tpl')->where(array('companyid'=>$this->companyid,'id'=>$laVal['freighttplid']))->getField('type');
				$tplInfo = M('mall_freight_tpl_child')->where(array('companyid'=>$this->companyid,'tplid'=>$laVal['freighttplid'],'areanames'=>array('like','%'.$address['city'].',%')))->field('firstpiece,continuedpiece,firstheavy,continuedheavy')->find();
				if($tplInfo){
					// 模板类型 1、按件数；2、按重量；
					if($tplType == 1){
						$orderInfo['orderFreight'] += $tplInfo['firstpiece']+$tplInfo['continuedpiece']*($laVal['goodnum']-1);
					}else{
						//$orderInfo['allFreight'] += $tplInfo['firstheavy']+(ceil($orderInfo['allWeight'])-1)*$tplInfo['continuedheavy'];
					}
				}else{
					$isDispatching = 2;
				}
			}
		}
		$orderInfo['orderFreight'] = format_number($orderInfo['orderFreight']);
		//-------------------- 整单优惠 --------------------
		$eshopDiscount = M('eshop_discount')->where(array('companyid'=>$this->companyid,'isoff'=>1,'starttime'=>array('lt',$time),'endtime'=>array('gt',$time)))->field('id,title,starttime,endtime,memberclass,type,money,discount,fulljian,fullzhe,fullnumjian,fullnumzhe,isopen,codingno,codingok')->select();
		if($eshopDiscount){
			$goodSpdm = explode(',', substr($goodSpdm, 0,-1));
			$EDdiscountPrice = '100000000.00'; //参与活动后的订单金额
			foreach($eshopDiscount as $edkey=>$edval){
				$isImplement = 1;//1、执行；2、不执行；
				//判断商品是否参与活动
				if($edval['isopen'] == 1){
					$isImplement = 1;
				}elseif($edval['isopen'] == 2){
					$codingno = explode(',', $edval['codingno']);
					if(array_intersect($goodSpdm,$codingno)){
						$isImplement = 2;
					}
				}elseif($edval['isopen'] == 3){
					$codingok = explode(',', $edval['codingok']);
					if(array_intersect($goodSpdm,$codingok)){
						foreach($goodSpdm as $key=>$val){
							if(strstr($edval['codingok'],$val)==FALSE){
								$isImplement = 2;
								continue;
							}
						}
					}else{
						$isImplement = 2;
					}
				}
				//判断商品符合哪种参与人群
				if($isImplement==1 && $edval['memberclass']==2){
					$isOrderCount = M('mall_order_info')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid))->count();
					if($isOrderCount > 0){
						$isImplement = 2;
					}
				}
				//优惠活动
				if($isImplement==1){
					if($edval['type']==1 && $orderInfo['goodsPrice']>$edval['money']){ //立减优惠
						$title = $edval['title'].' 立减'.$edval['money'].'元';
						$discountPrice = format_number($edval['money']);
					}elseif($edval['type'] == 2){ //立折优惠
						$title = $edval['title'].' 立折'.$edval['discount'].'%';
						$discountPrice = format_number($orderInfo['goodsPrice']*($edval['discount']/100));
					}elseif($edval['type'] == 3){ //满减优惠
						$fulljian  = explode('|',$edval['fulljian']);
						foreach($fulljian as $key=>$fjval){
							$fulljianval = explode(',',$fjval);
							if($fulljianval[0] <= $orderInfo['goodsPrice']){
								$title = $edval['title'].' 满'.$fulljianval[0].'减'.$fulljianval[1].'元';
								$discountPrice = format_number($fulljianval[1]);
							}
						}
					}elseif($edval['type'] == 4){ //满折优惠
						$fullzhe = explode('|',$edval['fullzhe']);
						foreach($fullzhe as $key=>$fzval){
							$fullzheval = explode(',',$fzval);
							if($fullzheval[0] <= $orderInfo['goodsPrice']){
								$title = $edval['title'].' 满'.$fullzheval[0].'折'.$fullzheval[1].'%';
								$discountPrice = format_number($orderInfo['goodsPrice']*($fullzheval[1]/100));
							}
						}
					}elseif($edval['type'] == 5){ //满件减优惠
						$fullnumjian = explode('|',$edval['fullnumjian']);
						foreach($fullnumjian as $key=>$fnjval){
							$fullnumjianval = explode(',',$fnjval);
							if($fullnumjianval[0] <= $orderInfo['allGoodsNum']){
								$title = $edval['title'].' 满'.$fullnumjianval[0].'件减'.$fullnumjianval[1].'元';
								$discountPrice = format_number($fullnumjianval[1]);
							}
						}
					}elseif($edval['type'] == 6){ //满件折优惠
						$fullnumzhe = explode('|',$edval['fullnumzhe']);
						foreach($fullnumzhe as $key=>$fnzval){
							$fullnumzheval = explode(',',$fnzval);
							if($fullnumzheval[0] <= $orderInfo['allGoodsNum']){
								$title = $edval['title'].' 满'.$fullnumzheval[0].'件折'.$fullnumzheval[1].'%';
								$discountPrice = format_number($orderInfo['goodsPrice']*($fullnumzheval[1]/100));
							}
						}
					}
					$starttime = format_time($edval['starttime'],'ymdhi');
					$endtime = format_time($edval['endtime'],'ymdhi');
					if($discountPrice < $EDdiscountPrice){
						$EDtitle = $title;
						$EDstarttime = $starttime;
						$EDendtime = $endtime;
						$EDdiscountPrice = $discountPrice;
					}
				}
			}
			if($EDtitle){
				$orderInfo['ED']['title'] = $EDtitle;
				$orderInfo['ED']['starttime'] = $EDstarttime;
				$orderInfo['ED']['endtime'] = $EDendtime;
				$orderInfo['ED']['discountPrice'] = format_number($EDdiscountPrice);
				$orderInfo['eshopDiscount'] = $discountPrice;
			}
		}
		//-------------------- Eshop优惠券 --------------------
		$orderInfo['allPrice'] = $orderInfo['goodsPrice']-$orderInfo['eshopDiscount'];
		if($_POST['vouchersid']){
			$vouchersInfo = M('member_vouchers')->where(array('companyid'=>$this->companyid,'id'=>$_POST['vouchersid']))->field('id,mid,sn,parvalue,usetimelimittype,useshopslimitset,useinfo,discounttype,minus,discount,fullminus,fulldiscount,eachfullminus,usestarttime,useendtime,vouchername')->find();
			if($vouchersInfo){
				if($vouchersInfo['discounttype'] == '1'){ // 立减
					$deratePrice = $vouchersInfo['minus'];
				}elseif($vouchersInfo['discounttype'] == '2'){ // 立折
					$deratePrice = $orderInfo['allPrice']*($vouchersInfo['discount']/100);
				}elseif($vouchersInfo['discounttype'] == '3'){ // 满减
					$shouldPay = explode(',', $vouchersInfo['fullminus']);
					if($orderInfo['allPrice'] >= $shouldPay[0]){
						$deratePrice = $shouldPay[1];
					}
				}elseif($vouchersInfo['discounttype'] == '4'){ // 满折
					$shouldPay = explode(',', $vouchersInfo['fulldiscount']);
					if($orderInfo['allPrice'] >= $shouldPay[0]){
						$deratePrice = $orderInfo['allPrice']*($shouldPay[1]/100);
					}
				}elseif($vouchersInfo['discounttype'] == '5'){ // 每满减
					$shouldPay = explode(',', $vouchersInfo['eachfullminus']);
					$deratePrice = (floor($orderInfo['allPrice']/$shouldPay[0]))*$shouldPay[1];
				}
				$orderInfo['voucherDiscount'] = $deratePrice;
			}
		}
		//-------------------- 优惠口令 --------------------
		$orderInfo['allPrice'] = $orderInfo['allPrice'] - $orderInfo['voucherDiscount'];
		if($orderInfo['allPrice'] <= 0){
			$orderInfo['allPrice'] = '0.01';
		}
		if($_POST['dmsDiscoukeyId']){
			$info = M('dms_discoukey')->where(array('companyid'=>$this->companyid,'appscene'=>array('like','%,1,%'),'id'=>$_POST['dmsDiscoukeyId'],'endtime'=>array('gt',time())))->field('id,discoukey,discoutype,discoumoney2,startdiscoumoney3,discoumoney3,discouratio4,startdiscoumoney5,discouratio5,giftname6,startdiscoumoney7,giftname7,startdiscoumoney8,discoumoney8')->find();
			if($info){
				if($info['discoutype'] == 1){ // 无优惠
					$return['tips'] = "本优惠口令无优惠";
				}elseif($info['discoutype'] == 2){ // 立减优惠
					$discount = "立减".$info['discoumoney2'];
					$utility = $info['discoumoney2'];
				}elseif($info['discoutype'] == 3){ // 满减优惠
					$discount = "满".$info['startdiscoumoney3']."立减".$info['discoumoney3'];
					if($orderInfo['allPrice'] >= $info['startdiscoumoney3']){
						$utility = $info['discoumoney3'];
					}else{
						$utility = "0.00";
					}
				}elseif($info['discoutype'] == 4){ // 立折优惠
					$discount = "立折优惠".$info['discouratio4']."%";
					$utility = $orderInfo['allPrice']*($info['discouratio4']/100);
				}elseif($info['discoutype'] == 5){ // 满折优惠
					$discount = "满".$info['startdiscoumoney5']."立折".$info['discouratio5']."%";
					if($orderInfo['allPrice'] >= $info['startdiscoumoney5']){
						$utility = $orderInfo['allPrice']*($info['discouratio5']/100);
					}else{
						$utility = "0.00";
					}
				}elseif($info['discoutype'] == 6){ // 礼品赠送
					$discount = $info['giftname6'];
					$utility = $info['giftname6'];
				}elseif($info['discoutype'] == 7){ // 礼品满赠
					$discount = "满".$info['startdiscoumoney7']."赠送".$info['giftname7'];
					if($orderInfo['allPrice'] >= $info['startdiscoumoney7']){
						$utility = $info['giftname7'];
					}else{
						$utility = "0.00";
					}
				}elseif($info['discoutype'] == 8){ // 每满减优惠
					$discount = "每满".$info['startdiscoumoney8']."立减".$info['discoumoney8'];
					if($orderInfo['allPrice'] >= $info['startdiscoumoney8']){
						$utility = (floor($orderInfo['allPrice']/$info['startdiscoumoney8']))*$info['discoumoney8'];
					}else{
						$utility = "0.00";
					}
				}
			}
			$orderInfo['dmsDiscount'] = format_number($utility);
		}
		$orderInfo['allPrice'] = $orderInfo['allPrice']-$orderInfo['dmsDiscount'];
		if($orderInfo['allPrice'] <= 0){
			$orderInfo['allPrice'] = '0.01';
		}
		$orderInfo['allPrice'] = $orderInfo['allPrice'] + $orderInfo['orderFreight'];
		//-------------------- 订单附表 --------------------
		$orderGoodsCount = '0';
		$orderid = $this->newOrderID('2', 'E', $this->companyid);
		//-------------------- 判断是否符合展业关系 --------------------
		$z = 2; // 1：是；2：否；
		foreach($list as $goodsKey=>$goodsVal){
			$orderGoodsDate['id'] = guidNow();
			$orderGoodsDate['companyid'] = $this->companyid;
			$orderGoodsDate['mid'] = $this->mid;
			$orderGoodsDate['orderid'] = $orderid;
			$orderGoodsDate['goodtype'] = $goodsVal['good']['goodtype'];
			$orderGoodsDate['vouchersid'] = $goodsVal['good']['vouchersid'];
			//$orderGoodsDate['prefix'] = '';
			$orderGoodsDate['pricetype'] = '1';
			$orderGoodsDate['goodid'] = $goodsVal['good']['id'];
			$orderGoodsDate['goodname'] =  $goodsVal['good']['title'];
			$orderGoodsDate['goodpic'] = $goodsVal['good']['pic'];
			$orderGoodsDate['goodprice'] = $goodsVal['good']['saleprice'];
			//$orderGoodsDate['goodint'] = '';
			$orderGoodsDate['goodnum'] = $goodsVal['good']['goodnum'];
			$orderGoodsDate['goodskuid'] = $goodsVal['good']['skuId'] ? $goodsVal['good']['skuId'] : '';
			//$orderGoodsDate['goodweight'] = $list[$key]['good']['weight'];
			$orderGoodsDate['usetimelimittype'] = $goodsVal['good']['usetimelimittype'];
			$orderGoodsDate['usetimelimitset'] = $goodsVal['good']['usetimelimitset'];
			$orderGoodsDate['useshopslimitset'] = $goodsVal['good']['useshopslimitset'];
			$orderGoodsDate['backorderpolicyset'] = $goodsVal['good']['backorderpolicyset'];
			$orderGoodsDate['useinfo'] = $goodsVal['good']['useinfo'];
			$orderGoodsDate['goodskuname'] = $goodsVal['good']['skuName'];
			$orderGoodsDate['updatetime'] = $orderGoodsDate['createtime'] = $time;
			$orderGoodsReturn = M('mall_order_goods')->add($orderGoodsDate);
			//从库存减掉商品购买数量
			$skustockamountReturn = 1;
			$stockamountReturn = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$goodsVal['good']['id']))->setDec('stockamount', $goodsVal['good']['goodnum']);
			if($goodsVal['good']['goodtype']==1 || $goodsVal['good']['goodtype']==3 || $goodsVal['good']['goodtype']==4 || $goodsVal['good']['goodtype']==5){
				$skustockamountReturn = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'id'=>$goodsVal['good']['skuId']))->setDec('stockamount', $goodsVal['good']['goodnum']); // sku减库存
				$stockamount = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$goodsVal['good']['id']))->sum('stockamount');
			}else{
				$stockamount = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$goodsVal['good']['id']))->getField('stockamount');
			}
			//已售罄：当商品库存为：0时，改变商品状态为：已售罄
			$issoldout = 1;
			if($stockamount < 1){
				$issoldout = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$goodsVal['good']['id']))->save(array('issoldout'=>1));
			}
			$shoppingCardDelete = M('mall_shopping_cart')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'id'=>$goodsVal['good']['cartId']))->delete();
			if($orderGoodsReturn && $stockamountReturn && $skustockamountReturn && $issoldout && $shoppingCardDelete){
				$orderGoodsCount++;
			}
			//-------------------- 判断是否符合展业关系 --------------------
			if($goodsVal['good']['backorderpolicyset'] == ','){
				$z = 1;
			}
		}
		$bandzpmid = 1;
		// 1、下单的时候判断是否是通过他人推广链接的产生的订单
		// 2、查询这个用户是否已经绑定过展业伙伴的mid
		// 3、这个商品不包含退货退款政策
		// 4、（不可与自身绑定）
		$zpartnerInfo = M('member_wechat_info')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid))->field('id,zpartnermid')->find();
		// 给这个订单标记为展业伙伴推荐人购买的订单
		if($zpartnerInfo['zpartnermid']){
			if($z== '1' && session('zpartnermid') != $this->mid){
				// 获取这个展业伙伴的账号状态（非清退以及已审核）
				$zpartnerStatus = M('mall_exhibition_partner_list')->where(array('companyid'=>$this->companyid,'mid'=>$zpartnerInfo['zpartnermid'],'isclear'=>2,'status'=>2))->count();
				if($zpartnerStatus){
					$orderInfo['iszorder'] = 1; 	// 是否为展业订单：1：是；2:否；默认：2；
				}
			}
		}elseif(!$zpartnerInfo['zpartnermid']){
			if(session('zpartnermid') && $z== '1' && session('zpartnermid') != $this->mid){
				// 查询这个人是否下过单 如果有则不再给这个人绑定展业关系，只有新用户从未下单的才能绑定关系
				$zOrderCount = M('mall_order_info')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'orderstatus'=>4))->count();
				if(!$zOrderCount){
					// 给此购买人绑定展业伙伴的mid
					$wechatData['zpartnermid'] = session('zpartnermid');
					$wechatData['updatetime'] = $time;
					$bandzpmid = M('member_wechat_info')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid))->save($wechatData);
				}
				// 获取这个展业伙伴的账号状态（非清退以及已审核）
				$zpartnerStatus = M('mall_exhibition_partner_list')->where(array('companyid'=>$this->companyid,'mid'=>session('zpartnermid'),'isclear'=>2,'status'=>2))->count();
				if($zpartnerStatus){
					$orderInfo['iszorder'] = 1; 	// 是否为展业订单：1：是；2:否；默认：2；
				}
			}
		}
		//-------------------- 订单主表 --------------------
		$orderInfo['id'] = guidNow();
		$orderInfo['companyid'] = $this->companyid;
		$orderInfo['mid'] = $this->mid;
		$orderInfo['orderid'] = $orderid;
		$orderInfo['ordertitle'] = $ordertitle;
		if($goodType1Num > 0){
			$orderInfo['ordertype'] = 1;
		}
		$orderInfo['orderstatus'] = 1;
		$orderInfo['temporderstatus'] = 1;
		$orderInfo['orderprice'] = $orderInfo['allPrice'];
		$orderInfo['orderderateprice'] = $orderInfo['eshopDiscount']+$orderInfo['voucherDiscount']+$orderInfo['dmsDiscount'];
		$orderInfo['ordersubtotal'] = $orderInfo['goodsPrice']+$orderInfo['orderFreight'];
		$orderInfo['orderfreight'] = $orderInfo['orderFreight'];
		$orderInfo['orderinvoice'] = $this->_post('orderinvoice');
		$orderInfo['orderinvoicetitle'] = $this->_post('orderinvoicetitle');
		$orderInfo['consigneename'] = $address['name'] ? $address['name'] : '';
		$orderInfo['consigneephone'] = $address['mobile'] ? $address['mobile'] : '';
		$orderInfo['consigneeaddress'] = $address['province'] ? $address['province'].$address['city'].$address['district'].$address['address'] : '';
		$orderInfo['membernote'] = $this->_post('membernote');
		$orderInfo['eshopdiscounttitle'] = $orderInfo['ED']['title'];
		$orderInfo['eshopdiscountmoney'] = $orderInfo['eshopDiscount'];
		$orderInfo['vouchertitle'] = $vouchersInfo['vouchername'];
		$orderInfo['vouchermoney'] = $orderInfo['voucherDiscount'];
		//查询出设置订单自动取消的截止时间
		$mallorderautoset = M('company')->where(array('id'=>$this->companyid))->field('id,mallorderautoset')->find();
		$orderInfo['ordernopayendtime'] = $time + ($mallorderautoset['mallorderautoset']*3600); //订单未付款通知的截止时间
		$orderInfo['issendmessage'] = 2; //是否已经发送未付款通知消息
		$orderInfo['updatetime'] = $orderInfo['createtime'] = $time;
		$orderInfoReturn = M('mall_order_info')->add($orderInfo);
		if($isDispatching==1 && $orderInfoReturn && $orderGoodsCount==count($list) && $bandzpmid){
			M()->commit();
			$ajaxReturn['code'] = 200;
			$ajaxReturn['msg'] = '订单提交成功';
			$ajaxReturn['id'] = $orderInfoReturn;
			$ajaxReturn['orderid'] = $orderid;
			//-------------------- 券核销 --------------------
			$vouchersIsUsed = M('member_vouchers')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'id'=>$_POST['vouchersid']))->getField('isused');
			if($vouchersIsUsed == 2){
				$sn = M('member_vouchers')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'id'=>$_POST['vouchersid']))->getField('sn');
				$option['vouchertype'] = 1;
				$option['vouchernumber'] = $sn;
				$option['cid'] = $this->companyid;
				$option['usetype'] = 4;
				$option['users'] =  1;
				$option['getway'] = 2;
				$this->verificationVouchersSCRM5($option);
			}
			//-------------------- 优惠口令 --------------------
			$dmsOrderInfoReturn = 1;
			$dmsCustomerSuc = 1;
			if($_POST['dmsDiscoukeyId']){
				$discoukeyInfo = M('dms_discoukey')->where(array('id'=>$_POST['dmsDiscoukeyId'],'endtime'=>array('gt',$time)))->field('id,discoukey,discoutype,discoumoney2,startdiscoumoney3,discoumoney3,discouratio4,startdiscoumoney5,discouratio5,giftname6,startdiscoumoney7,giftname7,startdiscoumoney8,discoumoney8,wageratio')->find();
				if($discoukeyInfo){
					$orderprice = $orderInfo['goodsPrice']-$orderInfo['eshopDiscount']-$orderInfo['voucherDiscount'];
					if($discoukeyInfo['discoutype'] == 2){
						$dmsOrderInfo['discoumoney'] = $discoukeyInfo['discoumoney2'];//优惠金额
					}elseif($discoukeyInfo['discoutype'] == 3){
						$dmsOrderInfo['startdiscoumoney'] = $discoukeyInfo['startdiscoumoney3'];//开始优惠
						$dmsOrderInfo['discoumoney'] = $discoukeyInfo['discoumoney3'];//优惠金额
					}elseif($discoukeyInfo['discoutype'] == 4){
						//$dmsOrderInfo['discoumoney'] = $orderprice/(1-$discoukeyInfo['discouratio4']/100)*($discoukeyInfo['discouratio4']/100);//优惠金额
						$dmsOrderInfo['discoumoney'] = format_number($orderprice/($discoukeyInfo['discouratio4']/100));//优惠金额
						$dmsOrderInfo['discouratio'] = $discoukeyInfo['discouratio4'];//优惠比例
					}elseif($discoukeyInfo['discoutype'] == 5){
						$dmsOrderInfo['startdiscoumoney'] = $discoukeyInfo['startdiscoumoney5'];//开始优惠
						//$dmsOrderInfo['discoumoney'] = $orderprice/(1-$discoukeyInfo['discouratio5']/100)*($discoukeyInfo['discouratio5']/100);//优惠金额
						$dmsOrderInfo['discoumoney'] = format_number($orderprice/($discoukeyInfo['discouratio5']/100));//优惠金额
						$dmsOrderInfo['discouratio'] = $discoukeyInfo['discouratio5'];//优惠比例
					}elseif($discoukeyInfo['discoutype'] == 6){
						$dmsOrderInfo['giftname'] = $discoukeyInfo['giftname6'];//礼品名称
						$dmsOrderInfo['discoumoney'] = 0.00;//优惠金额
					}elseif($discoukeyInfo['discoutype'] == 7){
						$dmsOrderInfo['startdiscoumoney'] = $discoukeyInfo['startdiscoumoney7'];//开始优惠
						$dmsOrderInfo['giftname'] = $discoukeyInfo['giftname7'];//礼品名称
						$dmsOrderInfo['discoumoney'] = 0.00;//优惠金额
					}elseif($discoukeyInfo['discoutype'] == 8){ // 每满减优惠
						$dmsOrderInfo['startdiscoumoney'] = $discoukeyInfo['startdiscoumoney8'];//开始优惠
						if($orderprice >= $discoukeyInfo['startdiscoumoney8']){
							$dmsOrderInfo['discoumoney'] = '0.00';
							$dmsOrderInfo['discoumoney'] = (floor($orderprice/$discoukeyInfo['startdiscoumoney8']))*$discoukeyInfo['discoumoney8'];
						}else{
							$dmsOrderInfo['discoumoney'] = 0.00;//优惠金额
						}
					}
					$orderInfo['orderprice'] = $orderprice - $dmsOrderInfo['discoumoney'];
					$dmsOrderInfo['id'] = guidNow();
					$dmsOrderInfo['companyid'] = $this->companyid;
					$dmsOrderInfo['mid'] = $this->mid;
					$dmsOrderInfo['keyid'] = $_POST['dmsDiscoukeyId'];
					$dmsOrderInfo['orderid'] = $orderid;
					$dmsOrderInfo['ordermoney'] = $orderInfo['goodsPrice']+$orderInfo['orderFreight'];
					$dmsOrderInfo['paymoney'] = $orderInfo['orderprice']+$orderInfo['orderFreight'];
					$dmsOrderInfo['rednmoney'] = $dmsOrderInfo['discoumoney'];
					$dmsOrderInfo['wagesmoney'] = $dmsOrderInfo['paymoney']*($discoukeyInfo['wageratio']/100);
					$dmsOrderInfo['wagesprop'] = $discoukeyInfo['wageratio'];
					$dmsOrderInfo['discoukey'] = $discoukeyInfo['discoukey'];
					$dmsOrderInfo['discoutype'] = $discoukeyInfo['discoutype'];
					$dmsOrderInfo['orderstatus'] = 1;
					$dmsOrderInfo['ordertype'] = 1;
					$dmsOrderInfo['updatetime'] = $dmsOrderInfo['createtime'] = $time;
					$dmsOrderInfoReturn = M('dms_order')->add($dmsOrderInfo);
					
					//增加客户
					$customerCou = M('dms_customer')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'keyid'=>$_POST['dmsDiscoukeyId']))->count();
					if(!$customerCou){
						$customerData['id'] = guidNow();
						$customerData['companyid'] = $this->companyid;
						$customerData['mid'] = $this->mid;
						$customerData['keyid'] = $_POST['dmsDiscoukeyId'];
						$customerData['createtime'] = $customerData['updatetime'] = $time;
						$customerSuc = M('dms_customer')->add($customerData);
						//累计客户数
						$discoukeySuc = M('dms_discoukey')->where(array('companyid'=>$this->companyid,'id'=>$_POST['dmsDiscoukeyId']))->setInc('totalpeoplesum');
						M('dms_discoukey')->where(array('companyid'=>$this->companyid,'id'=>$_POST['dmsDiscoukeyId']))->setInc('thismonthpeoplesum');
						if(!$customerSuc || !$discoukeySuc){
							$dmsCustomerSuc = 0;
						}
					}
				}
			}
		}else{
			M()->rollback();
			$ajaxReturn['msg'] = '订单提交失败，请稍候重试';
		}
		echo json_encode($ajaxReturn);
	}
    /**
     * SCRM（使用优惠券）
     * ajax 异步使用优惠券 存在优惠口令则将优惠口令价格重新计算一遍
     * @author Thomas<416369046@qq.com>
     * @since  2017-1-17
     */
    public function ajaxUseVouchers(){
    	$vouchersid = $this->_post('vouchersid');
    	$allPrice = $this->_post('allPrice');
    	$discoukey = $this->_post('discoukey');
    	$allFreight = $this->_post("allFreight");
    	$discountPrice = $this->_post("discountPrice");
    	$where['companyid'] = $this->companyid;
    	$where['id'] = $vouchersid;
    	//优惠券
    	$vouchersInfo = M('member_vouchers')->where($where)->field('id,mid,sn,parvalue,usetimelimittype,useshopslimitset,useinfo,discounttype,minus,discount,fullminus,fulldiscount,eachfullminus,usestarttime,useendtime,vouchername')->find();
    	if($vouchersInfo){
	    	if($vouchersInfo['discounttype'] == '1'){
	    		//立减
	    		$deratePrice = $vouchersInfo['minus'];
	    	}elseif($vouchersInfo['discounttype'] == '2'){
	    		//立折
	    		$deratePrice = $allPrice*($vouchersInfo['discount']/100);
	    	}elseif($vouchersInfo['discounttype'] == '3'){
	    		//满减
	    		$shouldPay = explode(',', $vouchersInfo['fullminus']);
	    		if($allPrice >= $shouldPay[0]){
	    			$deratePrice = $shouldPay[1];
	    		}
	    	}elseif($vouchersInfo['discounttype'] == '4'){
	    		//满折
	    		$shouldPay = explode(',', $vouchersInfo['fulldiscount']);
	    		if($allPrice >= $shouldPay[0]){
	    			$deratePrice = $allPrice*($shouldPay[1]/100);
	    		}
	    	}elseif($vouchersInfo['discounttype'] == '5'){
	    		//每满减
	    		$shouldPay = explode(',', $vouchersInfo['eachfullminus']);
	    		$deratePrice = (floor($allPrice/$shouldPay[0]))*$shouldPay[1];
	    	}
	    	$allPrice = $allPrice - $deratePrice;
	    	if($allPrice <= 0){
	    		$allPrice = '0.01';
	    	}
	    	//优惠口令
	    	if($discoukey){
	    		$info = M('dms_discoukey')->where(array('companyid'=>$this->companyid,'appscene'=>array('like','%,1,%'),'discoukey'=>$discoukey,'endtime'=>array('gt',time())))->field('id,discoukey,discoutype,discoumoney2,startdiscoumoney3,discoumoney3,discouratio4,startdiscoumoney5,discouratio5,giftname6,startdiscoumoney7,giftname7,startdiscoumoney8,discoumoney8')->find();
	    		if($info){
	    			$isshow = 1; //是否显示$return['html']
	    			if($info['discoutype'] == 1){
	    				//无优惠
	    				$isshow = 2;
	    				$return['tips'] = "本优惠口令无优惠";
	    				$amount = $allPrice;
	    			}elseif($info['discoutype'] == 2){
	    				//立减优惠
	    				$discount = "立减".$info['discoumoney2'];
	    				$utility = "-".$info['discoumoney2'];
	    				$amount = $allPrice - $info['discoumoney2'];
	    			}elseif($info['discoutype']==3 && $allPrice>=$info['startdiscoumoney3']){
	    				//满减优惠
	    				$discount = "满".$info['startdiscoumoney3']."立减".$info['discoumoney3'];
	    				if($allPrice >= $info['startdiscoumoney3']){
	    					$utility = "-".$info['discoumoney3'];
	    					$amount = $allPrice - $info['discoumoney3'];
	    				}else{
	    					$utility = "-0.00";
	    					$amount = $allPrice;
	    				}
	    			}elseif($info['discoutype'] == 4){
	    				//立折优惠
	    				$discount = "立折优惠".$info['discouratio4']."%";
	    				$utility = "-".$allPrice*($info['discouratio4']/100);
	    				$amount = $allPrice - format_number($allPrice*($info['discouratio4']/100));
	    			}elseif($info['discoutype']==5 && $allPrice>=$info['startdiscoumoney5']){
	    				//满折优惠
	    				$discount = "满".$info['startdiscoumoney5']."立折".$info['discouratio5']."%";
	    				if($allPrice >= $info['startdiscoumoney5']){
	    					$utility = "-".$allPrice*($info['discouratio5']/100);
	    					$amount = $allPrice - format_number($allPrice*($info['discouratio5']/100));
	    				}else{
	    					$utility = "-0.00";
	    					$amount = $allPrice;
	    				}
	    			}elseif($info['discoutype'] == 6){
	    				//礼品赠送
	    				$discount = $info['giftname6'];
	    				$utility = $info['giftname6'];
	    				$amount = $allPrice;
	    			}elseif($info['discoutype']==7 && $allPrice>=$info['startdiscoumoney7']){
	    				//礼品满赠
	    				$discount = "满".$info['startdiscoumoney7']."赠送".$info['giftname7'];
	    				if($allPrice >= $info['startdiscoumoney7']){
	    					$utility = $info['giftname7'];
	    				}else{
	    					$utility = "-0.00";
	    				}
	    				$amount = $allPrice;
	    			}elseif($info['discoutype']==8 && $allPrice>=$info['startdiscoumoney8']){
	    				//每满减优惠
	    				$discount = "每满".$info['startdiscoumoney8']."立减".$info['discoumoney8'];
	    				if($allPrice >= $info['startdiscoumoney8']){
	    					$xy = '0.00';
	    					$xy = (floor($allPrice/$info['startdiscoumoney8']))*$info['discoumoney8'];
	    					$utility = "-".$xy;
	    					$amount = $allPrice-$xy;
	    				}else{
	    					$utility = "-0.00";
	    					$amount = $allPrice;
	    				}
	    			}
	    			if($amount > 0){
	    				$allPrice = $amount;
	    				$return['code'] = 200;
	    				$return['tips'] = '使用优惠成功';
	    				$return['allPrice'] = format_number($allPrice+$allFreight);
	    				$return['dmsDeratePrice'] = $dmsDeratePrice = format_number(trim($utility,'-'));
	    				$return['vouchersDeratePrice'] = format_number($deratePrice);
	    				$return['tempAllDeratePrice'] = format_number($deratePrice + $dmsDeratePrice);
	    				$return['allDeratePrice'] = ($deratePrice + $dmsDeratePrice + $discountPrice)>0?format_number($deratePrice + $dmsDeratePrice + $discountPrice):'0.00';
	    				if($isshow == 1){
	    					$return['html'] = '<div class="Password-utility js-dms-discout-box" data-id="'.$info[id].'"><p>优惠口令：'.$info['discoukey'].'</p><p>立享优惠：'.$discount.'</p><p>口令效用：<span>'.format_number($utility).'</span></p></div>';
	    				}
	    			}else{
	    				$return['code'] = 201;
	    				$return['tips'] = '优惠金额不能大于或等于订单金额';
	    				$return['allPrice'] = format_number($allPrice+$allFreight);
	    				$return['vouchersDeratePrice'] = format_number($deratePrice);
	    				$return['tempAllDeratePrice'] = format_number($deratePrice);
	    				$return['allDeratePrice'] = ($deratePrice + $discountPrice)>0?format_number($deratePrice + $discountPrice):'0.00';
	    			}
	    		}else{
	    			$return['code'] = 300;
	    			$return['tips'] = '本优惠口令不存在';
	    			$return['allPrice'] = format_number($allPrice+$allFreight);
	    			$return['vouchersDeratePrice'] = format_number($deratePrice);
	    			$return['tempAllDeratePrice'] = format_number($deratePrice);
	    			$return['allDeratePrice'] = ($deratePrice + $discountPrice)>0?format_number($deratePrice + $discountPrice):'0.00';
	    		}
	    	}else{
	    		$return['code'] = 200;
	    		$return['tips'] = '使用优惠券成功';
	    		$return['allPrice'] = format_number($allPrice+$allFreight);
	    		$return['vouchersDeratePrice'] = format_number($deratePrice);
	    		$return['tempAllDeratePrice'] = format_number($deratePrice);
	    		$return['allDeratePrice'] = ($deratePrice + $discountPrice)>0?format_number($deratePrice + $discountPrice):'0.00';
	    	}
    	}else{
    		$return['code'] = 300;
    		$return['tips'] = '使用优惠券失败';
    		$return['allPrice'] = format_number($allPrice+$allFreight);
    		$return['vouchersDeratePrice'] = format_number($deratePrice);
    		$return['tempAllDeratePrice'] = format_number($deratePrice);
    		$return['allDeratePrice'] = ($deratePrice + $discountPrice)>0?format_number($deratePrice + $discountPrice):'0.00';
    	}
    	echo json_encode($return);
    }
    /**
     * SCRM（不使用优惠券）
     * ajax 异步不使用优惠券 存在优惠口令则将优惠口令价格重新计算一遍
     * @author Thomas<416369046@qq.com>
     * @since  2017-1-17
     */
    public function ajaxNotUseVouchers(){
    	$allPrice = $this->_post('allPrice');
    	$discoukey = $this->_post('discoukey');
    	$allFreight = $this->_post("allFreight");
    	$discountPrice = $this->_post("discountPrice");
    	$where['companyid'] = $this->companyid;
    	$where['mid'] = $this->mid;
    	$where['isused'] = '2';
    	$where['issend'] = '2';
    	$where['_string'] = ' ((vouchertype = 7 || (vouchertype = 40 && usescenelimitset like "%,1,%")) AND useendtime >" '. time() . '")';
    	if ($allPrice) {
    		$where['_string'] .= " AND (parvalue <= " . $allPrice . " || parvalue = '0.00')";
    	}
    	//可使用的优惠券
    	$count = M('member_vouchers')->where($where)->count();
    	//优惠口令
    	if($discoukey){
    		$info = M('dms_discoukey')->where(array('companyid'=>$this->companyid,'appscene'=>array('like','%,1,%'),'discoukey'=>$discoukey,'endtime'=>array('gt',time())))->field('id,discoukey,discoutype,discoumoney2,startdiscoumoney3,discoumoney3,discouratio4,startdiscoumoney5,discouratio5,giftname6,startdiscoumoney7,giftname7,startdiscoumoney8,discoumoney8')->find();
    		if($info){
    			$isshow = 1; //是否显示$return['html']
    			if($info['discoutype'] == 1){
    				//无优惠
    				$isshow = 2;
    				$return['tips'] = "本优惠口令无优惠";
    				$amount = $allPrice;
    			}elseif($info['discoutype'] == 2){
    				//立减优惠
    				$discount = "立减".$info['discoumoney2'];
    				$utility = "-".$info['discoumoney2'];
    				$amount = $allPrice - $info['discoumoney2'];
    			}elseif($info['discoutype']==3 && $allPrice>=$info['startdiscoumoney3']){
    				//满减优惠
    				$discount = "满".$info['startdiscoumoney3']."立减".$info['discoumoney3'];
    				if($allPrice >= $info['startdiscoumoney3']){
    					$utility = "-".$info['discoumoney3'];
    					$amount = $allPrice - $info['discoumoney3'];
    				}else{
    					$utility = "-0.00";
    					$amount = $allPrice;
    				}
    			}elseif($info['discoutype'] == 4){
    				//立折优惠
    				$discount = "立折优惠".$info['discouratio4']."%";
    				$utility = "-".$allPrice*($info['discouratio4']/100);
    				$amount = $allPrice - $allPrice*($info['discouratio4']/100);
    			}elseif($info['discoutype']==5 && $allPrice>=$info['startdiscoumoney5']){
    				//满折优惠
    				$discount = "满".$info['startdiscoumoney5']."立折".$info['discouratio5']."%";
    				if($allPrice >= $info['startdiscoumoney5']){
    					$utility = "-".$allPrice*($info['discouratio5']/100);
    					$amount = $allPrice - $allPrice*($info['discouratio5']/100);
    				}else{
    					$utility = "-0.00";
    					$amount = $allPrice;
    				}
    			}elseif($info['discoutype'] == 6){
    				//礼品赠送
    				$discount = $info['giftname6'];
    				$utility = $info['giftname6'];
    				$amount = $allPrice;
    			}elseif($info['discoutype']==7 && $allPrice>=$info['startdiscoumoney7']){
    				//礼品满赠
    				$discount = "满".$info['startdiscoumoney7']."赠送".$info['giftname7'];
    				if($allPrice >= $info['startdiscoumoney7']){
    					$utility = $info['giftname7'];
    				}else{
    					$utility = "-0.00";
    				}
    				$amount = $allPrice;
    			}elseif($info['discoutype']==8 && $allPrice>=$info['startdiscoumoney8']){
    				//每满减优惠
    				$discount = "每满".$info['startdiscoumoney8']."立减".$info['discoumoney8'];
    				if($allPrice >= $info['startdiscoumoney8']){
    					$xy = '0.00';
    					$xy = (floor($allPrice/$info['startdiscoumoney8']))*$info['discoumoney8'];
    					$utility = "-".$xy;
    					$amount = $allPrice-$xy;
    				}else{
    					$utility = "-0.00";
    					$amount = $allPrice;
    				}
    			}
    			if($amount > 0){
    				$allPrice = $amount;
    				$return['code'] = 200;
    				$return['allPrice'] = format_number($allPrice+$allFreight);
    				$return['dmsDeratePrice'] = $dmsDeratePrice = format_number(trim($utility,'-'));
    				$return['tempAllDeratePrice'] = format_number($dmsDeratePrice);
    				$return['allDeratePrice'] = ($dmsDeratePrice+$discountPrice)>0?format_number($dmsDeratePrice+$discountPrice):'0.00';
    				$return['isCanUseVouchers'] = $count.' 张可用';
    				if($isshow == 1){
    					$return['html'] = '<div class="Password-utility js-dms-discout-box" data-id="'.$info[id].'"><p>优惠口令：'.$info['discoukey'].'</p><p>立享优惠：'.$discount.'</p><p>口令效用：<span>'.format_number($utility).'</span></p></div>';
    				}
    			}else{
    				$return['code'] = 201;
    				$return['tips'] = '优惠金额不能大于或等于订单金额';
    				$return['allPrice'] = format_number($allPrice+$allFreight);
    				$return['tempAllDeratePrice'] = '0.00';
    				$return['allDeratePrice'] = $discountPrice>0?format_number($discountPrice):'0.00';
    				$return['isCanUseVouchers'] = $count.' 张可用';
    			}
    		}else{
    			$return['code'] = 300;
    			$return['tips'] = '本优惠口令不存在';
    			$return['allPrice'] = format_number($allPrice+$allFreight);
    			$return['tempAllDeratePrice'] = '0.00';
    			$return['allDeratePrice'] = $discountPrice>0?format_number($discountPrice):'0.00';
    			$return['isCanUseVouchers'] = $count.' 张可用';
    		}
    	}else{
    		$return['code'] = 200;
    		$return['allPrice'] = format_number($allPrice+$allFreight);
    		$return['tempAllDeratePrice'] = '0.00';
    		$return['allDeratePrice'] = $discountPrice>0?format_number($discountPrice):'0.00';
    		$return['isCanUseVouchers'] = $count.' 张可用';
    		$return['tips'] = '不使用优惠券';
    	}
    	echo json_encode($return);
    }
    /**
     * SCRM
     * 使用优惠口令
     * @author Thomas<416369046@qq.com>
     * @since  2017-1-17
     */
    public function ajaxPreferentialPassword(){
    	$allPrice = $this->_post('allPrice');
    	$discoukey = $this->_post('discoukey');
    	$vouchersid = $this->_post('vouchersid');
    	$allFreight = $this->_post("allFreight");
    	$discountPrice = $this->_post("discountPrice");
    	$where['companyid'] = $this->companyid;
    	$where['id'] = $vouchersid;
    	//优惠券
    	$vouchersInfo = M('member_vouchers')->where($where)->field('id,mid,sn,parvalue,usetimelimittype,useshopslimitset,useinfo,discounttype,minus,discount,fullminus,fulldiscount,eachfullminus,usestarttime,useendtime,vouchername')->find();
    	if($vouchersInfo){
    		if($vouchersInfo['discounttype'] == '1'){
    			//立减
    			$deratePrice = $vouchersInfo['minus'];
    		}elseif($vouchersInfo['discounttype'] == '2'){
    			//立折
    			$deratePrice = $allPrice*($vouchersInfo['discount']/100);
    		}elseif($vouchersInfo['discounttype'] == '3'){
    			//满减
    			$shouldPay = explode(',', $vouchersInfo['fullminus']);
    			if($allPrice >= $shouldPay[0]){
    				$deratePrice = $shouldPay[1];
    			}
    		}elseif($vouchersInfo['discounttype'] == '4'){
    			//满折
    			$shouldPay = explode(',', $vouchersInfo['fulldiscount']);
    			if($allPrice >= $shouldPay[0]){
    				$deratePrice = $allPrice*($shouldPay[1]/100);
    			}
    		}elseif($vouchersInfo['discounttype'] == '5'){
    			//每满减
    			$shouldPay = explode(',', $vouchersInfo['eachfullminus']);
    			$deratePrice = (floor($allPrice/$shouldPay[0]))*$shouldPay[1];
    		}
    		$allPrice = $allPrice - $deratePrice;
    		if($allPrice <= 0){
    			$allPrice = '0.01';
    		}
    	}
    	$info = M('dms_discoukey')->where(array('companyid'=>$this->companyid,'appscene'=>array('like','%,1,%'),'discoukey'=>$discoukey,'endtime'=>array('gt',time())))->field('id,discoukey,discoutype,discoumoney2,startdiscoumoney3,discoumoney3,discouratio4,startdiscoumoney5,discouratio5,giftname6,startdiscoumoney7,giftname7,startdiscoumoney8,discoumoney8')->find();
    	if($info){
    		$isshow = 1; //是否显示$return['html']
    		if($info['discoutype'] == 1){
    			//无优惠
    			$isshow = 2;
    			$return['tips'] = "本优惠口令无优惠";
    			$amount = $allPrice;
    		}elseif($info['discoutype'] == 2){
    			//立减优惠
    			$discount = "立减".$info['discoumoney2'];
    			$utility = "-".$info['discoumoney2'];
    			$amount = $allPrice - $info['discoumoney2'];
    		}elseif($info['discoutype']==3 && $allPrice>=$info['startdiscoumoney3']){
    			//满减优惠
    			$discount = "满".$info['startdiscoumoney3']."立减".$info['discoumoney3'];
    			if($allPrice >= $info['startdiscoumoney3']){
    				$utility = "-".$info['discoumoney3'];
    				$amount = $allPrice - $info['discoumoney3'];
    			}else{
    				$utility = "-0.00";
    				$amount = $allPrice;
    			}
    		}elseif($info['discoutype'] == 4){
    			//立折优惠
    			$discount = "立折优惠".$info['discouratio4']."%";
    			$utility = "-".$allPrice*($info['discouratio4']/100);
    			$amount = $allPrice - format_number($allPrice*($info['discouratio4']/100));
    		}elseif($info['discoutype']==5 && $allPrice>=$info['startdiscoumoney5']){
    			//满折优惠
    			$discount = "满".$info['startdiscoumoney5']."立折".$info['discouratio5']."%";
    			if($allPrice >= $info['startdiscoumoney5']){
    				$utility = "-".$allPrice*($info['discouratio5']/100);
    				$amount = $allPrice - format_number($allPrice*($info['discouratio5']/100));
    			}else{
    				$utility = "-0.00";
    				$amount = $allPrice;
    			}
    		}elseif($info['discoutype'] == 6){
    			//礼品赠送
    			$discount = $info['giftname6'];
    			$utility = $info['giftname6'];
    			$amount = $allPrice;
    		}elseif($info['discoutype']==7 && $allPrice>=$info['startdiscoumoney7']){
    			//礼品满赠
    			$discount = "满".$info['startdiscoumoney7']."赠送".$info['giftname7'];
    			if($allPrice >= $info['startdiscoumoney7']){
    				$utility = $info['giftname7'];
    			}else{
    				$utility = "-0.00";
    			}
    			$amount = $allPrice;
    		}elseif($info['discoutype']==8 && $allPrice>=$info['startdiscoumoney8']){
    			//每满减优惠
    			$discount = "每满".$info['startdiscoumoney8']."立减".$info['discoumoney8'];
    			if($allPrice >= $info['startdiscoumoney8']){
    				$xy = '0.00';
    				$xy = (floor($allPrice/$info['startdiscoumoney8']))*$info['discoumoney8'];
    				$utility = "-".$xy;
    				$amount = $allPrice-$xy;
    			}else{
    				$utility = "-0.00";
    				$amount = $allPrice;
    			}
    		}
    		if($amount > 0){
    			$allPrice = $amount;
    			$return['code'] = 200;
    			$return['allPrice'] = format_number($allPrice+$allFreight);
    			$return['dmsDeratePrice'] = $dmsDeratePrice = format_number(trim($utility,'-'));
    			$return['voucherDeratePrice'] = format_number($deratePrice);
    			$return['tempAllDeratePrice'] = format_number($deratePrice + $dmsDeratePrice);
    			$return['allDeratePrice'] = ($deratePrice + $dmsDeratePrice + $discountPrice)>0?format_number($deratePrice + $dmsDeratePrice + $discountPrice):'0.00';
    			if($isshow == 1){
    				$return['html'] = '<div class="Password-utility js-dms-discout-box" data-id="'.$info['id'].'"><p>优惠口令：'.$info['discoukey'].'</p><p>立享优惠：'.$discount.'</p><p>口令效用：<span>'.format_number($utility).'</span></p></div>';
    			}
    		}else{
    			$return['code'] = 300;
    			$return['tips'] = '优惠金额不能大于或等于订单金额';
    			$return['allPrice'] = format_number($allPrice+$allFreight);
    			$return['voucherDeratePrice'] = format_number($deratePrice);
    			$return['tempAllDeratePrice'] = format_number($deratePrice);
    			$return['allDeratePrice'] = ($deratePrice + $discountPrice)>0?format_number($deratePrice + $discountPrice):'0.00';
    		}
    	}else{
    		$return['code'] = 300;
    		$return['tips'] = '本优惠口令不存在';
    		$return['allPrice'] = format_number($allPrice+$allFreight);
    		$return['voucherDeratePrice'] = format_number($deratePrice);
    		$return['tempAllDeratePrice'] = format_number($deratePrice);
    		$return['allDeratePrice'] = ($deratePrice + $discountPrice)>0?format_number($deratePrice + $discountPrice):'0.00';
    	}
    	echo json_encode($return);
    }
    /**
     * SCRM
     * ajax 异步切换选择地址
     * @author Thomas<416369046@qq.com>
     * @since  2017-1-18
     */
    public function ajaxChangeAddress(){
    	$addressid = $this->_post("addressid");
    	$where['id'] = $addressid;
    	$where['companyid'] = $this->companyid;
    	$where['mid'] = $this->mid;
    	$addressInfo = M('member_shop_address')->where($where)->field('id,name,mobile,province,city,district,address,isdefault')->find();
    	
    	$isDispatching = 1;
    	$carGoodsId = $this->_post('carGoodsId'); // 购物车Id
    	if($carGoodsId){
    		//-------------------- 购物车 --------------------
    		$goodType1Num = '0'; // 实物商品个数（用于运费）
    		$cartList = M('mall_shopping_cart')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'id'=>array('in', $carGoodsId)))->field('goodid,goodskuid,goodnum')->select();
    		foreach($cartList as $key=>$val){
    			$list[$key]['good'] = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$val['goodid'],'isoffshelves'=>2,'issoldout'=>2))->field('id,goodtype,freighttype,freighttplid')->find();
    			if($list[$key]['good']['goodtype'] == 1){ // 实物商品
    				$goodType1Num++;
    			}
    			$list[$key]['good']['goodnum'] = $val['goodnum'];
    		}
    		if($goodType1Num > 0){
    			foreach($list as $goodKey=>$goodVal){
    				if($goodVal['good']['freighttype'] == 3){
    					$listArr[$goodKey] = $goodVal['good']['freighttplid'];
    				}
    			}
    			$listArr = array_merge(array_flip(array_flip($listArr)));
    			foreach($listArr as $arrKey=>$arrVal){
    				foreach($list as $goodKey2=>$goodVal2){
    					if($arrVal == $goodVal2['good']['freighttplid']){
    						$listArr2[$arrKey]['freighttplid'] = $goodVal2['good']['freighttplid'];
    						$listArr2[$arrKey]['goodnum'] += $goodVal2['good']['goodnum'];
    					}
    				}
    			}
    			foreach($listArr2 as $laKey=>$laVal){
    				$tplType = M('mall_freight_tpl')->where(array('companyid'=>$this->companyid,'id'=>$laVal['freighttplid']))->getField('type');
    				$tplInfo = M('mall_freight_tpl_child')->where(array('companyid'=>$this->companyid,'tplid'=>$laVal['freighttplid'],'areanames'=>array('like','%'.$addressInfo['city'].',%')))->field('firstpiece,continuedpiece,firstheavy,continuedheavy')->find();
    				if($tplInfo){
    					// 模板类型 1、按件数；2、按重量；
    					if($tplType == 1){
    						$allFreight += $tplInfo['firstpiece']+$tplInfo['continuedpiece']*($laVal['goodnum']-1);
    					}else{
    						//$orderInfo['allFreight'] += $tplInfo['firstheavy']+(ceil($orderInfo['allWeight'])-1)*$tplInfo['continuedheavy'];
    					}
    				}else{
    					$isDispatching = 2;
    				}
    			}
    		}
    	}else{
    		//-------------------- 立即购买 --------------------
    		$goodsid = $this->_post('goodsid'); // 商品Id
    		$goodsnum = $this->_post('goodsnum');  // 商品数量
	    	$good = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$goodsid,'isoffshelves'=>2))->field('id,freighttype,freighttplid')->find();
	    	if($addressInfo && $good['freighttype']==3){
	    		$tplType = M('mall_freight_tpl')->where(array('companyid'=>$this->companyid,'id'=>$good['freighttplid']))->getField('type');
	    		$tplInfo = M('mall_freight_tpl_child')->where(array('companyid'=>$this->companyid,'tplid'=>$good['freighttplid'],'areanames'=>array('like','%'.$addressInfo['city'].',%')))->field('firstpiece,continuedpiece,firstheavy,continuedheavy')->find();
	    		if($tplInfo){
	    			// 模板类型 1、按件数；2、按重量；
	    			if($tplType == 1){
	    				$allFreight = $tplInfo['firstpiece']+$tplInfo['continuedpiece']*($goodsnum-1);
	    			}else{
	    				//$orderInfo['allFreight'] = $tplInfo['firstheavy']+(ceil($orderInfo['allWeight'])-1)*$tplInfo['continuedheavy'];
	    			}
	    		}else{
	    			$isDispatching = 2;
	    		}
	    	}
    	}
    	$return['string'] = '';
    	if($addressInfo){
    		$address = $addressInfo['province'].$addressInfo['city'].$addressInfo['district'].$addressInfo['address'];
    		$return['string'] .= '<div class="receipt-addr receipt-addr-pos js-invoiceslt-Popup-btn js-order-pay-addressid" data-addressid="'.$addressInfo['id'].'"><i class="Selected-Address-icon"></i><div class="Selected-Address-Details"><p class="Selected-Address-p1">'.$addressInfo['name'].'<span>'.$addressInfo['mobile'].'</span></p><p class="Selected-Address-p2"><span>';
    		if($addressInfo['isdefault'] == 1){
    			$return['string'] .= '[默认地址]';
    		}else{
    			$return['string'] .= '';
    		}
    		$return['string'] .= '</span> '.$address.'</p></div></div>';
    	}
    	if($return['string']){
    		$allPrice = $this->_post('allPrice');
    		$derateAllPrice = $this->_post("derateAllPrice");
    		$return['code'] = 200;
    		$allPrice = format_number($allPrice-$derateAllPrice);
    		if($allPrice <= 0){
    			$allPrice = '0.01';
    		}
    		$return['allFreight'] = format_number($allFreight);
    		$return['allPrice'] = format_number($allPrice+$allFreight);
    	}else{
    		$return['code'] = 300;
    	}
    	echo json_encode($return);
    }
    /**
     * SCRM
     * 创建/编辑收货地址
     * @author Thomas<416369046@qq.com>
     * @since  2017-1-19
     */
    public function ajaxAddressset(){
    	if(IS_POST){
    		$time = time();
    		$addressType = $this->_post('addressType'); // 1、创建；2、编辑；
    		$addressid = $this->_post('addressid'); // 地址ID
    		$isdefault = $this->_post('isdefault');
    		M()->startTrans();
    		$addre = explode(" ", $this->_post('addre'));
    		$addressData['name'] = $this->_post('name');
    		$addressData['mobile'] = $this->_post('mobile');
    		$addressData['areacode'] = $this->_post('areacode');
    		$addressData['address'] = $this->_post('address');
    		$addressData['province'] = $addre[0];
    		$addressData['city'] = $addre[1];
    		$addressData['district'] = $addre[2];
    		$addressData['isdefault'] = $isdefault;
    		$addressData['updatetime'] = $time;
    		// 获得经纬度
    		if($addressData['city'] && $addressData['district'] && $addressData['address']){
    		    $address = $addressData['city'].$addressData['district'].$addressData['address'];
    		    $url = 'http://apis.map.qq.com/ws/geocoder/v1/?address='.$address.'&key=J3IBZ-AIYHQ-C4I5S-GOYMW-BRGQQ-NZFP7';
    		    $addJson = http_get($url);
    		    $addData = json_decode($addJson, true);
    		    if($addData['status'] == '0'){
    		        $addressData['lng'] = $addData['result']['location']['lng'];
    		        $addressData['lat'] = $addData['result']['location']['lat'];
    		        $isEdit = '1';    // 获得经纬度之后可以提交(防止经纬度获得失败)
    		    }else{
    		        $isEdit = '2';
    		    }
    		}
    		if($isEdit){
    		    if($addressType == 2){
    		        $add = M('Member_shop_address')->where(array('comapnyid'=>$this->companyid,'id'=>$addressid))->save($addressData);
    		        $resultid = $addressid;
    		    }elseif($addressType == 1){
    		        $addressData['companyid'] = $this->companyid;
    		        $addressData['mid'] = $this->mid;
    		        $addressData['createtime'] = $time;
    		        $add = M('Member_shop_address')->add($addressData);
    		        $resultid = $add;
    		    }
    		    // 设置为默认收货地址
    		    if($isdefault == 1){
    		        M('member_shop_address')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'id'=>array('neq', $resultid)))->setField('isdefault', 2);
    		    }
    		    // 个人收货地址（用于地址筛选）
    		    $list = M('member_shop_address')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid))->field('name,mobile,province,city,district,address')->select();
    		    if($list){
    		        foreach($list as $key=>$val){
    		        				$allshopaddress .= $val['name'].' '.$val['mobile'].' '.$val['province'].$val['city'].$val['district'].$val['address'].';';
    		        }
    		        M('member_register_info')->where(array('companyid'=>$this->companyid,'id'=>$this->mid))->save(array('allshopaddress'=>$allshopaddress,'updatetime'=>$time));
    		        unset($list);
    		    }        
    		}

    		$isDispatching = 1;
    		$carGoodsId = $this->_post('carGoodsId'); // 购物车Id
    		$orderAddressInfo = M('member_shop_address')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'id'=>$resultid))->field('id,name,mobile,province,city,district,address,isdefault')->find();
    		if($carGoodsId){
    			//-------------------- 购物车 --------------------
    			$goodType1Num = '0'; // 实物商品个数（用于运费）
    			$cartList = M('mall_shopping_cart')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'id'=>array('in', $carGoodsId)))->field('goodid,goodskuid,goodnum')->select();
    			foreach($cartList as $key=>$val){
    				$list[$key]['good'] = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$val['goodid'],'isoffshelves'=>2,'issoldout'=>2))->field('id,goodtype,freighttype,freighttplid')->find();
    				if($list[$key]['good']['goodtype'] == 1){ // 实物商品
    					$goodType1Num++;
    				}
    				$list[$key]['good']['goodnum'] = $val['goodnum'];
    			}
    			if($goodType1Num > 0){
    				foreach($list as $goodKey=>$goodVal){
    					if($goodVal['good']['freighttype'] == 3){
    						$listArr[$goodKey] = $goodVal['good']['freighttplid'];
    					}
    				}
    				$listArr = array_merge(array_flip(array_flip($listArr)));
    				foreach($listArr as $arrKey=>$arrVal){
    					foreach($list as $goodKey2=>$goodVal2){
    						if($arrVal == $goodVal2['good']['freighttplid']){
    							$listArr2[$arrKey]['freighttplid'] = $goodVal2['good']['freighttplid'];
    							$listArr2[$arrKey]['goodnum'] += $goodVal2['good']['goodnum'];
    						}
    					}
    				}
    				foreach($listArr2 as $laKey=>$laVal){
    					$tplType = M('mall_freight_tpl')->where(array('companyid'=>$this->companyid,'id'=>$laVal['freighttplid']))->getField('type');
    					$tplInfo = M('mall_freight_tpl_child')->where(array('companyid'=>$this->companyid,'tplid'=>$laVal['freighttplid'],'areanames'=>array('like','%'.$orderAddressInfo['city'].',%')))->field('firstpiece,continuedpiece,firstheavy,continuedheavy')->find();
    					if($tplInfo){
    						// 模板类型 1、按件数；2、按重量；
    						if($tplType == 1){
    							$allFreight += $tplInfo['firstpiece']+$tplInfo['continuedpiece']*($laVal['goodnum']-1);
    						}else{
    							//$orderInfo['allFreight'] += $tplInfo['firstheavy']+(ceil($orderInfo['allWeight'])-1)*$tplInfo['continuedheavy'];
    						}
    					}else{
    						$isDispatching = 2;
    					}
    				}
    			}
    		}else{
    			//-------------------- 立即购买 --------------------
    			$goodsid = $this->_post('goodsid'); // 商品Id
    			$goodsnum = $this->_post('goodsnum');  // 商品数量
    			$good = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$goodsid,'isoffshelves'=>2))->field('freighttype,freighttplid')->find();
    			if($good['freighttype']==3){
    				$tplType = M('mall_freight_tpl')->where(array('companyid'=>$this->companyid,'id'=>$good['freighttplid']))->getField('type');
    				$tplInfo = M('mall_freight_tpl_child')->where(array('companyid'=>$this->companyid,'tplid'=>$good['freighttplid'],'areanames'=>array('like','%'.$orderAddressInfo['city'].',%')))->field('firstpiece,continuedpiece,firstheavy,continuedheavy')->find();
    				if($tplInfo){
    					// 模板类型 1、按件数；2、按重量；
    					if($tplType == 1){
    						$allFreight = $tplInfo['firstpiece']+$tplInfo['continuedpiece']*($goodsnum-1);
    					}else{
    						//$orderInfo['allFreight'] = $tplInfo['firstheavy']+(ceil($orderInfo['allWeight'])-1)*$tplInfo['continuedheavy'];
    					}
    				}else{
    					$isDispatching = 2;
    				}
    			}
    		}
    		$string = '';
    		if($orderAddressInfo){
    			$address = $orderAddressInfo['province'].$orderAddressInfo['city'].$orderAddressInfo['district'].$orderAddressInfo['address'];
    			$string .= '<div class="receipt-addr receipt-addr-pos js-invoiceslt-Popup-btn js-order-pay-addressid" data-addressid="'.$orderAddressInfo['id'].'"><i class="Selected-Address-icon"></i><div class="Selected-Address-Details"><p class="Selected-Address-p1">'.$orderAddressInfo['name'].'<span>'.$orderAddressInfo['mobile'].'</span></p><p class="Selected-Address-p2"><span>';
    			if($orderAddressInfo['isdefault'] == 1){
    				$string .= '[默认地址]';
    			}else{
    				$string .= '';
    			}
    			$string .= '</span> '.$address.'</p></div></div>';
    		}
    		if($add && $orderAddressInfo){
    			$allPrice = $this->_post('allPrice'); // 商品金额-整单优惠
    			$derateAllPrice = $this->_post("derateAllPrice"); // 优惠金额
    			M()->commit();
    			$message['code'] = 200;
    			$message['id'] = $resultid;
    			$message['tips'] = '操作成功';
    			$allPrice = $allPrice-$derateAllPrice;
    			if($allPrice < 0){
    				$allPrice = '0.01';
    			}
    			$message['isDispatching'] = $isDispatching;
    			$message['allFreight'] = format_number($allFreight);
    			$message['allPrice'] = format_number($allPrice + $allFreight);
    			$message['string'] = $string;
    		}else{
    			M()->rollback();
    			$message['code'] = 300;
    			if($isEdit == '2'){
    			    $message['tips'] = '暂时无法识别您的地址，请稍后再试！';
    			}else{
    			    $message['tips'] = '操作失败';
    			}
    		}
    		echo json_encode($message);
    	}
    }
    /**
     * SCRM
     * ajax 获取当前会员所有地址
     * @author Thomas<416369046@qq.com>
     * @since  2017-1-19
     */
    public function ajaxGetAddress(){
    	$goodsid = $this->_post('goodsid');
    	$addressList = M('member_shop_address')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid))->field('id,name,mobile,province,city,district,address,isdefault')->order('updatetime DESC,id DESC')->select();
    	if($addressList){
    		$addresString = ''; //所有地址
    		$opentionList = ''; //可选择地址
    		$notOpentionList = ''; //不可选择地址
    		// 整理商品id
    		$goodsidArr = explode(',', $goodsid);
    		foreach($goodsidArr as $arrKey=>$arrVal){
    			$goodsidArrId = explode('|', $arrVal);
    			if($goodsidArrId[0]){
    				$goodsidArr2[] = $goodsidArrId[0];
    			}
    		}
    		// 查询商品配送区域
    		$tplArr = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>array('in', array_unique($goodsidArr2)),'freighttype'=>3))->field('freighttplid')->select();
    		if($tplArr){
    			foreach($tplArr as $tplKey=>$tplVal){
    				$tplArr[$tplKey] = M('mall_freight_tpl_child')->where(array('companyid'=>$this->companyid,'tplid'=>$tplVal['freighttplid']))->field('areanames')->select();
    				foreach($tplArr[$tplKey] as $tplval2){
    					$tplArrString[$tplKey] .= $tplval2['areanames'];
    				}
    			}
    		}
    		foreach($addressList as $key=>$val){
    			$isdefault = $val['isdefault']==1 ? '【默认地址】' : '';
    			$fuheNum = 0;
    			foreach($tplArrString as $tkey=>$tval){
    				// 判断地址是否符合运费模板要求
    				if(strstr($tval, $val['city'])){
    					$fuheNum++;
    					continue;
    				}
    			}
    			$address = $val['province'].$val['city'].$val['district'].$val['address'];
    			if($fuheNum == count($tplArrString)){
    				$opentionstring .= '<li><label data-addressid="'.$val['id'].'"><input class="address-radio radio js-address-'.$val['id'].'" name="address" type="radio" value="'.$val['id'];
    				if($val['isdefault']==1){ $opentionstring .= '" checked="checked"';}
    				$opentionstring .= '"><div class="Choice-address-area"><p><span>'.$val['name'].'</span>'.$val['mobile'].'</p><p><span>'.$isdefault.' </span>'.$address.'</p></div></label><button class="edit-address-btn js-receipt-Popup-btn" data-addressid="'.$val['id'].'" data-address-type="2">编辑</button></li>';
    			}else{
    				$notopentionstring .= '<li><label><input class="address-radio radio js-address-'.$val['id'].'" name="address" type="radio" disabled="true"';
    				if($val['isdefault']==1){ $notopentionstring .= ' checked="checked"';}
    				$notopentionstring .= '><div class="Choice-address-area"><p><span>'.$val['name'].'</span>'.$val['mobile'].'</p><p><span>'.$isdefault.' </span>'.$address.'</p></div></label><button class="edit-address-btn js-receipt-Popup-btn" data-addressid="'.$val['id'].'" data-address-type="2">编辑</button></li>';
    			}
    	    	unset($fuheNum);
    	    }
    	    if($opentionstring){
    	    	$addresString .= '<ul class="Choice-address-ul">'.$opentionstring.'</ul>';
    	    }
    	    if($notopentionstring){
    	    	$addresString .= '<h6 class="Out-of0-range-h6">以下地址超出配送范围</h6><ul class="Choice-address-ul Out-of0-range-ul">'.$notopentionstring.'</ul>';
    	    }
    	    $return['code'] = 200;
    	    $return['addresString'] = $addresString;
    	    echo json_encode($return);
		}
    }
    /**
     * SCRM
     * ajax 获取当前编辑地址的详情
     * @author Thomas<416369046@qq.com>
     * @since  2017-1-19
     */
    public function ajaxGetThisAddress(){
    	$addressid = $this->_post("addressid");
    	$where['id'] = $addressid;
    	$where['companyid'] = $this->companyid;
    	$where['mid'] = $this->mid;
    	$addressInfo = M('member_shop_address')->where($where)->field('id,name,mobile,province,city,district,address,areacode,isdefault')->find();
    	$areacodeList = explode(',',$addressInfo['areacode']);
    	$area = $addressInfo['province'].' '.$addressInfo['city'].' '.$addressInfo['district'];
    	if($addressInfo){
    		$return['code'] = 200;
    		$return['id'] = $addressInfo['id'];
    		$return['name'] = $addressInfo['name'];
    		$return['mobile'] = $addressInfo['mobile'];
    		$return['area'] = $area;
    		$return['address'] = $addressInfo['address'];
    		$return['provincecode'] = $areacodeList[0];
    		$return['citycode'] = $areacodeList[1];
    		$return['districtcode'] = $areacodeList[2];
    		$return['isdefault'] = $addressInfo['isdefault'];
    	}else{
    		$return['code'] = 300;
    		$return['$addressInfo'] = '';
    	}
    	echo json_encode($return);
    }
    
    
    
    

    
    
    
    
    
    /**
     * 非微信支付 订单的支付
     */
    public function orderPay(){
    	$this->setPageTitle(array('title'=>'订单详情'));
    	$this->checkMemberLogin();
    	$id = $this->_get('id');
    	$where['id'] = $id;
    	$where['companyid'] = $this->companyid;
    	$where['ordertype'] = 1;
    	$info = M('mall_order_info')->where($where)->field('id,mid,orderid,borderid,ordertype,orderstatus,paytime,createtime,orderprice,orderint,orderpaymethod')->find();
    	if($info){
    		$info['goodslist'] = M('mall_order_goods')->where(array('companyid'=>$this->companyid,'orderid'=>$info['orderid']))->field('companyid,orderid,goodname,goodnum,goodprice,goodint,pricetype')->select();
    		$info['mallorderautoset'] = M('company')->where(array('id'=>$this->companyid))->getField('mallorderautoset');
    		if($info['orderpaymethod'] == 5 && $this->mid){
    			$info['totalintegration'] = M('member_register_info')->where(array('companyid'=>$this->companyid,'id'=>$this->mid))->getField('totalintegration');
    		}
    		$this->assign('info',$info);
    		$this->display();
    	}else{
    		$this->redirect(U('System/notFound',array('companyid'=>$this->companyid)));
    	}
    }
    /**
     * 非微信支付 订单的支付
     */
    public function orderPayIntegral(){
    	$this->setPageTitle(array('title'=>'订单详情'));
    	$this->checkMemberLogin();
    	$id = $this->_get('id');
    	$where['id'] = $id;
    	$where['companyid'] = $this->companyid;
    	$info = M('mall_order_info')->where($where)->field('id,mid,orderid,borderid,ordertype,orderstatus,paytime,createtime,orderprice,orderint,orderpaymethod')->find();
    	if($info){
    		$info['goodslist'] = M('mall_order_goods')->where(array('companyid'=>$this->companyid,'orderid'=>$info['orderid']))->field('companyid,orderid,goodname,goodnum,goodprice,goodint,pricetype')->select();
    		$info['mallorderautoset'] = M('company')->where(array('id'=>$this->companyid))->getField('mallorderautoset');
    		if($info['orderpaymethod'] == 5 && $this->mid){
    			$info['totalintegration'] = M('member_register_info')->where(array('companyid'=>$this->companyid,'id'=>$this->mid))->getField('totalintegration');
    		}
    		$this->assign('info',$info);
    		$this->display();
    	}else{
    		$this->redirect(U('System/notFound',array('companyid'=>$this->companyid)));
    	}
    }
    /**
     * 立即购买 确认订单
     */
    public function createBuyNowOrderOld(){
    	$this->setPageTitle(array('title'=>'确认订单'));
    	$addressid = $this->_get('addressid');
    	$this->assign('addressid', $addressid);
    	$goodsid = $this->_get('goodsid');
    	$this->assign('goodsid',$goodsid);
    	$goodsskuid = $this->_get('goodsskuid');
    	$this->assign('goodsskuid',$goodsskuid);
    	$goodsnum = $this->_get('goodsnum');
    	$this->assign('goodsnum',$goodsnum);
    	$goodtype = $this->_get('goodtype');
    	$this->assign('goodtype',$goodtype);
    	$orderinvoice = $this->_get('orderinvoice');//订单发票：1：需要；2：不需要；
    	$this->assign('orderinvoice',$orderinvoice);
    	$orderinvoicetitle = $this->_get('orderinvoicetitle');//发票抬头
    	$this->assign('orderinvoicetitle',$orderinvoicetitle);
    	$groupid = $this->_get('groupid'); // 拼团订单商品
    	$this->assign('groupid',$groupid);
    	$groupinfoid = $this->_get('groupinfoid'); // 拼团订单商品
    	$this->assign('groupinfoid',$groupinfoid);
    	$totalintegral = M('member_register_info')->where(array('id'=>$this->mid,'companyid'=>$this->companyid))->getField('totalintegration');
    	$this->assign('totalintegral',$totalintegral);
    	if($goodtype&&$goodsid&&$goodsnum){
    		$time = time();
    		$isDispatching = 1;
    		$orderInfo['allPrice'] = '0.00';
    		$orderInfo['allIntegral'] = '0.00';
    		$orderInfo['allGoodsNum'] = '0';
    		$orderInfo['allFreight'] = '0.00';
    		$orderInfo['allWeight'] = '0.00';
    		//$unifyfreight = M('company')->where(array('id'=>$this->companyid))->getField('unifyfreight');//统一运费
    		$good = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$goodsid,'isoffshelves'=>2))->field('id,goodtype,title,goodnum,pricetype,saleprice,grouponprice,isopenvipprice,intprice,canbuynum,vouchertype,voucherimgurl,vouchersid,weight,freighttype,freighttplid')->find();
    		if(!$good){
    			$this->redirect(U('System/notFound'),array('companyid'=>$this->companyid));
    		}
    		$good['goodnum'] = $goodsnum;
    		if($goodtype == 1 || $goodtype == 3 || $goodtype == 4 || $goodtype == 5 || $goodtype == 6 || $goodtype == 7){
    			if($goodtype == 1 || $goodtype == 3 || $goodtype == 4 || $goodtype == 5){
    				$mallgoodssku = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$goodsid,'id'=>$goodsskuid))->field('name,saleprice,grouponprice,intprice,imgurl')->find();
    				$good['title'] .= '（'.$mallgoodssku['name'].'）';
    				if($groupid){
    					$good['saleprice'] = $mallgoodssku['grouponprice'];
    				}else{
    					$good['saleprice'] = $mallgoodssku['saleprice'];
    				}
    				$good['intprice'] = $mallgoodssku['intprice'];
    			}
    			if($goodtype == 1){
    				$good['pic'] = $mallgoodssku['imgurl'];
    			}else{
    				$good['pic'] = M('mall_goods_pics')->where(array('companyid'=>$this->companyid,'goodid'=>$goodsid))->order('sort asc,id asc')->getField('pic');;
    			}
    		}else{
    			if($groupid){
    				$good['saleprice'] = $good['grouponprice'];
    			}else{
    				$good['saleprice'] = $good['saleprice'];
    			}
    		}
    		//******************** 会员价 **********************************************************************
    		/* if($goodtype==2 && $good['isopenvipprice']==1){
    		 $vipdis = M()->table('tp_member_card_info AS info')->join(array('LEFT JOIN tp_mall_goods_rank_discount AS dis ON dis.rankid=info.rankid'))->where(array('info.companyid'=>$this->companyid,'info.mid'=>$this->mid,'dis.goodsid'=>$good['id']))->getField('vipdiscount');
    		if(0< $vipdis && $vipdis <= 1){
    		//会员几个覆盖优惠价
    		$good['saleprice'] = format_number($good['saleprice']*$vipdis);
    		$good['intprice'] = format_number($good['intprice']*$vipdis);
    		}
    		} */
    		if($good['pricetype'] == 1){
    			$orderInfo['allPrice'] = format_number($good['goodnum']*$good['saleprice']);
    		}elseif ($good['pricetype'] == 2){
    			$orderInfo['allIntegral'] = format_number($good['goodnum']*$good['intprice']);
    		}
    		//******************** 运费 ************************************************************************//
    		$isDispatching = 1;  // 是否可以配送 1、可以；2、不可以；
    		$orderInfo['allWeight'] = format_number($good['goodnum']*$good['weight']); //商品总重量
    		if($goodtype == 1){
    			$addressid = $this->_get('addressid');
    			if($addressid){
    				$addressInfo = M('member_shop_address')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'id'=>$addressid))->field('id,name,mobile,province,city,district,address')->find();
    				$address = $addressInfo['province'].$addressInfo['city'].$addressInfo['district'].$addressInfo['address'];
    			}else{
    				$addressInfo = M('member_shop_address')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'isdefault'=>1))->field('id,name,mobile,province,city,district,address')->find();
    				if($addressInfo){
    					$address = $addressInfo['province'].$addressInfo['city'].$addressInfo['district'].$addressInfo['address'];
    					$addressid = $addressInfo['id'];
    				}else{
    					$address = '';
    					$addressid = 0;
    				}
    			}
    			if($addressid && $good['freighttype']==3){
    				$tplType = M('mall_freight_tpl')->where(array('companyid'=>$this->companyid,'id'=>$good['freighttplid']))->getField('type');
    				$tplInfo = M('mall_freight_tpl_child')->where(array('companyid'=>$this->companyid,'tplid'=>$good['freighttplid'],'areanames'=>array('like','%'.$addressInfo['city'].',%')))->field('firstpiece,continuedpiece,firstheavy,continuedheavy')->find();
    				if($tplInfo){
    					// 模板类型 1、按件数；2、按重量；
    					if($tplType == 1){
    						$orderInfo['allFreight'] = $tplInfo['firstpiece']+$tplInfo['continuedpiece']*($goodsnum-1);
    					}else{
    						//$orderInfo['allFreight'] = $tplInfo['firstheavy']+(ceil($orderInfo['allWeight'])-1)*$tplInfo['continuedheavy'];
    					}
    				}else{
    					$isDispatching = 2;
    				}
    			}
    			$this->assign('address',$address);
    			$this->assign('addressid',$addressid);
    			$this->assign('addressInfo',$addressInfo);
    		}
    		$orderInfo['allFreight'] = format_number($orderInfo['allFreight']);
    		$orderInfo['ordersubtotal'] = $orderInfo['allPrice'] + $orderInfo['allFreight'];
    		//******************** 整单优惠 ****************************************************************************************************
    		$eshopDiscount = M('eshop_discount')->where(array('companyid'=>$this->companyid,'isoff'=>1,'starttime'=>array('lt',$time),'endtime'=>array('gt',$time)))->field('id,title,starttime,endtime,memberclass,type,money,discount,fulljian,fullzhe,fullnumjian,fullnumzhe,isopen,codingno,codingok')->select();
    		if($eshopDiscount && $good['pricetype']==1){
    			$EDsubtotalPrice = '100000000.00'; //参与活动后的订单金额
    			foreach($eshopDiscount as $key=>$val){
    				$isImplement = 1;//1、执行；2、不执行；
    				//判断商品是否参与活动
    				if($val['isopen']==2 && strstr($val['codingno'],$good['goodnum'])==TRUE){
    					$isImplement = 2;
    				}elseif($val['isopen']==3 && strstr($val['codingok'],$good['goodnum'])==FALSE){
    					$isImplement = 2;
    				}
    				//判断商品符合哪种参与人群
    				if($isImplement==1 && $val['memberclass']==2){
    					$isOrderCount = M('mall_order_info')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid))->count();
    					if($isOrderCount > 0){
    						$isImplement = 2;
    					}
    				}
    				//优惠活动
    				if($isImplement==1){
    					if($val['type']==1 && $orderInfo['ordersubtotal']>$val['money']){ //立减优惠
    						$title = $val['title'].' 立减'.$val['money'].'元';
    						$discountPrice = format_number($val['money']);
    						$subtotalPrice = $orderInfo['ordersubtotal']-$discountPrice;
    					}elseif($val['type'] == 2){ //立折优惠
    						$title = $val['title'].' 立折'.$val['discount'].'%';
    						$discountPrice = format_number($orderInfo['ordersubtotal']*($val['discount']/100));
    						$subtotalPrice = $orderInfo['ordersubtotal']-$discountPrice;
    					}elseif($val['type'] == 3){ //满减优惠
    						$fulljian  = explode('|',$val['fulljian']);
    						foreach($fulljian as $key=>$fjval){
    							$fulljianval = explode(',',$fjval);
    							if($fulljianval[0] <= $orderInfo['ordersubtotal']){
    								$title = $val['title'].' 满'.$fulljianval[0].'减'.$fulljianval[1].'元';
    								$discountPrice = format_number($fulljianval[1]);
    								$subtotalPrice = $orderInfo['ordersubtotal']-$discountPrice;
    							}
    						}
    					}elseif($val['type'] == 4){ //满折优惠
    						$fullzhe = explode('|',$val['fullzhe']);
    						foreach($fullzhe as $key=>$fzval){
    							$fullzheval = explode(',',$fzval);
    							if($fullzheval[0] <= $orderInfo['ordersubtotal']){
    								$title = $val['title'].' 满'.$fullzheval[0].'折'.$fullzheval[1].'%';
    								$discountPrice = format_number($orderInfo['ordersubtotal']*($fullzheval[1]/100));
    								$subtotalPrice = $good['ordersubtotal']-$discountPrice;
    							}
    						}
    					}elseif($val['type'] == 5){ //满件减优惠
    						$fullnumjian = explode('|',$val['fullnumjian']);
    						foreach($fullnumjian as $key=>$fnjval){
    							$fullnumjianval = explode(',',$fnjval);
    							if($fullnumjianval[0] <= $goodsnum){
    								$title = $val['title'].' 满'.$fullnumjianval[0].'件减'.$fullnumjianval[1].'元';
    								$discountPrice = format_number($fullnumjianval[1]);
    								$subtotalPrice = $orderInfo['ordersubtotal']-$discountPrice;
    							}
    						}
    					}elseif($val['type'] == 6){ //满件折优惠
    						$fullnumzhe = explode('|',$val['fullnumzhe']);
    						foreach($fullnumzhe as $key=>$fnzval){
    							$fullnumzheval = explode(',',$fnzval);
    							if($fullnumzheval[0] <= $goodsnum){
    								$title = $val['title'].' 满'.$fullnumzheval[0].'件折'.$fullnumzheval[1].'%';
    								$discountPrice = format_number($orderInfo['ordersubtotal']*($fullnumzheval[1]/100));
    								$subtotalPrice = $orderInfo['ordersubtotal']-$discountPrice;
    							}
    						}
    					}
    					$starttime = format_time($val['starttime'],'ymdhi');
    					$endtime = format_time($val['endtime'],'ymdhi');
    					if($subtotalPrice < $EDsubtotalPrice){
    						$EDtitle = $title;
    						$EDstarttime = $starttime;
    						$EDendtime = $endtime;
    						$EDdiscountPrice = $discountPrice;
    						$EDsubtotalPrice = $subtotalPrice;
    					}
    				}
    			}
    			if($EDtitle){
    				$good['ED']['title'] = $EDtitle;
    				$good['ED']['starttime'] = $EDstarttime;
    				$good['ED']['endtime'] = $EDendtime;
    				$good['ED']['discountPrice'] = format_number($EDdiscountPrice);
    				$good['subtotalPrice'] = format_number($EDsubtotalPrice);
    				$this->assign('eshopDiscount','1');
    			}
    		}
    		$orderPrice = $orderInfo['allPrice'] = $good['subtotalPrice'] ? $good['subtotalPrice'] : $orderInfo['ordersubtotal'];//用于读取可用优惠券数量
    		$this->assign('orderPrice', $orderPrice);
    		//********************* 优惠券 *******************************************************************************************************
    		$vouchersid = $this->_get('vouchersid');
    		$this->assign('vouchersid', $vouchersid);
    		$orderInfo['deratePrice'] = '0.00';//减免价格
    		if($vouchersid){
    			$vouchersInfoWhere['id'] = $vouchersid;
    			$vouchersInfoWhere['companyid'] = $this->companyid;
    			$vouchersInfoWhere['mid'] = $this->mid;
    			$vouchersInfoWhere['isused'] = '2';
    			$vouchersInfoWhere['issend'] = '2';
    			$vouchersInfo =  M('member_vouchers')->where($vouchersInfoWhere)->field('id,discounttype,minus,discount,fullminus,fulldiscount,eachfullminus')->find();
    			if($vouchersInfo['discounttype'] == 1){
    				//立减
    				$orderInfo['deratePrice'] = $vouchersInfo['minus'];
    			}elseif($vouchersInfo['discounttype'] == 2){
    				//立折
    				$orderInfo['deratePrice'] = $orderPrice*($vouchersInfo['discount']/100);
    			}elseif($vouchersInfo['discounttype'] == 3){
    				//满减
    				$shouldPay = explode(',', $vouchersInfo['fullminus']);
    				if($orderPrice >= $shouldPay[0]){
    					$orderInfo['deratePrice'] = $shouldPay[1];
    				}
    			}elseif($vouchersInfo['discounttype'] == 4){
    				//满折
    				$shouldPay = explode(',', $vouchersInfo['fulldiscount']);
    				if($orderPrice >= $shouldPay[0]){
    					$orderInfo['deratePrice'] = $orderPrice*($shouldPay[1]/100);
    				}
    			}elseif($vouchersInfo['discounttype'] == 5){
    				//每满减
    				$shouldPay = explode(',', $vouchersInfo['eachfullminus']);
    				$orderInfo['deratePrice'] = (floor($orderPrice/$shouldPay[0]))*$shouldPay[1];
    			}
    		}
    		$orderInfo['tempAllPrice'] = $orderInfo['allPrice'];
    		$orderInfo['allPrice'] = $orderInfo['allPrice'] - $orderInfo['deratePrice'];
    		if($orderInfo['allPrice'] <= 0){
    			$orderInfo['allPrice'] = '0.01';
    		}
    		$orderInfo['allPrice'] = format_number($orderInfo['allPrice']);
    		$orderInfo['deratePrice'] = format_number($orderInfo['deratePrice']);
    		$totalintegration = M('member_register_info')->where(array('companyid'=>$this->companyid,'id'=>$this->mid))->getField('totalintegration');
    		if($totalintegration < 0){
    			$totalintegration = '0.00';
    		}
    		$this->assign('totalintegration',$totalintegration);
    		$this->assign('orderInfo',$orderInfo);
    		$this->assign('good',$good);
    		$this->assign('isshowsystemdiymen','2');//隐藏自定义菜单
    		$this->assign('isDispatching',$isDispatching);
    		$canUseVouchersCount = 0;
    		if($orderPrice>0){
    			///读取优惠券
    			unset($where);
    			$where['companyid'] = $this->companyid;
    			$where['mid'] = $this->mid;
    			$where['isused'] = '2';
    			$where['issend'] = '2';
    			$where['_string'] = ' (vouchertype=7 AND useendtime >" '. time() . '")';
    			if ($orderPrice) {
    				$where['_string'] .= " AND (parvalue <= " . $orderPrice . " || parvalue = '0.00')";
    			}
    			//未使用
    			$canUseVouchersCount = M('member_vouchers')->where($where)->count();
    		}
    		$this->assign('canUseVouchersCount', $canUseVouchersCount);
    	}else{
    		$this->redirect(U('System/notFound'),array('companyid'=>$this->companyid));
    	}
    	if(!$this->mid){
    		session('historyUrl','http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]);// 用于登陆后跳回本页面
    		$this->checkMemberLoginBox();// 检测是否登录弹框
    	}
    	$this->display();
    }
    /**
     * ajax 提交立即购买订单
     */
    public function ajaxCreateBuyNowOrderOld(){
    	$ajaxReturn['code'] = 300;
    	$ajaxReturn['msg'] = '订单提交失败，请您稍后重试';
    
    	// http://testing.mobiwind.cn/index.php?g=Wap&m=MemberMallOrder&a=createBuyNowOrder&companyid=1217&goodsid=pg0121700048&goodsskuid=3285&goodsnum=1&goodtype=1&groupid=3IVLIRZO2WW0G&groupinfoid=3A4RNU6EKRGG0
    	$time = time();
    	$goodsid = $this->_post('goodsid');
    	$groupid = $this->_request('groupid');
    	$groupinfoid = $this->_request('groupinfoid');
    	$good = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$goodsid,'isoffshelves'=>2,'issoldout'=>2))->field('id,goodtype,title,pricetype,saleprice,grouponprice,stockamount,isopenvipprice,intprice,canbuynum,vouchertype,voucherimgurl,vouchersid,prefix,freighttype,freighttplid,weight,usetimelimittype,usetimelimitset,useshopslimitset,backorderpolicyset,useinfo')->find();
    	if(!$good){
    		$ajaxReturn['code'] = 300;
    		$ajaxReturn['msg'] = '该商品已售罄（或下架）';
    	}else{
    		$goodskuid = $this->_post('goodsskuid');
    		$goodnum = $this->_post('goodsnum');
    		if($good['goodtype'] == 1 || $good['goodtype'] == 3 || $good['goodtype'] == 4 || $good['goodtype'] == 5 || $good['goodtype'] == 6 || $good['goodtype'] == 7){
    			if($good['goodtype'] == 1 || $good['goodtype'] == 3 || $good['goodtype'] == 4 || $good['goodtype'] == 5){
    				$mallgoodssku = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'id'=>$goodskuid))->field('name,saleprice,grouponprice,intprice,imgurl,stockamount')->find();
    				$stockamount = $mallgoodssku['stockamount'];
    				$good['title'] .= '（'. $mallgoodssku['name'].'）';
    				if($groupid){
    					$good['saleprice'] = $mallgoodssku['grouponprice'];
    				}else{
    					$good['saleprice'] = $mallgoodssku['saleprice'];
    				}
    				$good['intprice'] = $mallgoodssku['intprice'];
    			}else{
    				$stockamount = $good['stockamount'];
    			}
    			if($good['goodtype'] == 1){
    				$good['pic'] = $mallgoodssku['imgurl'];
    			}else{
    				$good['pic'] = M('mall_goods_pics')->where(array('companyid'=>$this->companyid,'goodid'=>$goodsid))->order('sort asc,id asc')->getField('pic');;
    			}
    		}elseif($good['goodtype'] == 2){
    			if($groupid){
    				$good['saleprice'] = $good['grouponprice'];
    			}else{
    				$good['saleprice'] = $good['saleprice'];
    			}
    			$stockamount = $good['stockamount'];
    			$good['pic'] = $good['voucherimgurl'];
    		}
    		$good['goodname'] = $good['title'];
    		if($goodnum > $stockamount){
    			$ajaxReturn['code'] = 300;
    			$ajaxReturn['msg'] = '商品库存不足';
    		}else{
    			$addressid = $this->_post('addressid');
    			if($good['goodtype'] == 1){
    				$defaultAddress = M('member_shop_address')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'id'=>$addressid))->field('name,mobile,province,city,district,address')->find();
    				if(!$defaultAddress){
    					$ajaxReturn['msg'] = '请选择您的收货地址';
    					echo json_encode($ajaxReturn);
    					exit();
    				}else{
    					$orderInfo['consigneename'] = $defaultAddress['name'];
    					$orderInfo['consigneephone'] = $defaultAddress['mobile'];
    					$orderInfo['consigneeaddress'] = $defaultAddress['province'].$defaultAddress['city'].$defaultAddress['district'].$defaultAddress['address'];
    				}
    			}
    			//订单表主表
    			$orderpaymethod = $this->_post('orderpaymethod');  //付款方式
    			$orderprice = $this->_post('orderprice'); //订单金额
    			$serialNumber = M('mall_order_info')->where(array('companyid'=>$this->companyid,'createtime'=>array('between',array($time,$time+1))))->count();
    			$orderid = orderID('2','E', $this->companyid, $serialNumber+1);
    			$orderInfo['id'] = $orderInfoReturn = guidNow();
    			$orderInfo['companyid'] = $this->companyid;
    			$orderInfo['mid'] = $this->mid;
    			$orderInfo['orderid'] = $orderid;
    			if($good['goodtype'] == 1){
    				$orderInfo['truegoodtype'] = '1';
    			}
    			if($groupid){ //拼团信息
    				$group = M('mall_groupon_activity')->where(array('companyid'=>$this->companyid,'id'=>$groupid))->field('id,goodid,limitbuy,limitnum,groupnum,qrcode,status')->find();
    				//存拼团商品表
    				if($groupinfoid != 0){
    					$groupInfo = M('mall_groupon_info')->where(array('companyid'=>$this->companyid,'id'=>$groupinfoid))->field('id,grouporderid')->find();
    					/* 	$where['companyid'] = $this->companyid;
    					 $where['id'] = $groupinfoid;
    					$groupData['joingroupnum'] = array('exp', '`joingroupnum`+1');
    					$groupData['updatetime'] = time();
    					$groupResult = M('mall_groupon_info')->where($where)->save($groupData); */
    					$memberGroupData['isleader'] = 2;
    					$memberGroupData['groupinfoid'] = $groupinfoid;
    					$memberGroupData['grouporderid'] = $groupInfo['grouporderid'];
    				}else{
    					$groupData['id'] = $groupinfoid = guidNow();
    					$groupData['companyid'] = $this->companyid;
    					$groupData['groupid'] = $groupid;
    					$groupData['goodid'] = $goodsid;
    					$groupData['goodskuid'] = $goodskuid;
    					$groupData['grouporderid'] = $grouporderid = get_order_id();
    					$groupData['groupstatus'] = 1;
    					$groupData['groupnum'] = $group['groupnum'];
    					$groupData['joingroupnum'] = 1;
    					$groupData['createtime'] = $groupData['updatetime'] = time();
    					$groupResult = M('mall_groupon_info')->add($groupData);
    					$memberGroupData['isleader'] = 1;
    					$memberGroupData['groupinfoid'] = $groupinfoid;
    					$memberGroupData['grouporderid'] = $grouporderid;
    				}
    				// mall_groupon_info_member 团长每个团员信息
    				$memberInfo = M('member_wechat_info')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid))->field('id,mid,openid,nickname,headimgurl')->find();
    				$memberGroupData['id'] = guidNow();
    				$memberGroupData['companyid'] = $this->companyid;
    				$memberGroupData['groupid'] = $groupid;
    				$memberGroupData['orderid'] = $orderid;
    				$memberGroupData['mid'] = $this->mid;
    				$memberGroupData['openid'] = $memberInfo['openid'];
    				$memberGroupData['nickname'] = $memberInfo['nickname'];
    				$memberGroupData['headimgurl'] = $memberInfo['headimgurl'];
    				$memberGroupData['base64nickname'] = base64_encode($memberInfo['nickname']);
    				$memberGroupData['createtime'] = $memberGroupData['updatetime'] = time();
    				$memberGroupResult = M('mall_groupon_info_member')->add($memberGroupData);
    				$orderInfo['groupinfoid'] = $groupinfoid;
    			}
    			$orderInfo['ordertitle'] = $good['title'];
    			$orderInfo['goodtype'] = $good['goodtype'];
    			$orderInfo['ordertype'] = $good['goodtype'];
    			$orderInfo['orderstatus'] = '1';
    			$orderInfo['temporderstatus'] = '1';
    			$orderInfo['orderprice'] = $orderprice;
    			$orderInfo['orderderateprice'] = $this->_post('orderderateprice'); //订单减免优惠
    			$orderInfo['ordersubtotal'] = $this->_post('ordersubtotal'); //订单小计（没优惠）
    			$orderInfo['orderint'] = $this->_post('orderint') ? $this->_post('orderint') : '0.00'; //积分
    			$orderInfo['orderfreight'] = $this->_post('orderfreight'); //订单运费
    			$orderInfo['orderweight'] = $this->_post('orderweight'); //订单重量
    			$orderInfo['orderpaymethod'] = $orderpaymethod;
    			$orderInfo['orderinvoice'] = $this->_post('orderinvoice');
    			$orderInfo['orderinvoicetitle'] = $this->_post('orderinvoicetitle');
    			$orderInfo['eshopdiscounttitle'] = $this->_post('eshopdiscounttitle');
    			$orderInfo['eshopdiscountmoney'] = $this->_post('eshopdiscountmoney');
    			$orderInfo['membernote'] = $this->_post('membernote')?$this->_post('membernote'):'';
    			//查询出设置订单自动取消的截止时间
    			$mallorderautoset = M('company')->where(array('id'=>$this->companyid))->field('id,mallorderautoset')->find();
    			$orderInfo['ordernopayendtime'] = $time + ($mallorderautoset['mallorderautoset']*3600); //订单未付款通知的截止时间
    			$orderInfo['issendmessage'] = 2; //是否已经发送未付款通知消息
    			$orderInfo['updatetime'] = $orderInfo['createtime'] = $time;
    			M('mall_order_info')->add($orderInfo);
    			 
    			//订单商品表
    			$orderGoodsDate['id'] = guidNow();
    			$orderGoodsDate['companyid'] = $this->companyid;
    			$orderGoodsDate['mid'] = $this->mid;
    			$orderGoodsDate['orderid'] = $orderid;
    			$orderGoodsDate['goodtype'] = $good['goodtype'];
    			$orderGoodsDate['vouchersid'] = $good['vouchersid'];
    			$orderGoodsDate['prefix'] = $good['prefix']; //虚拟券前缀
    			$orderGoodsDate['pricetype'] = $good['pricetype']; //定价策略
    			$orderGoodsDate['goodid'] = $goodsid;
    			$orderGoodsDate['goodname'] = $good['goodname'];
    			$orderGoodsDate['goodpic'] = $good['pic'];
    			$orderGoodsDate['goodprice'] = $good['saleprice'];
    			$orderGoodsDate['goodint'] = $good['intprice'];
    			$orderGoodsDate['goodnum'] = $goodnum;
    			$orderGoodsDate['goodskuid'] = $goodskuid;
    			$orderGoodsDate['goodweight'] = $good['weight'];
    			$orderGoodsDate['usetimelimittype'] = $good['usetimelimittype'];
    			$orderGoodsDate['usetimelimitset'] = $good['usetimelimitset'];
    			$orderGoodsDate['useshopslimitset'] = $good['useshopslimitset'];
    			$orderGoodsDate['backorderpolicyset'] = $good['backorderpolicyset'];
    			$orderGoodsDate['useinfo'] = $good['useinfo'];
    			$orderGoodsDate['goodskuname'] = $mallgoodssku['name'];
    			$orderGoodsDate['updatetime'] = $orderGoodsDate['createtime'] = time();
    			$orderGoodsReturn = M('mall_order_goods')->add($orderGoodsDate);
    			 
    			//从库存减掉商品购买数量
    			if($good['goodtype'] == 1 || $good['goodtype'] == 3 || $good['goodtype'] == 4 || $good['goodtype'] == 5){
    				M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'id'=>$goodskuid))->setDec('stockamount',$goodnum);
    			}
    			$stockamountReturn = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$goodsid))->setDec('stockamount',$goodnum);
    			 
    			//已售罄：当商品库存为：0时，改变商品状态为：已售罄
    			$issoldout = 1;
    			$stockamount = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$goodsid))->getField('stockamount');
    			if($stockamount < 1){
    				$issoldout = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$goodsid))->save(array('issoldout'=>1));
    			}
    			//我的电子券
    			$vouchersiduse = 1;
    			$vouchersid = $this->_post('vouchersid');
    			if($vouchersid){
    				/* $vouchersiduse = M('member_vouchers')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'id'=>$vouchersid))->save(array('isused'=>1,'usetime'=>$time,'updatetime'=>$time));
    				 if($vouchersiduse){
    				//增加eshop优惠券的核销量
    				$voucherinfoid = M('member_vouchers')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'id'=>$vouchersid))->getField('voucherinfoid');
    				$countSaveData['updatetime'] = $time;
    				$countSaveData['verificationnum'] = array('exp', '`verificationnum`+1');
    				$countSaveReturn = M('member_marketing_activities_voucher_info')->where(array('companyid'=>$this->companyid,'id'=>$voucherinfoid))->save($countSaveData);
    				$vouchername = M('member_vouchers')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'id'=>$vouchersid))->getField('vouchername');
    				$openid = M('member_wechat_info')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid))->getField('openid');
    				if($openid){
    				$this->WeChatTemplateMessageSend('10',$openid,$this->companyid,'','',array('核销卡券','券通知'),array($vouchername));
    				}
    				} */
    				//获取优惠券使用状态
    				$vouchersIsUsed = M('member_vouchers')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'id'=>$vouchersid))->getField('isused');
    				if($vouchersIsUsed == 2){
    					$sn =  M('member_vouchers')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'id'=>$vouchersid))->getField('sn');
    					$option['vouchertype'] = 1;
    					$option['vouchernumber'] = $sn;
    					$option['cid'] = $this->companyid;
    					$option['usetype'] = 4;
    					$option['users'] =  1;
    					$option['getway'] = 2;
    					$this->verificationVouchersSCRM5($option);
    				}else{
    					$ajaxReturn['msg'] = '优惠券已经被使用，请重新选择';
    					echo json_encode($ajaxReturn);
    					exit();
    				}
    			}
    			//优惠口令
    			$dmsOrderInfoReturn = 1;
    			$dmsCustomerSuc = 1;
    			$dmsDiscoukeyId = $this->_post('dmsDiscoukeyId');
    			if($dmsDiscoukeyId){
    				$discoukeyInfo = M('dms_discoukey')->where(array('id'=>$dmsDiscoukeyId,'endtime'=>array('gt',$time)))->field('id,discoukey,discoutype,discoumoney2,startdiscoumoney3,discoumoney3,discouratio4,startdiscoumoney5,discouratio5,giftname6,startdiscoumoney7,giftname7,startdiscoumoney8,discoumoney8,wageratio')->find();
    				if($discoukeyInfo){
    					//DMS订单表
    					$dmsOrderInfo['id'] = guidNow();
    					$dmsOrderInfo['companyid'] = $this->companyid;
    					$dmsOrderInfo['mid'] = $this->mid;
    					$dmsOrderInfo['keyid'] = $dmsDiscoukeyId;
    					$dmsOrderInfo['orderid'] = $orderid;
    					$dmsOrderInfo['ordermoney'] = $dmsOrderInfo['paymoney'] = $orderprice;
    					$dmsOrderInfo['wagesmoney'] = $orderprice*($discoukeyInfo['wageratio']/100);
    					$dmsOrderInfo['wagesprop'] = $discoukeyInfo['wageratio'];
    					$dmsOrderInfo['discoukey'] = $discoukeyInfo['discoukey'];
    					$dmsOrderInfo['discoutype'] = $discoukeyInfo['discoutype'];
    					if($discoukeyInfo['discoutype'] == 2){
    						$dmsOrderInfo['discoumoney'] = $discoukeyInfo['discoumoney2'];//优惠金额
    					}elseif($discoukeyInfo['discoutype'] == 3){
    						$dmsOrderInfo['startdiscoumoney'] = $discoukeyInfo['startdiscoumoney3'];//开始优惠
    						$dmsOrderInfo['discoumoney'] = $discoukeyInfo['discoumoney3'];//优惠金额
    					}elseif($discoukeyInfo['discoutype'] == 4){
    						$dmsOrderInfo['discoumoney'] = $orderprice/(1-$discoukeyInfo['discouratio4']/100)*($discoukeyInfo['discouratio4']/100);//优惠金额
    						$dmsOrderInfo['discouratio'] = $discoukeyInfo['discouratio4'];//优惠比例
    					}elseif($discoukeyInfo['discoutype'] == 5){
    						$dmsOrderInfo['startdiscoumoney'] = $discoukeyInfo['startdiscoumoney5'];//开始优惠
    						$dmsOrderInfo['discoumoney'] = $orderprice/(1-$discoukeyInfo['discouratio5']/100)*($discoukeyInfo['discouratio5']/100);//优惠金额
    						$dmsOrderInfo['discouratio'] = $discoukeyInfo['discouratio5'];//优惠比例
    					}elseif($discoukeyInfo['discoutype'] == 6){
    						$dmsOrderInfo['giftname'] = $discoukeyInfo['giftname6'];//礼品名称
    					}elseif($discoukeyInfo['discoutype'] == 7){
    						$dmsOrderInfo['startdiscoumoney'] = $discoukeyInfo['startdiscoumoney7'];//开始优惠
    						$dmsOrderInfo['giftname'] = $discoukeyInfo['giftname7'];//礼品名称
    					}elseif($discoukeyInfo['discoutype'] == 8){ // 每满减优惠
    						$dmsOrderInfo['startdiscoumoney'] = $discoukeyInfo['startdiscoumoney8'];//开始优惠
    						if($orderprice >= $discoukeyInfo['startdiscoumoney8']){
    							$dmsOrderInfo['discoumoney'] = '0.00';
    							$dmsOrderInfo['discoumoney'] = (floor($orderprice/$discoukeyInfo['startdiscoumoney8']))*$discoukeyInfo['discoumoney8'];
    						}else{
    							$dmsOrderInfo['discoumoney'] = 0.00;//优惠金额
    						}
    					}
    					$dmsOrderInfo['orderstatus'] = 1;
    					$dmsOrderInfo['ordertype'] = 1;
    					$dmsOrderInfo['updatetime'] = $dmsOrderInfo['createtime'] = $time;
    					$dmsOrderInfoReturn = M('dms_order')->add($dmsOrderInfo);
    						
    					//增加客户
    					$customerCou = M('dms_customer')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid,'keyid'=>$dmsDiscoukeyId))->count();
    					if(!$customerCou){
    						$customerData['id'] = guidNow();
    						$customerData['companyid'] = $this->companyid;
    						$customerData['mid'] = $this->mid;
    						$customerData['keyid'] = $dmsDiscoukeyId;
    						$customerData['createtime'] = $customerData['updatetime'] = $time;
    						$customerSuc = M('dms_customer')->add($customerData);
    						//累计客户数
    						$discoukeySuc = M('dms_discoukey')->where(array('companyid'=>$this->companyid,'id'=>$dmsDiscoukeyId))->setInc('totalpeoplesum');
    						M('dms_discoukey')->where(array('companyid'=>$this->companyid,'id'=>$dmsDiscoukeyId))->setInc('thismonthpeoplesum');
    						if(!$customerSuc || !$discoukeySuc){
    							$dmsCustomerSuc = 0;
    						}
    					}
    				}
    			}
    			 
    			$isDispatching = $this->_post('isDispatching');
    			if($orderInfoReturn && $orderGoodsReturn && $vouchersiduse && $dmsOrderInfoReturn && $dmsCustomerSuc){
    				M()->commit();
    				$ajaxReturn['code'] = 200;
    				$ajaxReturn['msg'] = '订单提交成功';
    				$ajaxReturn['id'] = $orderInfoReturn;
    				$ajaxReturn['orderid'] = $orderid;
    			}else{
    				M()->rollback();
    				$ajaxReturn['msg'] = '订单提交失败，请稍候重试';
    				if($isDispatching == 2){
    					$ajaxReturn['msg'] = '该地区暂时不支持配送';
    				}
    			}
    		}
    	}
    	echo json_encode($ajaxReturn);
    }
    /**
     * 选择服务类型
     * @author 姚成凯<kevin@renlaifeng.cn>
     * @since  2016-8-22
     */
    /* public function ajaxOrderPrice(){
     $return['price'] = '0.00';
    
    $id = $this->_post('id');
    $type = $this->_post('type');
    if($type < 3){
    $info = M('mall_order_goods')->where(array('companyid'=>$this->companyid,'id'=>$id))->field('goodprice,goodnum')->find();
    $return['price'] = format_number($info['goodprice']*$info['goodnum']);
    }
    echo json_encode($return);
    } */
    /**
     * 我的优惠券
     */
    public function myVouchersold(){
    	$this->setPageTitle(array('title' => '可用优惠券'));
    	$this->checkMemberLogin();
    	$goodsid = $this->_get('goodsid');
    	$this->assign('goodsid', $goodsid);
    	$goodsskuid = $this->_get('goodsskuid');
    	$this->assign('goodsskuid', $goodsskuid);
    	$goodsnum = $this->_get('goodsnum');
    	$this->assign('goodsnum', $goodsnum);
    	$addressid = $this->_get('addressid');
    	$this->assign('addressid', $addressid);
    	$ordertype = $this->_get('ordertype');
    	$this->assign('ordertype', $ordertype);
    	$goodtype = $this->_get('goodtype');
    	$this->assign('goodtype', $goodtype);
    	$vouchersid = $this->_get('vouchersid');
    	$this->assign('vouchersid', $vouchersid);
    	$orderPrice = $this->_get('orderPrice');
    	$this->assign('orderPrice',$orderPrice);
    	$where['companyid'] = $this->companyid;
    	$where['mid'] = $this->mid;
    	$where['isused'] = '2';
    	$where['issend'] = '2';
    	$where['_string'] = ' (vouchertype=7 AND useendtime >" '. time() . '")';
    	if ($orderPrice) {
    		$where['_string'] .= " AND (parvalue <= " . $orderPrice . " || parvalue = '0.00')";
    	}
    	//未使用
    	$vouchers['count'] = M('member_vouchers')->where($where)->count();
    	$vouchers['list'] = M('member_vouchers')->where($where)->field('id,mid,sn,parvalue,usetimelimittype,useshopslimitset,useinfo,discounttype,minus,discount,fullminus,fulldiscount,eachfullminus,usestarttime,useendtime,vouchername')->order('id DESC')->select();
    	foreach($vouchers['list'] as $key=>$val){
    		if($val['discounttype'] == 1){
    			$vouchers['list'][$key]['derate'] = floor($val['minus']);
    		}elseif($val['discounttype'] == 2){
    			$vouchers['list'][$key]['derate'] = '折'.$val['discount'].'%';
    		}elseif($val['discounttype'] == 3){
    			$shouldPay = explode(',', $val['fullminus']);
    			$vouchers['list'][$key]['fullPrice'] = $shouldPay[0];
    			$vouchers['list'][$key]['derate'] = $shouldPay[1];
    		}elseif($val['discounttype'] == 4){
    			$shouldPay = explode(',', $val['fulldiscount']);
    			$vouchers['list'][$key]['fullPrice'] = $shouldPay[0];
    			$vouchers['list'][$key]['derate'] = '折'.$shouldPay[1].'%';
    		}elseif($val['discounttype'] == 5){
    			$shouldPay = explode(',', $val['eachfullminus']);
    			$vouchers['list'][$key]['fullPrice'] = $shouldPay[0];
    			$vouchers['list'][$key]['derate'] = $shouldPay[1];
    		}
    	}
    	$this->assign('vouchers', $vouchers);
    	$this->display();
    }
   
    /**
     * 优惠口令
     * @author 姚成凯<kevin@renlaifeng.cn>
     * @since  2016-2-26
     */
    public function ajaxPreferentialPasswordOld(){
    	$allPrice = $this->_post('allPrice');
    	$discoukey = $this->_post('discoukey');
    	 
    	$info = M('dms_discoukey')->where(array('companyid'=>$this->companyid,'appscene'=>array('like','%,1,%'),'discoukey'=>$discoukey,'endtime'=>array('gt',time())))->field('id,discoukey,discoutype,discoumoney2,startdiscoumoney3,discoumoney3,discouratio4,startdiscoumoney5,discouratio5,giftname6,startdiscoumoney7,giftname7,startdiscoumoney8,discoumoney8')->find();
    	if($info){
    		$isshow = 1; //是否显示$return['html']
    		if($info['discoutype'] == 1){
    			//无优惠
    			$isshow = 2;
    			$return['tips'] = "本优惠口令无优惠";
    			$amount = $allPrice;
    		}elseif($info['discoutype'] == 2){
    			//立减优惠
    			$discount = "立减".$info['discoumoney2'];
    			$utility = "-".$info['discoumoney2'];
    			$amount = $allPrice - $info['discoumoney2'];
    		}elseif($info['discoutype'] == 3){
    			//满减优惠
    			$discount = "满".$info['startdiscoumoney3']."立减".$info['discoumoney3'];
    			if($allPrice >= $info['startdiscoumoney3']){
    				$utility = "-".$info['discoumoney3'];
    				$amount = $allPrice - $info['discoumoney3'];
    			}else{
    				$utility = "-0.00";
    				$amount = $allPrice;
    			}
    		}elseif($info['discoutype'] == 4){
    			//立折优惠
    			$discount = "立折优惠".$info['discouratio4']."%";
    			$utility = "-".$allPrice*($info['discouratio4']/100);
    			$amount = $allPrice - $allPrice*($info['discouratio4']/100);
    		}elseif($info['discoutype'] == 5){
    			//满折优惠
    			$discount = "满".$info['startdiscoumoney5']."立折".$info['discouratio5']."%";
    			if($allPrice >= $info['startdiscoumoney5']){
    				$utility = "-".$allPrice*($info['discouratio5']/100);
    				$amount = $allPrice - $allPrice*($info['discouratio5']/100);
    			}else{
    				$utility = "-0.00";
    				$amount = $allPrice;
    			}
    		}elseif($info['discoutype'] == 6){
    			//礼品赠送
    			$discount = $info['giftname6'];
    			$utility = $info['giftname6'];
    			$amount = $allPrice;
    		}elseif($info['discoutype'] == 7){
    			//礼品满赠
    			$discount = "满".$info['startdiscoumoney7']."赠送".$info['giftname7'];
    			if($allPrice >= $info['startdiscoumoney7']){
    				$utility = $info['giftname7'];
    			}else{
    				$utility = "-0.00";
    			}
    			$amount = $allPrice;
    		}elseif($info['discoutype'] == 8){
    			//每满减优惠
    			$discount = "每满".$info['startdiscoumoney8']."立减".$info['discoumoney8'];
    			if($allPrice >= $info['startdiscoumoney8']){
    				$xy = '0.00';
    				$xy = (floor($allPrice/$info['startdiscoumoney8']))*$info['discoumoney8'];
    				$utility = "-".$xy;
    				$amount = $allPrice-$xy;
    			}else{
    				$utility = "-0.00";
    				$amount = $allPrice;
    			}
    		}
    		if($amount > 0){
    			$return['code'] = 200;
    			$return['amount'] = format_number($amount);
    			if($isshow == 1){
    				$return['html'] = '<div class="beizhu-kl" data-id="'.$info[id].'"><p class="yhkl-4p">优惠口令：<span class="dms-keyname">'.$info['discoukey'].'</span></p><p class="yhkl-4p">立享优惠：<span class="dms-discou">'.$discount.'</span></p><p class="yhkl-4p">口令效用：<span class="dms-xy hon-sp">'.format_numer($utility).'</span></p></div>';
    			}
    		}else{
    			$return['code'] = 300;
    			$return['tips'] = '优惠金额不能大于或等于订单金额';
    			$return['amount'] = $allPrice;
    		}
    	}else{
    		$return['code'] = 300;
    		$return['tips'] = '本优惠口令不存在';
    		$return['amount'] = $allPrice;
    	}
    	echo json_encode($return);
    }


    public function ajaxOrderStatus(){
        if(IS_POST){
            $res = M("mall_order_info")->where(array("orderid"=>$this->_post("orderid")))->getField("orderstatus");
            if($res==2){
                $ajax['code'] = 200;
                $ajax['msg'] = "success";
            }else{
                $ajax['code'] = 300;
                $ajax['msg'] = "error";
            }
            echo json_encode($ajax);
        }
    }
}
?>