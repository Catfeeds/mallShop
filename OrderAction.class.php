<?php
/**
 * 增值管理 - 硬件购买
 * 
 * @author    Tomas<416369046@qq.com>
 * @since     2016-9-27
 * @version   1.0
 */
class OrderAction extends HomeBaseAction{
	public function __construct(){
		parent::__construct();
		$cid = M('company')->where(array('id'=>session('cid')))->getField('companyid');
		$this->list = M('check_customer_info')->where(array('id'=>$cid))->field('companyname,brandname')->find();
		$this->companyid = session('cid');
	}
	/**
	 * 手机预览二维码
	 *
	 */
	public function mcode(){
		$url = base64_decode($this->_get('link'));
		$this->getQRcode($url);
	}
	/**
	 * 硬件购买
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-9-27
	 */			
	public function ajaxBuy(){
		$data['companyid'] = $this->companyid;
		$goodtype = $this->_request('goodtype');
		$ordersum = $this->_request('ordersum');
		$goodnum = $this->_request('goodnum');
		$time = time();
		$serialNumber = M('check_hardware_order')->where(array('companyid'=>$this->companyid,'createtime'=>array('between',array($time,$time+1))))->count();
		$data['orderid'] = orderID(2,'N', session('cid'), $serialNumber+1);
		if($goodtype == 1){
			$prize = 58;
			$allPrize = 58*$goodnum;
			if($allPrize == $ordersum){
				$data['ordersum'] = $ordersum;
			}else{
				$ajax['code']='300';
				$ajax['msg']='支付失败';
				echo json_encode($ajax);exit();
			}
		    //$data['ordersum'] = 0.01;
		}elseif($goodtype == 2){
			 $prize = 658;
			$allPrize = 658*$goodnum;
			if($allPrize == $ordersum){
				$data['ordersum'] = $ordersum;
			}else{
				$ajax['code']='300';
				$ajax['msg']='支付失败';
				echo json_encode($ajax);exit();
			} 
			//$data['ordersum'] = 0.01;
		}elseif($goodtype == 3){
			 $allPrize = $goodnum;
			if($allPrize == $ordersum){
				$data['ordersum'] = $ordersum;
			}else{
				$ajax['code']='300';
				$ajax['msg']='支付失败';
				echo json_encode($ajax);exit();
			} 
			//$data['ordersum'] = 0.16;
		}elseif($goodtype == 4){
			 $prize = 600;
			$allPrize = 600*$goodnum + 500;
			if($allPrize == $ordersum){
				$data['ordersum'] = $ordersum;
			}else{
				$ajax['code']='300';
				$ajax['msg']='支付失败';
				echo json_encode($ajax);exit();
			} 
			//$data['ordersum'] = 0.01;
		}elseif($goodtype == 5){
			 $prize = 1800;
			$allPrize = 1800*$goodnum;
			if($allPrize == $ordersum){
				$data['ordersum'] = $ordersum;
			}else{
				$ajax['code']='300';
				$ajax['msg']='支付失败';
				$ajax['msg2']=$allPrize;
				$ajax['msg3']=$ordersum;
				echo json_encode($ajax);exit();
			} 
			//$data['ordersum'] = 0.01;
		}elseif($goodtype == 6){
			$data['lakalatype'] = $lakalatype = $this->_post("lakalatype");
			if($lakalatype==1) $prize = 1589;
			else $prize = 1858;
			$allPrize = $prize*$goodnum;
			if($allPrize == $ordersum){
				$data['ordersum'] = $ordersum;
			}else{
				$ajax['code']='300';
				$ajax['msg']='支付失败';
				echo json_encode($ajax);exit();
			}
			//$data['ordersum'] = 0.01;
		}elseif($goodtype == 7){
			$data['wechatnum'] = $wechatnum = $this->_post("wechatnum");
			$allPrize = $goodnum*980+$wechatnum*680;
			if($allPrize == $ordersum){
				$data['ordersum'] = $ordersum;
			}else{
				$ajax['code']='300';
				$ajax['msg']='支付失败';
				echo json_encode($ajax);exit();
			}
			//$data['ordersum'] = 0.01;
		}elseif($goodtype == 8){
			if($ordersum == '9600'||$ordersum == '8800'|| $ordersum == '16500'){
				$data['ordersum'] = $ordersum;
			}else{
				$ajax['code']='300'; 
				$ajax['msg']='支付失败';
				echo json_encode($ajax);exit();
			}
			//$data['ordersum'] = 0.01;
		}
		$data['isagree'] = $this->_request('isagree');
		$data['isaccount'] = 1;
		$data['orderstate'] = 1;
		$data['goodtype'] = $goodtype;
		$data['goodnum'] = $goodnum;
		$data['paytype'] = $this->_request('paytype');
		$data['address'] = $this->_request('address');
		$data['name'] = $this->_request('name');
		$data['mobile'] = $this->_request('mobile');
		if($data['paytype'] == 1){
		    $data['borderid'] = orderID(3,'W', session('cid'), $serialNumber+1);
		    $data['out_trade_no'] = $data['borderid'].'W'.($serialNumber+1);
		}else{
		    $data['borderid'] = orderID(3,'A', session('cid'), $serialNumber+1);
		    $data['out_trade_no'] = $data['borderid'].'A'.($serialNumber+1);
		}
		$data['createtime'] = $data['updatetime'] = time();
	
		$res = M('check_hardware_order')->add($data);
		if($res){
			$ajax['code']='200';
			$ajax['msg']='支付成功';
			$ajax['orderid'] = $data['orderid'];
			$ajax['goodtype'] = $data['goodtype'];
		}else{
			$ajax['code']='300';
			$ajax['msg']='支付失败';
		}
		echo json_encode($ajax);
	}
	/**
	 *
	 * 支付宝支付(生成支付二维码)
	 *
	 * @author Leo<1251868177@qq.com>
	 * @since  2016-8-14
	 */
	public function ajaxAliPayQRcode(){
	    $returnData['code'] = 300;
	    $returnData['tips'] = '抱歉，服务器繁忙，请稍后重试';
	    $orderid = $this->_post('orderid');
	    if($orderid){
	        $where['companyid'] = $this->companyid;
	        $where['orderid'] = $orderid;
	        $info = M('check_hardware_order')->where($where)->field('id,ordersum,out_trade_no,ordertype')->find();
	        if($info){
	            if($info['ordersum'] > '0'){
	                if($info['ordertype'] == '3'){
	                    $returnData['tips'] = '本订单已经成功支付，不能再次支付';
	                }else if($info['ordertype'] == '4'){
	                    $returnData['tips'] = '本订单已支付失败，不能再次支付';
	                }else{
	                    // 生成支付二维码
	                    $biz_content['out_trade_no'] = $info['out_trade_no'];
	                    $biz_content['total_amount'] = $info['ordersum']?$info['ordersum']:'0.01';
	                    $biz_content['subject'] = '订单号：'.$orderid;
	                    $aliwhere['companyid'] = C('mobiwind_companyid');
	                    $aliwhere['isshow'] = '1';
	                    $aliInfo = M('company_pay_alipay')->where($aliwhere)->field('id,appid,merchant_private_key,alipay_public_key')->find();
	                    if($aliInfo){
	                        /* $sendData['app_id'] = $aliInfo['appid'];
	                        $sendData['biz_content'] = $biz_content;
	                        $sendData['type'] = 'orderCreate';
	                        $sendData['notify_url'] = C('site_url').'/Payapi/Alipay/OrderStoneNotifyUrl.php'; */
	                        
	                        $sendData['base']['app_id'] = $aliInfo['appid'];
	                        $sendData['base']['merchant_private_key'] = $aliInfo['merchant_private_key'];
	                        $sendData['base']['alipay_public_key'] = $aliInfo['alipay_public_key'];
	                        $sendData['base']['notify_url'] = C('site_url').'/Payapi/Alipay/OrderStoneNotifyUrl.php';
	                        $sendData['bizContent'] = $biz_content;
	                        $sendData['payType'] = 'qrPay';
	                        $sendData['type'] = 'orderCreate';
	                        
	                        $response = http_post(C('site_url').'/Payapi/Alipay/ScanCodePayment.php', json_encode($sendData));
	                        $alipayReturn = json_decode($response, true);
	                    }else{
	                        $returnData['tips'] = '支付宝配置信息错误';
	                    }
	                    if($alipayReturn['alipay_trade_precreate_response']['code'] == '10000'){
	                        $returnData['code'] = 200;
	                        $returnData['tips'] = '恭喜，支付宝二维码成功生成';
	                        $returnData['url'] = $alipayReturn['alipay_trade_precreate_response']['qr_code'];
	                    }else{
	                        $returnData['tips'] = '支付宝订单信息配置错误';
	                    }
	                }
	            }else{
	                $returnData['tips'] = '抱歉，金额错误';
	            }
	        }else{
	            $returnData['tips'] = '抱歉，订单信息错误';
	        }
	    }else{
	        $returnData['tips'] = '抱歉，订单号错误';
	    }
	    echo json_encode($returnData);
	}
	
