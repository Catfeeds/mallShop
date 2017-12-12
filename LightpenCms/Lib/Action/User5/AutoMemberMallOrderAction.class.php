<?php
/**
 * 自动处理订单
 * Enter description here ...
 * @author yongfei.zhao
 *
 */
class AutoMemberMallOrderAction extends BaseAction{

	public function __construct(){
		parent::__construct();
		ignore_user_abort();
		set_time_limit(0);
	}
	/**
	 * 进行订单修改
	 */
	public function index(){
		$xml = file_get_contents('php://input');
		$return['code'] = 300;//无xml 数据
		if($xml){
			$xmldata = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
			if($xmldata){
				$companyid = $xmldata['attach'];
				$out_trade_no = $xmldata['out_trade_no'];
				$orderInfo = M('mall_order_info')->where(array('out_trade_no'=>$out_trade_no))->field('id,mid,companyid,borderid,out_trade_no,orderid,ordertitle,orderpaymethod,orderstatus,temporderstatus,paytime,createtime,orderprice,orderint,consigneename,consigneephone,consigneeaddress,groupinfoid')->find();
				if ($orderInfo['temporderstatus'] == '2' && ($orderInfo['orderstatus'] == 2||$orderInfo['orderstatus'] == 4)){
                    $return['code'] = 201;//已经支付过
	    		}else{
	    		    //获取发消息的openid
	    		    $orderOpenid = M('member_wechat_info')->where(array('companyid'=>$orderInfo['companyid'],'mid'=>$orderInfo['mid']))->getField('openid');
	    		    //从订单商品表中随机取出一个商品作为商品详情
	    		    $mallGoodInfo = M('mall_order_goods')->where(array('companyid'=>$orderInfo['companyid'],'orderid'=>$orderInfo['orderid']))->field('goodname')->limit(1)->find();
	    		    if($orderInfo['orderpaymethod'] == 1){
	    		    	//发送付款成功通知
		    		    $this->WeChatTemplateMessageSend('7',$orderOpenid,$orderInfo['companyid'],'','',array($orderInfo['orderprice'],htmlspecialchars_decode($mallGoodInfo['goodname']),$orderInfo['consigneename'].' '.$orderInfo['consigneephone'].' '.$orderInfo['consigneeaddress'],$orderInfo['orderid']),'');
	    		    }
	    		    //当订单为拼团订单时，这里不去跳转执行base共用方法，只有拼团成功之后才去执行加减积分以及发券（实物商品待发货）等等状态的改变
	    		    if($orderInfo['groupinfoid'] != 0){
	    		    	$return = $this->mallGroupOrderPaySCRM5($orderInfo['orderid'],$orderInfo['companyid']);
	    		    }else{
	    		    	$return = $this->mallOrderPaySCRM5($orderInfo['orderid'],$orderInfo['companyid']);
	    		    }
					$return['id'] = $orderInfo['id'];
					$return['msg'] .= $orderInfo['id'].'/'.$orderInfo['orderpaymethod'].'/'.$orderInfo['mid'].'/'.$orderInfo['companyid'];
				}
			}
		}
    	echo json_encode($return);
	}
	/**
	 * 储值支付的订单修改
	 * @author Thomas<416369046@qq.com>
	 * @since  2016-11-25
	 */
	public function storedValue(){
		$time = time();
		$return['code'] = 300;
		$orderid = $this->_post('orderid');
		if($orderid){
			//获取订单的所有信息
			$orderInfo = M('mall_order_info')->where(array('orderid'=>$orderid))->field('id,mid,companyid,borderid,out_trade_no,orderid,ordertitle,orderpaymethod,orderstatus,temporderstatus,paytime,createtime,orderprice,ordersubtotal,ordertype,consigneename,consigneephone,consigneeaddress,groupinfoid')->find();
			$companyid = $orderInfo['companyid'];
			//$serialNumberOI = M('mall_order_info')->where(array('companyid'=>$companyid,'createtime'=>array('between',array($time,$time+1))))->count();
			//$out_trade_no = $orderInfo['out_trade_no'] ? $orderInfo['out_trade_no'] : orderID('3', 'W', $companyid, $serialNumberOI+1).'W'.($serialNumberOI+1);
			//将商户号存入订单表
			//$updateOutTradeNoReturn = M('mall_order_info')->where(array('orderid'=>$orderid,'companyid'=>$companyid))->save(array('out_trade_no'=>$out_trade_no,'updatetime'=>time()));
			//储值支付将 商户号清空
			$updateOutTradeNoReturn = M('mall_order_info')->where(array('orderid'=>$orderid,'companyid'=>$companyid))->save(array('out_trade_no'=>'','updatetime'=>time()));
			//判断实际储值与订单金额做比较
			$accountbalance = M('member_register_info')->where(array('companyid'=>$companyid,'id'=>$orderInfo['mid']))->getField('accountbalance');
			if($accountbalance >= $orderInfo['orderprice']){
				//包含实物商品 订单状态的改变
				if($orderInfo['ordertype'] == '1'){
			        $updateRetun = M('mall_order_info')->where(array('orderid'=>$orderid))->save(array('temporderstatus'=>'1','orderstatus'=>'2','orderpaymethod'=>'7','paytime'=>time(),'updatetime'=>time()));
			    }else{
			        $updateRetun = M('mall_order_info')->where(array('orderid'=>$orderid))->save(array('temporderstatus'=>'1','orderstatus'=>'4','orderpaymethod'=>'7','paytime'=>time(),'updatetime'=>time(),'receivaltime'=>time()));
			    }
			    //获取发消息的openid
			    $orderOpenid = M('member_wechat_info')->where(array('companyid'=>$companyid,'mid'=>$orderInfo['mid']))->getField('openid');
			    //从订单商品表中随机取出一个商品作为商品详情
			    $mallGoodInfo = M('mall_order_goods')->where(array('companyid'=>$companyid,'orderid'=>$orderid))->field('goodname')->limit(1)->find();
			    //发送付款成功通知
			    $this->WeChatTemplateMessageSend('7',$orderOpenid,$companyid,'','',array($orderInfo['orderprice'],htmlspecialchars_decode($mallGoodInfo['goodname']),$orderInfo['consigneename'].' '.$orderInfo['consigneephone'].' '.$orderInfo['consigneeaddress'],$orderid),'');
			    //当订单为拼团订单时，这里不去跳转执行base共用方法，只有拼团成功之后才去执行加减积分以及发券（实物商品待发货）等等状态的改变
			    if($orderInfo['groupinfoid'] != 0){
			    	$updateOrderPay = $this->mallGroupOrderPaySCRM5($orderid,$companyid);
			    }else{
			    	$updateOrderPay = $this->mallOrderPaySCRM5($orderid,$companyid);
			    }
			    if($updateOrderPay){
				    $return['code'] = 200;
				    $return['id'] = $orderInfo['id'];
				    $return['tips'] .= $orderInfo['id'].'/'.$orderInfo['orderpaymethod'].'/'.$orderInfo['mid'].'/'.$orderInfo['companyid'];
			    }
			}else{
				$return['tips'] = '您的储值不足支付此次订单';
			}
		}else{
			$return['tips'] = '订单号错误';
		}
		echo json_encode($return);
	}
	/**
	 * 
	 * 定时关闭订单(新) 每一分钟执行一次
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2017-1-16
	 */
	public function autoSaveMallOrder(){
		$time = time();
		$companyList = M('company')->where(array('_string'=>"isclose ='0' and  viptime > ".time()." and mallorderautoset > 0 "))->field('id,mallorderautoset')->select();
		if($companyList){
			$mallOrderInfoModel = M('mall_order_info');
			$mallOrderGoodsModel = M('mall_order_goods');
			$mallGoodsModel = M('mall_goods');
			$mallGoodsSkuModel = M('mall_goods_sku');
			foreach ($companyList as $clKey=>$clVal){
				$mallOrderInfoList = $mallOrderInfoModel->where(array('orderstatus'=>'1','ordernopayendtime'=>array('lt',$time)))->field('orderid')->select();
				if($mallOrderInfoList){
					foreach ($mallOrderInfoList as $mKey=>$mVal){
						// 修改订单状态
						$orderResult = $mallOrderInfoModel->where(array('orderid'=>$mVal['orderid']))->save(array('orderstatus'=>'5','updatetime'=>$time));
						if(!$orderResult){
							$log .= '任务失败  SQL：'.M()->getLastSql()."\n";
						}
						$orderGoods = $mallOrderGoodsModel->where(array('orderid'=>$mVal['orderid']))->field('id,goodid,goodtype,goodskuid,goodnum')->select();
						if($orderGoods){
							//库存回滚
							foreach($orderGoods as $oKey=>$oVal){
								if($oVal['goodtype'] != 2 || $oVal['goodtype'] != 6 || $oVal['goodtype'] != 7){
									$mallgoodsskustockamount = $mallGoodsSkuModel->where(array('goodid'=>$oVal['goodid'],'id'=>$oVal['goodskuid']))->setInc('stockamount',$oVal['goodnum']);
									if(!$mallgoodsskustockamount){
										$log .= '任务失败  SQL：'.M()->getLastSql()."\n";
									}
								}
								$mallgoodsstockamount = $mallGoodsModel->where(array('id'=>$oVal['goodid']))->setInc('stockamount',$oVal['goodnum']);
								if(!$mallgoodsstockamount){
									$log .= '任务失败  SQL：'.M()->getLastSql()."\n";
								}
							}
						}
						// 添加日志记录
						if($log){
							$logData['id'] = guidNow();
							$logData['companyid'] = $clVal['id'];
							$logData['log'] = $log;
							$logData['createtime'] = $time;
							M('log_physical_order_goods')->add($logData);
						}
					}
				}
			}
		}
	}
	/**
	 * 定时任务
	 * 后台设置时间自动关闭订单（ 此任务包含实物商品） 每一分钟跑一次
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-11-1
	 */
	public function autoCancelMallOrder(){
		$time = time();
		$companyList = M('company')->where(array('_string'=>"isclose ='0' and  viptime > ".time()." and permissions like '%,7,%' and mallorderautoset > 0 "))->field('id,mallorderautoset')->select();
		if($companyList){
			$mallOrderInfoModel = M('mall_order_info');
			$mallOrderGoodsModel = M('mall_order_goods');
			$mallGoodsModel = M('mall_goods');
			$mallGoodsSkuModel = M('mall_goods_sku');
			foreach ($companyList as $clKey=>$clVal){
				$mallOrderInfoList = $mallOrderInfoModel->where(array('ordertype'=>'1','orderstatus'=>'1','ordernopayendtime'=>array('lt',$time)))->field('orderid')->select();
				if($mallOrderInfoList){
					foreach ($mallOrderInfoList as $mKey=>$mVal){
						// 修改订单状态
						$orderResult = $mallOrderInfoModel->where(array('orderid'=>$mVal['orderid']))->save(array('orderstatus'=>'5','updatetime'=>time()));
						if(!$orderResult){
							$log .= '任务失败  SQL：'.M()->getLastSql()."\n";
						}
						$orderGoods = $mallOrderGoodsModel->where(array('orderid'=>$mVal['orderid']))->field('id,goodid,goodtype,goodskuid,goodnum')->select();
						if($orderGoods){
							//库存回滚
							foreach($orderGoods as $oKey=>$oVal){
								$mallgoodsskustockamount = $mallGoodsSkuModel->where(array('goodid'=>$oVal['goodid'],'id'=>$oVal['goodskuid']))->setInc('stockamount',$oVal['goodnum']);
								if(!$mallgoodsskustockamount){
									$log .= '任务失败  SQL：'.M()->getLastSql()."\n";
								}
								$mallgoodsstockamount = $mallGoodsModel->where(array('id'=>$oVal['goodid']))->setInc('stockamount',$oVal['goodnum']);
								if(!$mallgoodsstockamount){
									$log .= '任务失败  SQL：'.M()->getLastSql()."\n";
								}
							}
						}
						// 添加日志记录
						if($log){
							$logData['id'] = guidNow();
							$logData['companyid'] = $clVal['id'];
							$logData['log'] = $log;
							$logData['createtime'] = $time;
							M('log_physical_order_goods')->add($logData);
						}
					} 
				}
			}
		}
	}
	/**
	 * 定时任务
	 * 每一分钟跑一次，将所有订单类型为 3、 4、 5 、6、7（计次卡，团购，门票，权益卡，卡券礼包）的商品的订单创建时间 加30分钟，作为条件 小于当前时间 并且订单状态为待付款的订单 设置为关闭订单
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-11-1
	 */
	public function autoShutDownMallOrder(){
		$time = time();
		//所有订单状态为1（待付款）并且订单类型为（计次卡、团购、门票）的订单
		$orderInfo = M('mall_order_info')->where(array('orderstatus'=>1,'ordertype'=>array('in','2,3,4,5,6,7')))->field('id,mid,companyid,orderid,orderstatus,createtime')->select();
		foreach($orderInfo as $key=>$val){
			//先处理订单状态
			$orderEndTime  =  $val['createtime'] + 1800;
			if($time >= $orderEndTime){
				$orderResult = M('mall_order_info')->where(array('orderid'=>$val['orderid']))->save(array('orderstatus'=>'5','updatetime'=>time()));
				if(!$orderResult){
					$log .= '任务失败  SQL：'.M()->getLastSql()."\n";
				}
				//查询出所有订单的商品信息
				$orderGoods = M('mall_order_goods')->where(array('orderid'=>$val['orderid']))->field('id,goodid,goodnum,goodskuid,goodtype')->select();
				if($orderGoods){
					//库存回滚
					foreach($orderGoods as $oKey=>$oVal){
						if($oVal['goodtype'] != 2){
							$mallgoodsskustockamount = M('mall_goods_sku')->where(array('goodid'=>$oVal['goodid'],'id'=>$oVal['goodskuid']))->setInc('stockamount',$oVal['goodnum']);
							if(!$mallgoodsskustockamount){
								$log .= '任务失败  SQL：'.M()->getLastSql()."\n";
							}
						}
						$mallgoodsstockamount = M('mall_goods')->where(array('id'=>$oVal['goodid']))->setInc('stockamount',$oVal['goodnum']);
						if(!$mallgoodsstockamount){
							$log .= '任务失败  SQL：'.M()->getLastSql()."\n";
						}
					}
				}
			}
			// 添加日志记录
			if($log){
				$logData['id'] = guidNow();
				$logData['companyid'] = $val['companyid'];
				$logData['log'] = $log;
				$logData['createtime'] = $time;
				M('log_vouchers_order_goods')->add($logData);
			}
			unset($Key,$Val,$oKey,$oVal);
		}
	}
	/**
	 * 定时任务
	 * 根据订单中的截止时间与当前时间进行匹配 如果相等则进行发送通知
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-9-2
	 */
	public function sendOrderWillColseMessage(){ 
		$log = '任务开始时间：'.format_time(time(),'ymdhis')."\n";
		$time = time() + 3600;
		//所有订单状态为1（待付款）的订单
		$orderInfo = M('mall_order_info')->where(array('orderstatus'=>1,'issendmessage'=>2,'ordernopayendtime'=>array('lt',$time)))->field('id,mid,companyid,orderid,orderpaymethod,orderstatus,orderprice,orderint,consigneename,consigneephone,ordernopayendtime,consigneeaddress')->select();
		foreach($orderInfo as $key=>$val){
			//获取发消息的openid
			$orderOpenid = M('member_wechat_info')->where(array('companyid'=>$val['companyid'],'mid'=>$val['mid']))->getField('openid');
			//从订单商品表中随机取出一个商品作为商品详情
			$mallGoodInfo = M('mall_order_goods')->where(array('companyid'=>$val['companyid'],'orderid'=>$val['orderid']))->field('goodname')->limit(1)->find();
			//发送订单未付款通知
			$result = $this->WeChatTemplateMessageSend('6',$orderOpenid,$val['companyid'],'','',array($val['orderprice'],htmlspecialchars_decode($mallGoodInfo['goodname']),$val['consigneename'].' '.$val['consigneephone'].' '.$val['consigneeaddress'],$val['orderid']),'');
			if($result){
				$data['issendmessage'] = 1;
				$data['updatetime'] = time();
				//改变发送消息状态
				$updateResult = M('mall_order_info')->where(array('orderstatus'=>1,'issendmessage'=>2,'ordernopayendtime'=>array('lt',$time)))->save($data);
				if(!$updateResult){
					$log .= '任务失败  SQL：'.M()->getLastSql()."\n";
				}
			}else{
				$log .= '任务失败  SQL：'.M()->getLastSql()."\n";
			}
			unset($Key,$Val);
		}
		$log .= '任务结束时间：'.format_time(time(),'ymdhis')."\n---------------------------------------------------------\n";
		echo $log;
	}
}
?>