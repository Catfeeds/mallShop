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
	 * 立即购买 确认订单
	 * @author Tomas<416369046@qq.com>
	 * @since  2017-11-08
	 */
	public function createBuyNowOrder(){
        session('historyUrl','http://' . $_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"]);// 用于登陆后跳回本页面
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
                $addressInfo['id'] = '';
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
				$where['mid'] = $this->mid;
                $where['status'] = '2';
				//可使用的优惠券
				$vouchers['count'] = M('member_vouchers')->where($where)->count();
				$this->assign('vouchers',$vouchers);
			}
			$sn = $_GET['sn'];
			$voucherInfo = M("member_vouchers")->where(array("id"=>$sn))->find();
			if($voucherInfo){
                $orderInfo['allPrice'] = $orderInfo['allPrice']-$voucherInfo['reduce'];
            }
			if($orderInfo['allPrice'] <= 0){
				// 如果订单总价小于0 则默认最低价格为0.01
				$orderInfo['allPrice'] = '0.01';
			}
			$orderInfo['allPrice'] = format_number($orderInfo['allPrice']);
            $this->assign('orderInfo',$orderInfo);
            $this->assign('voucherInfo',$voucherInfo);
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
    	$where['mid'] = $this->mid;
    	$where['status'] = 2;
    	$skuInfo = M("mall_goods_sku")->where(array("id"=>$goodsskuid))->find();
        $price = $skuInfo['saleprice']*$goodsnum;
    	//未使用
    	$vouchers = M('member_vouchers')->where($where)->order('end_time DESC')->select();
        $this->assign('vouchers', $vouchers);
        $this->assign('price', $price);
    	$this->display();
    }
    public function addVoucehr(){
        $indexVoucherInfo = M("vouchers")->where(array("sn"=>$_POST['sn']))->find();
        if($indexVoucherInfo){
            $res = M('member_vouchers')->where(array("sn"=>$indexVoucherInfo['sn'],"mid"=>$this->mid))->count();
            if($res>0){
                $ajax['code'] = 500;
                $ajax['msg'] = '已经兑换过次优惠券';
            }else{
                $data['mid'] = $this->mid;
                $data['sn'] = $indexVoucherInfo['sn'];
                $data['title'] = $indexVoucherInfo['title'];
                $data['end_time'] = $indexVoucherInfo['end_time'];
                $data['type'] = $indexVoucherInfo['type'];
                $data['is_index'] = $indexVoucherInfo['is_index'];
                $data['full'] = $indexVoucherInfo['full'];
                $data['reduce'] = $indexVoucherInfo['reduce'];
                $data['info'] = $indexVoucherInfo['info'];
                $data['status'] = 2;
                $data['createtime'] = $data['updatetime'] = time();
                M("member_vouchers")->add($data);
                $ajax['code'] = 200;
            }

        }else{
            $ajax['code'] = 500;
            $ajax['msg'] = '没有次优惠券';
        }
        echo json_encode($ajax);
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
						$orderInfo['consigneeaddress'] = $defaultAddress['province'].$defaultAddress['city'].$defaultAddress['district'].$defaultAddress['town'].$defaultAddress['address'];
					}
				}
				$orderInfo['ordersubtotal'] = format_number($orderprice); 				// 订单金额 = 商品金额
				$orderid = $this->newOrderID('2', 'E', $this->companyid);				// 订单号
				$discountPrice = $orderInfo['ordersubtotal'];			
				$derateAllPrice = '0.00';												// 优惠总价
				$vouchersid = $this->_post("vouchersid");								// 优惠券id
				$deratePrice = '0.00';
				//********************* 使用优惠券 *******************************************************************************************************
				$vouchersInfo = M('member_vouchers')->where(array('mid'=>$this->mid,'id'=>$vouchersid))->find();

				if($vouchersInfo){
                    $deratePrice = $vouchersInfo['reduce'];
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
				$orderInfo['orderpaymethod'] = $orderpaymethod;								// 订单支付方式：1：微信支付；7：储值支付；
				$orderInfo['orderinvoice'] = $this->_post('orderinvoice');					// 是否需要发票
                $orderInfo['orderinvoicetype'] = $this->_post('orderinvoicetype');		// 发票类型
                $orderInfo['orderinvoicetitle'] = $this->_post('orderinvoicetitle');		// 发票抬头
                $orderInfo['orderinvoicemailingaddress'] = $this->_post('orderinvoicemailingaddress');		// 发票抬头
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
				$orderGoodsData['goodweight'] = 1;											// 商品重量
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
					$vouchersIsUsed = M('member_vouchers')->where(array('mid'=>$this->mid,'id'=>$vouchersid))->save(array("status"=>1,"usetime"=>time(),"orderid"=>$orderid));
				}
				$dmsOrderInfoReturn = 1;
				if(session('shareopenid') && session('salestype') == 2){
					// 这里可以看成是经销用户生成
					$dmsInfo = M('mall_dms_base')->where(array('companyid'=>$this->companyid))->field()->find();
					// 获取分享人的mid
					$sharemid = M('member_wechat_info')->where(array('companyid'=>$this->companyid,'openid'=>session('shareopenid')))->getField('mid');
					if($dmsInfo){
						//经销订单表
						$dmsOrderInfo['id'] = guidNow();
						$dmsOrderInfo['companyid'] = $this->companyid;
						$dmsOrderInfo['mid'] = $sharemid;					// 订单属于分享人
						$dmsOrderInfo['buymid'] = $this->mid;				// 购买人的mid
						$dmsOrderInfo['orderid'] = $orderid;
						$dmsOrderInfo['ordermoney'] = format_number($orderprice);
						$dmsOrderInfo['paymoney'] = $allPrice;
						$dmsOrderInfo['wagesmoney'] = $orderprice*($dmsInfo['commissionrate']/100);
						$dmsOrderInfo['wagesprop'] = $dmsInfo['commissionrate'];
						$dmsOrderInfo['orderstatus'] = 1;
						$dmsOrderInfo['ordertype'] = 1;
						$dmsOrderInfo['updatetime'] = $dmsOrderInfo['createtime'] = $time;
						$dmsOrderInfoReturn = M('mall_dms_order')->add($dmsOrderInfo);
						// 判断此经销是否属于代理  若属于代理 则代理获得8%的佣金
						$partnerInfo = M('member_wechat_info')->where(array('mid'=>$this->mid))->field('zpartnermid')->find();
						if($partnerInfo['zpartnermid']){
							// 生成代理分成的订单
							$zData['id'] = guidNow();
							$zData['companyid'] = $this->companyid;
							$zData['mid'] = $partnerInfo['zpartnermid'];  	// 订单属于代理 存入代理的mid
							$zData['buymid'] = $$this->mid;					// 购买人的mid
							$zData['orderid'] = $orderid;
							$zData['orderprice'] = $orderprice;
							$zData['payprice'] = $allPrice;
							$zData['commission'] = $allPrice*0.08; 			//代理分成为8%
							$zData['orderstatus'] = 1;
							$zData['status'] = 2;
							$zData['type'] = 2;								// 经销订单
							$zData['createtime'] = $zData['updatetime'] = $time;
							$zResult = M('mall_exhibition_partner_order')->add($zData);
						}
						// 获取收货地址 若在代理区域 则代理获得8%的佣金
						if($defaultAddress['province']){
							$wheredl['province'] = $defaultAddress['province'];
						}
						if($defaultAddress['city']){
							$wheredl['city'] = $defaultAddress['city'];
						}
						if($defaultAddress['district']){
							$wheredl['area'] = $defaultAddress['district'];
						}
						if($defaultAddress['town']){
							$wheredl['town'] = $defaultAddress['town'];
						}
						// 获取管理此区域的代理mid
						$dlInfo = M('mall_exhibition_partner_areamanage')->where($wheredl)->field('id,mid')->find();
						if($dlInfo){
							// 生成代理订单
							$zData['id'] = guidNow();
							$zData['companyid'] = $this->companyid;
							$zData['mid'] = $dlInfo['mid'];  				// 订单属于代理 存入代理的mid
							$zData['buymid'] = $this->mid;					// 购买人的mid
							$zData['orderid'] = $orderid;
							$zData['orderprice'] = $orderprice;
							$zData['payprice'] = $allPrice;
							$zData['commission'] = $allPrice*0.08; 			//代理分成为8%
							$zData['orderstatus'] = 1;
							$zData['status'] = 2;
							$zData['type'] = 3;								// 区域订单
							$zData['createtime'] = $zData['updatetime'] = $time;
							$zResult = M('mall_exhibition_partner_order')->add($zData);
						}
					}
				}elseif(session('shareopenid') && session('salestype') == 1){
					// 这里可以看成是代理用户生成
					$partnerInfo = M('mall_exhibition_partner_base')->where(array('companyid'=>$this->companyid))->field('id,commissionrate')->find();
					// 获取分享人的mid
					$sharemid = M('member_wechat_info')->where(array('companyid'=>$this->companyid,'openid'=>session('shareopenid')))->getField('mid');
					if($partnerInfo){
						// 生成直销订单
						$zData['id'] = guidNow();
						$zData['companyid'] = $this->companyid;
						$zData['mid'] = $sharemid;							// 订单属于分享人
						$zData['buymid'] = $this->mid;						// 购买人的mid
						$zData['orderid'] = $orderid;
						$zData['orderprice'] = $orderprice;
						$zData['payprice'] = $allPrice;
						$zData['commission'] = $allPrice*($partnerInfo['commissionrate']/100);
						$zData['orderstatus'] = 1;
						$zData['status'] = 2;
						$zData['type'] = 1;									// 直销订单
						$zData['createtime'] = $zData['updatetime'] = $time;
						$zResult = M('mall_exhibition_partner_order')->add($zData);
					}
					// 获取收货地址 若在代理区域 则代理获得8%的佣金
					if($defaultAddress['province']){
						$wheredl['province'] = $defaultAddress['province'];
					}
					if($defaultAddress['city']){
						$wheredl['city'] = $defaultAddress['city'];
					}
					if($defaultAddress['district']){
						$wheredl['area'] = $defaultAddress['district'];
					}
					if($defaultAddress['town']){
						$wheredl['town'] = $defaultAddress['town'];
					}
					// 获取管理此区域的代理mid
					$dlInfo = M('mall_exhibition_partner_areamanage')->where($wheredl)->field('id,mid')->find();
					if($dlInfo){
						// 生成代理订单
						$zData['id'] = guidNow();
						$zData['companyid'] = $this->companyid;
						$zData['mid'] = $dlInfo['mid'];  					// 订单属于代理 存入代理的mid
						$zData['buymid'] = $this->mid;						// 购买人的mid
						$zData['orderid'] = $orderid;
						$zData['orderprice'] = $orderprice;
						$zData['payprice'] = $allPrice;
						$zData['commission'] = $allPrice*0.08; 				//代理分成为8%
						$zData['orderstatus'] = 1;
						$zData['status'] = 2;
						$zData['type'] = 3;									// 区域订单
						$zData['createtime'] = $zData['updatetime'] = $time;
						$zResult = M('mall_exhibition_partner_order')->add($zData);
					}
				}
				if($orderInfoReturn && $orderGoodsReturn && $vouchersiduse){
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