	/**
	 * 查询订单状态
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-9-27
	 */
	public function QueryOrderState(){
		$returnData['code'] = 300;
		$returnData['tips'] = '支付中';
		$orderid = $this->_post('orderid');
		$companyid = session('cid');
		if($orderid){
			$balance = M("company")->where(array("id"=>$companyid))->getField("balance");
			$where['companyid'] = $companyid;
			$where['orderid'] = $orderid;
			$orderInfo = M('check_hardware_order')->where($where)->field('id,ordertype,ordersum,goodtype')->find();
			if($orderInfo['ordertype'] == '3'){
				$returnData['code'] = 200;
				$returnData['tips'] = '支付成功';
				if($orderInfo['goodtype'] == '3'){
					M()->startTrans();
					//$orderInfo['ordersum'] = 1000;
					$smsnum = $orderInfo['ordersum']/0.08;
					$res1 = M("company")->where(array("id"=>$companyid))->setInc("balance",$orderInfo['ordersum']);
					$res2 = M("company")->where(array("id"=>$companyid))->setInc("smsnum",$smsnum);
					if($balance<0){
						$resB = M("company")->where(array("id"=>$companyid))->setDec("smsnum",(-$balance)/0.08);
					}
					$res3 = M('check_hardware_order')->where($where)->save(array("orderstate"=>2,"issmssendnum"=>0));
					if($res1&&$res2){
						$returnData['sms'] = '成功';
						M()->commit();
					}else{
						$returnData['sms'] = '失败';
						M()->rollback();
						$data['id'] = guidNow();
						$data['companyid'] = $companyid;
						$data['log'] = "sms充值增加库存失败，充值金额：".$orderInfo['ordersum']."；增加短信条数：".$smsnum."；";
						$data['datetime'] = format_time(time());
						$data['createtime'] = time();
						M("log_sms")->add($data);
						$this->sendSms('13564012907', $data['log'].'日志id:'.$data['id'],'【人来风】','','181818');
					}
				}elseif($orderInfo['goodtype'] == '8'){
					$checkid = M("company")->where(array("id"=>$this->companyid))->getField("companyid");
					$aeuser = M("check_customer_info")->where(array("id"=>$checkid))->getField("aeuser");
					$mobile = M("sell_staffs")->where(array("name"=>$aeuser))->getField("mobile");
					//if($aeuser&&$mobile){//FG,cry,ryann,lan,stella 13918086001,18571729950,15026482623,13564012907,13818652568
						$this->sendSms('15821877807','有一家客户<'.session("cname").'>在官网下了一笔续约的订单，请尽快到check通过开通或者续约的请求','【人来风】','','181818');
					//}else{
						$this->sendSms('13564012907', '有一家商户创建了一条开通或续约信息，但是找不到对应的AE信息，请尽快核查，checkID:'.$checkid ,'【人来风】','','181818');
					//}
				}
				
			}elseif($orderInfo['ordertype'] == '4'){
				$returnData['code'] = 201;
				$returnData['tips'] = '支付失败';
			}
		}
		echo json_encode($returnData);
	}
	/**
	 * 支付二维码
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-9-27
	 */
	public function getScanQRcode(){
		$url = urldecode($this->_request('url'));
		$this->getQRcode($url);
	}
	/**
	 * sms短信充值
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-9-29
	 */
	public function ajaxSms(){
		$data['companyid'] = $this->companyid;
		$time = time();
		$serialNumber = M('check_sms_order')->where(array('companyid'=>$this->companyid,'createtime'=>array('between',array($time,$time+1))))->count();
		$data['orderid'] = orderID(2,'N', session('cid'), $serialNumber+1);
		$data['isaccount'] = 1;
		$data['orderstate'] = 1;
		$data['goodnum'] = $this->_request('goodnum');
		$data['paytype'] = $this->_request('paytype');
		$data['ordersum'] = $this->_request('ordersum');
		$data['isagress'] = $this->_request('isagress');
		$data['out_trade_no'] = orderID(3,'W', session('cid'), $serialNumber+1);
		$data['borderid'] = $data['out_trade_no'].'W'.($serialNumber+1);
		$data['createtime'] = $data['updatetime'] = time();
		$res = M('check_sms_order')->add($data);
		if($res){
			$ajax['code']='200';
			$ajax['msg']='支付成功';
			$ajax['orderid'] = $data['orderid'];
		}else{
			$ajax['code']='300';
			$ajax['msg']='支付失败';
		}
		echo json_encode($ajax);
	}
	/**
	 * 
	 * 查询sms订单状态
	 * 
	 * @author Dunn<Dunn@renlaifeng.cn>
	 * @since  2016-5-24
	 */
	public function QuerySmsState(){
		$returnData['code'] = 300;
		$returnData['tips'] = '支付中';
		$orderid = $this->_post('orderid');
		$companyid = session('cid');
		if($orderid){
			$where['companyid'] = $companyid;
			$where['orderid'] = $orderid;
			$orderInfo = M('check_sms_order')->where($where)->field('id,ordertype')->find();
			if($orderInfo['ordertype'] == '3'){
				$returnData['code'] = 200;
				$returnData['tips'] = '支付成功';
			}elseif($orderInfo['ordertype'] == '4'){
				$returnData['code'] = 201;
				$returnData['tips'] = '支付失败';
			}
		}
		echo json_encode($returnData);
	}
}