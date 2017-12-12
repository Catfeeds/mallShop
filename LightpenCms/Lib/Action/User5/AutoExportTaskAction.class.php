<?php
/**
 * SCRM5   我的导出任务
 * 
 * @author    Mark<1311013341@qq.com>
 * @since     2016-10-29
 * @version   1.0
 */
class AutoExportTaskAction extends BaseAction{
	
	public function __construct(){
		parent::__construct();
		ignore_user_abort();
		set_time_limit(0);
	}
	/**
	 * 
	 * 任务开始
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-10-29
	 */
	public function exportTask(){
		$ldata['id'] = guidNow();
		$ldata['createtime'] = time();
		$ldata['log'] = '任务开始时间：'.format_time(time(),'ymdhis');
		C('TOKEN_ON',false);
		set_time_limit(0);
		header("Content-type: text/html; charset=utf-8");
		$list = M('export_task')->where(array('state'=>1,'state2'=>1))->field('id,companyid,name,rule,type,remark,remarkname')->select();
		if($list){
			$qian=array(" ","　","\t","\n","\r");
			foreach($list as $key=>$val){
				//执行任务状态改成：进行中
				$stateData['updatetime'] = time();
				$stateData['state2'] = 2;
				M('export_task')->where(array('id'=>$val['id']))->save($stateData);
				$memberList = M()->query($val['rule']);
				if($memberList){
					if($val['type'] == '1'){  //网页二维码扫描数
						$content = "时间,被扫次数\r\n";
						foreach($memberList as $mlKey=>$mlVal){
							$content .= format_time($mlVal['day'],'ymd').',';
							$content .= $mlVal['scannum']."\r\n";
						}
					}elseif($val['type'] == '2'){  //微信关注二维码信息
						$content = "时间,获取关注数,取消关注数,有效关注数,有效注册数\r\n";
						foreach($memberList as $mlKey=>$mlVal){
							$content .= format_time($mlVal['day'],'ymd').',';
							$content .= $mlVal['subscribe'].',';
							$content .= $mlVal['unsubscribe'].',';
							$content .= $mlVal['scannum'].',';
							$content .= $mlVal['registernum']."\r\n";
						}
					}elseif($val['type'] == '3'){  //微信关注二维码粉丝信息
						$content = "微信昵称,最新关注时间,最近取关时间,关注状态,是否注册\r\n";
						foreach ($memberList as $mlKey=>$mlVal){
							$subtime = M('history_wechat_request')->where(array('companyid'=>$val['companyid'],'FromUserName'=>$mlVal['openid'],'Event'=>'subscribe'))->order('CreateTime desc')->group('CreateTime')->getField('CreateTime');
							$unsubtime = M('history_wechat_request')->where(array('companyid'=>$val['companyid'],'FromUserName'=>$mlVal['openid'],'Event'=>'unsubscribe'))->order('CreateTime desc')->group('CreateTime')->getField('CreateTime');
							$isregister = M('member_register_info')->where(array('companyid'=>$val['companyid'],'id'=>$mlVal['mid']))->getField('isregister');
							$content .= $mlVal['nickname']?$mlVal['nickname'].',':'-,';
							$content .= $subtime?format_time($subtime,'ymdhis').',':'-,';
							$content .= $unsubtime?format_time($unsubtime,'ymdhis').',':'-,';
							if($subtime>$unsubtime){
								$content .= '已关注,';
							}else{
								$content .= '取消关注,';
							}
							if($isregister == '1'){
								$content .= '是'."\r\n";
							}else{
								$content .= '否'."\r\n";
							}
						}
					}elseif($val['type'] == '4'){   //我的会员资料
						$content = "手机,姓名,性别,会员等级,累计积分,可用积分,微信关注状态,备注\r\n";
						foreach($memberList as $mlKey=>$mlVal){
							$content .= $mlVal['moblie'].',';
							$content .= $mlVal['name'].',';
							if($mlVal['gender'] == '1'){
								$content .= "先生,";
							}elseif($mlVal['gender'] == '2'){
								$content .= "女士,";
							}else{
								$content .= "-,";
							}
							$content .= $mlVal['rankname'].',';
							$content .= $mlVal['totalexperiencevalue'].',';
							$content .= $mlVal['totalintegration'].',';
							if($mlVal['subscribetype'] == '1'){
								$content .= "微信已关注,";
							}elseif($mlVal['subscribetype'] == '2'){
								$content .= "微信取关,";
							}else{
								$content .= "微信未关注,";
							}
							$content .= strip_tags(htmlspecialchars_decode(htmlspecialchars_decode($mlVal['note'])))."\r\n";
						}
					}elseif($val['type'] == '5'){//收入
						$content = "时间,订单号,收单口,金额,会员姓名,会员手机号,门店\r\n";
						foreach($memberList as $mlKey=>$mlVal){
							$content .= $mlVal['createtime']?format_time($mlVal['createtime'],'ymdhis').',':'-,';
							$content .= $mlVal['borderid'].',';
							if($mlVal['type'] == '110'){$content .= 'eshop,';}
							elseif($mlVal['type'] == '106'){$content .= '闪惠,';}
							elseif($mlVal['type'] == '114'){$content .= '风外卖,';}
							elseif($mlVal['type'] == '113'){$content .= '手机预订,';}
							elseif($mlVal['type'] == '115'){$content .= '手机点单,';}
							elseif($mlVal['type'] == '107'){$content .= '风助手,';}
							elseif($mlVal['type'] == '111'){$content .= '拉卡拉,';}
							elseif($mlVal['type'] == '301'){$content .= '后台储值消费,';}
							$content .= $mlVal['spendingamount']?$mlVal['spendingamount'].'元,':'-,';
							$content .= $mlVal['name']?$mlVal['name'].',':'-,';
							$content .= $mlVal['moblie']?$mlVal['moblie'].',':'-,';
							if($mlVal['shopid'] == '-1'){
								$content .= '总部';
							}else{
								if($mlVal['shopname']){
									$content .= $mlVal['shopname'];
								}else{
									$content .= '-';
								}
							}
							$content .= "\r\n";
						}
					}elseif($val['type'] == '6'){//充值
						$content = "时间,订单号,交易号,充值方式,金额,会员姓名,会员手机号,操作人\r\n";
						foreach($memberList as $mlKey=>$mlVal){
							$content .= $mlVal['createtime']?format_time($mlVal['createtime'],'ymdhis').',':'-,';
							$content .= $mlVal['borderid'].',';
							$content .= $mlVal['orderid'].',';
							if($mlVal['rechargetype'] == '1'){$content .= '后台充值,';}
							elseif($mlVal['rechargetype'] == '2'){$content .= '在线充值,';}
							elseif($mlVal['rechargetype'] == '3'){$content .= '会员WAP积分换储值,';}
							elseif($mlVal['rechargetype'] == '4'){$content .= '红包,';}
							$content .= $mlVal['spendingamount2']?$mlVal['spendingamount2'].'元,':'-,';
							$content .= $mlVal['name']?$mlVal['name'].',':'-,';
							$content .= $mlVal['moblie']?$mlVal['moblie'].',':'-,';
							if($mlVal['username']){
								$content .= $mlVal['username'];
							}else{
								$content .= '系统';
							}
							$content .= "\r\n";
						}
					}elseif($val['type'] == '7'){//积分
						$content = "时间,订单号,交易号,交易场景,积分变动,会员姓名,会员手机号,操作人\r\n";
						foreach($memberList as $mlKey=>$mlVal){
							$content .= $mlVal['createtime']?format_time($mlVal['createtime'],'ymdhis').',':'-,';
							$content .= $mlVal['borderid'].',';
							$content .= $mlVal['orderid'].',';
							if($mlVal['type'] == "204"){$content .= '积分商城,';}
							elseif($mlVal['type'] == "203"){$content .= '会员WAP积分换储值,';}
							elseif($mlVal['type'] == "104"){$content .= '后台加积分,';}
							elseif($mlVal['type'] == "201"){$content .= '后台减积分,';}
							elseif($mlVal['type'] == "116"){$content .= '风助手加积分,';}
							elseif($mlVal['type'] == "205"){$content .= '风助手减积分,';}
							elseif($mlVal['type'] == "202"){$content .= '积分到期自动清零,';}
							elseif($mlVal['type'] == "108"){$content .= '微信关注,';}
							elseif($mlVal['type'] == "102"){$content .= '注册,';}
							elseif($mlVal['type'] == "101"){$content .= '完善会员资料,';}
							elseif($mlVal['type'] == "105"){$content .= '摇摇签到,';}
							elseif($mlVal['type'] == "103"){$content .= '点评奖励,';}
							elseif($mlVal['type'] == "109"){$content .= '首次消费,';}
							elseif($mlVal['type'] == "110"){$content .= 'eshop支付,';}
							elseif($mlVal['type'] == "106"){$content .= '闪惠支付,';}
							elseif($mlVal['type'] == "111"){$content .= '拉卡拉门店收银,';}
							elseif($mlVal['type'] == "107"){$content .= '风助手门店收银,';}
							elseif($mlVal['type'] == "112"){$content .= '充值,';}
							elseif($mlVal['type'] == "113"){$content .= '手机预订支付,';}
							elseif($mlVal['type'] == "114"){$content .= '风外卖支付,';}
							elseif($mlVal['type'] == "115"){$content .= '手机点单支付,';}
							if($mlVal['type'] == "112"||$mlVal['type'] == "115"||$mlVal['type'] == "114"||$mlVal['type'] == "113"||$mlVal['type'] == "107"||$mlVal['type'] == "111"||$mlVal['type'] == "106"||$mlVal['type'] == "104"||$mlVal['type'] == "108"||$mlVal['type'] == "102"||$mlVal['type'] == "101"||$mlVal['type'] == "105"||$mlVal['type'] == "103"||$mlVal['type'] == "109"||$mlVal['type'] == "110"){
								$content .= '+';
								if($mlVal['integralnum']){
									$content .= $mlVal['integralnum'].',';
								}else{
									$content .= '0,';
								}
							}elseif($mlVal['type'] == "204"||$mlVal['type'] == "203"||$mlVal['type'] == "201"||$mlVal['type'] == "202"){
								$content .= '-';
								if($mlVal['integralnum']){
									$content .= $mlVal['integralnum'].',';
								}else{
									$content .= '0,';
								}
							}
							$content .= $mlVal['name']?$mlVal['name'].',':'-,';
							$content .= $mlVal['moblie']?$mlVal['moblie'].',':'-,';
							if($mlVal['username']){
								$content .= $mlVal['username'];
							}else{
								$content .= '系统';
							}
							$content .= "\r\n";
						}
					}elseif($val['type'] == '8'){//调研活动参与会员
						$contentsmember = '';
						foreach ($memberList as $mlKey=>$mlVal){
							$surveyQuestionList = M()->table('tp_survey_activity_question as saq')->join('tp_survey_activity_member as sam on sam.qid = saq.id')->where(array('saq.companyid'=>$val['companyid'],'saq.tid'=>$val['remark'],'sam.openid'=>$mlVal['openid']))->field('saq.name,sam.answer,saq.sort,saq.type')->order('saq.sort asc')->select();
							if($surveyQuestionList){
								$note = $contents = $contentss = '';
								$contents .= $mlVal['nickname'].',';
								$contents .= $mlVal['mobile'].',';
								$contents .= $mlVal['createtime']?format_time($mlVal['createtime'],'ymdhis').',':'-,';
								foreach($surveyQuestionList as $skey=>$sval){
									$note .= ",问题".($sval['sort']).":".str_replace(',', '，',$sval['name']);
									$answer = json_decode($sval['answer'],true);
									if($sval['type'] == '5'){
										$contentss .= ",";
									}else{
										foreach ($answer as $akey=>$aval){
											if($aval['Option']){
												$contentss .= '选项'.str_replace(',', '，',$aval['Option']).'：';
											}
											$contentss .= str_replace(',', '，',str_replace($qian,'',$aval['Answer'])).'    ';
										}
									}
									$contentss = $contentss.',';
								}
								$contentsmember .= $contents.substr($contentss, 0,-1)."\r\n";
							}
						}
						$content = "会员昵称,会员手机号,提交时间".$note."\r\n";
						$content .= $contentsmember;
					}elseif($val['type'] == '9'){//微信支付和支付宝支付累计收入
						$asaType = $this->totaltype();
						$content = "时间,交易号,商户订单号,收单口,金额,会员昵称,会员手机号,门店\r\n";
						foreach($memberList as $mlKey=>$mlVal){
							$content .= $mlVal['createtime']?format_time($mlVal['createtime'],'ymdhis').',':'-,';
							$content .= $mlVal['orderid'].',';
							$content .= $mlVal['linkorderid'].',';
							$content .= $asaType[$mlVal['type']].',';
							$content .= $mlVal['spendingamount']?$mlVal['spendingamount'].',':'-,';
							$content .= $mlVal['name']?$mlVal['name'].',':'-,';
							$content .= $mlVal['moblie']?$mlVal['moblie'].',':'-,';
							$content .= $mlVal['shopname']?$mlVal['shopname'].',':'-,';
							$content .= "\r\n";
						}
					}elseif($val['type'] == '10'){//储值，现金，银行卡支付累计收入
						$asaType = $this->totaltype();
						$content = "时间,交易号,收单口,金额,会员昵称,会员手机号,门店\r\n";
						foreach($memberList as $mlKey=>$mlVal){
							$content .= $mlVal['createtime']?format_time($mlVal['createtime'],'ymdhis').',':'-,';
							$content .= $mlVal['orderid'].',';
							$content .= $asaType[$mlVal['type']].',';
							$content .= $mlVal['spendingamount']?$mlVal['spendingamount'].',':'-,';
							$content .= $mlVal['name']?$mlVal['name'].',':'-,';
							$content .= $mlVal['moblie']?$mlVal['moblie'].',':'-,';
							$content .= $mlVal['shopname']?$mlVal['shopname'].',':'-,';
							$content .= "\r\n";
						}
					}elseif($val['type'] == '11'){//门店支付累计收入
						$asaType = $this->totaltype();
						$content = "时间,订单号,收单口,金额,会员昵称,会员手机号\r\n";
						foreach($memberList as $mlKey=>$mlVal){
							$content .= $mlVal['createtime']?format_time($mlVal['createtime'],'ymdhis').',':'-,';
							$content .= $mlVal['linkorderid'].',';
							$content .= $asaType[$mlVal['type']].',';
							$content .= $mlVal['spendingamount']?$mlVal['spendingamount'].',':'-,';
							$content .= $mlVal['name']?$mlVal['name'].',':'-,';
							$content .= $mlVal['moblie']?$mlVal['moblie'].',':'-,';
							$content .= "\r\n";
						}
					}elseif($val['type'] == '12'){//eshop,在线充值累计收入
						$content = "时间,订单号,金额,会员昵称,会员手机号\r\n";
						foreach($memberList as $mlKey=>$mlVal){
							$content .= $mlVal['createtime']?format_time($mlVal['createtime'],'ymdhis').',':'-,';
							$content .= $mlVal['linkorderid'].',';
							$content .= $mlVal['spendingamount']?$mlVal['spendingamount'].',':'-,';
							$content .= $mlVal['name']?$mlVal['name'].',':'-,';
							$content .= $mlVal['moblie']?$mlVal['moblie'].',':'-,';
							$content .= "\r\n";
						}
					}elseif($val['type'] == '13'){//闪惠，手机预订，手机点单，风外卖累计收入
						$content = "时间,订单号,金额,会员昵称,会员手机号,门店\r\n";
						foreach($memberList as $mlKey=>$mlVal){
							$content .= $mlVal['createtime']?format_time($mlVal['createtime'],'ymdhis').',':'-,';
							$content .= $mlVal['linkorderid'].',';
							$content .= $mlVal['spendingamount']?$mlVal['spendingamount'].',':'-,';
							$content .= $mlVal['name']?$mlVal['name'].',':'-,';
							$content .= $mlVal['moblie']?$mlVal['moblie'].',':'-,';
							$content .= $mlVal['shopname']?$mlVal['shopname'].',':'-,';
							$content .= "\r\n";
						}
					}elseif($val['type'] == '14'){//风助手，拉卡拉累计收入
						$content = "时间,订单号,金额,会员昵称,会员手机号,操作人,门店\r\n";
						foreach($memberList as $mlKey=>$mlVal){
							$content .= $mlVal['createtime']?format_time($mlVal['createtime'],'ymdhis').',':'-,';
							$content .= $mlVal['linkorderid'].',';
							$content .= $mlVal['spendingamount']?$mlVal['spendingamount'].',':'-,';
							$content .= $mlVal['name']?$mlVal['name'].',':'-,';
							$content .= $mlVal['moblie']?$mlVal['moblie'].',':'-,';
							$content .= $mlVal['username']?$mlVal['username'].',':'-,';
							$content .= $mlVal['shopname']?$mlVal['shopname'].',':'-,';
							$content .= "\r\n";
						}
					}elseif($val['type'] == '15'){//sms失败推送日志
						$content = "手机号\r\n";
						foreach($memberList as $mlKey=>$mlVal){
							$content .= '"'.$mlVal['mobile'].'",';
							$content .= "\r\n";
						}
					}elseif($val['type'] == '16'){//快递配送订单
						$content = "订单编号,兑换礼品,消耗积分,兑换时间,订单状态,会员姓名,会员手机号,收件信息\r\n";
						foreach($memberList as $key=>$val2){
							if($val2['orderstatus'] == 1){
								$orderstatus = '待发货';
							}elseif($val2['orderstatus'] == 2){
								$orderstatus = '配送中';
							}elseif($val2['orderstatus'] == 3){
								$orderstatus = '已签收';
							}elseif($val2['orderstatus'] == 4){
								$orderstatus = '关闭订单';
							}elseif($val2['orderstatus'] == 5){
								$orderstatus = '超时未领取';
							}
							$member = $val2['name'].",".$val2['moblie'].",";
							$consignee = $val2['consigneename']." ".$val2['consigneephone']." ".$val2['consigneeaddress'];
							$content .= $val2['orderid'].",".$val2['ordertitle'].",".$val2['orderint'].",".format_time($val2['createtime'],'ymdhi').",".$orderstatus.",".$member.",".$consignee."\r\n";
							unset($val2,$member,$consignee);
						}
					}elseif($val['type'] == '17'){//到店领取订单
						$content = "订单编号,兑换礼品,消耗积分,兑换时间,订单状态,会员姓名,会员手机号\r\n";
						foreach($memberList as $key=>$val2){
							if($val2['orderstatus'] == 1){
								$orderstatus = '待领取';
							}elseif($val2['orderstatus'] == 2){
								$orderstatus = '配送中';
							}elseif($val2['orderstatus'] == 3){
								$orderstatus = '已领取';
							}elseif($val2['orderstatus'] == 4){
								$orderstatus = '关闭订单';
							}elseif($val2['orderstatus'] == 5){
								$orderstatus = '超时未领取';
							}
							$member = $val2['name'].",".$val2['moblie'].",";
							$consignee = $val2['consigneename']." ".$val2['consigneephone']." ".$val2['consigneeaddress'];
							$content .= $val2['orderid'].",".$val2['ordertitle'].",".$val2['orderint'].",".format_time($val2['createtime'],'ymdhi').",".$orderstatus.",".$member.",".$consignee."\r\n";
							unset($val2,$member,$consignee);
						}
					}elseif($val['type'] == '18'){//虚拟礼品订单
						$content = "订单编号,兑换礼品,虚拟券号,消耗积分,兑换时间,是否已核销,订单状态,会员姓名,会员手机号\r\n";
						foreach($memberList as $key=>$val2){
							if($val2['isused'] == 1){
								$isused = '是';
							}elseif($val2['isused'] == 2){
								$isused = '否';
							}
							$member = $val2['name'].",".$val2['moblie'].",";
							$content .= $val2['orderid'].",".$val2['goodname'].",".$val2['sn'].",".$val2['orderint'].",".format_time($val2['createtime'],'ymdhi').",".$isused.",卡券已发送,".$member."\r\n";
							unset($val2,$isused,$member);
						}
					}elseif($val['type'] == '19'){ //实物订单
						$headArr = array('订单号','商户订单号','订单状态','下单时间','订单金额','实付金额','商品名称','售价','数量','提货券号','收件人姓名','收件人地址','收件人电话','买家留言','会员姓名','会员手机号');
						$content = implode(",",$headArr);
						$content .="\r\n";
						foreach($memberList as $key=>$val2){
							$out_trade_no = $val2['out_trade_no'] ? $val2['out_trade_no'] : ''; // 商户订单号
							if($val2['orderstatus'] == 1) $orderstatus = '待付款';
							elseif($val2['orderstatus'] == 2) $orderstatus = '待发货';
							elseif($val2['orderstatus'] == 3) $orderstatus = '配送中';
							elseif($val2['orderstatus'] == 4) $orderstatus = '已签收';
							elseif($val2['orderstatus'] == 5) $orderstatus = '已取消';
							elseif($val2['orderstatus'] == 6) $orderstatus = '卡券已发送';
							elseif($val2['orderstatus'] == 7) $orderstatus = '确认到账中';
							elseif($val2['orderstatus'] == 8) $orderstatus = '退货退款';
							elseif($val2['orderstatus']==9 || $val2['orderstatus']==10) $orderstatus = '已退单';
							// 商品名称
							if($val2['ordertype']==1 && $val2['truegoodtype']==2){
								$mallGoods = M('member_delivery_voucher')->where(array('companyid'=>$val2['companyid'],'orderid'=>$val2['orderid']))->field('name,sn')->select();
							}else{
								$mallGoods = M('mall_order_goods')->where(array('companyid'=>$val2['companyid'],'orderid'=>$val2['orderid']))->field('id,goodname,goodprice,goodnum')->select();
							}
							foreach($mallGoods as $gKey=>$gVal){
								if($val2['ordertype']==1 && $val2['truegoodtype']==2){
									$orderList[$key]['goodname'] .= str_replace('&nbsp;','',$gVal['name']).';';
									$orderList[$key]['goodprice'] .= '0.00;';
									$orderList[$key]['goodnum'] .= '1;';
									$orderList[$key]['sn'] .= $gVal['sn'].';';
								}else{
									$orderList[$key]['goodname'] .= str_replace('&nbsp;','',$gVal['goodname']).';';
									$orderList[$key]['goodprice'] .= $gVal['goodprice'].';';
									$orderList[$key]['goodnum'] .= $gVal['goodnum'].';';
								}
							}
							$data[$key] = array($val2['orderid'],$out_trade_no,$orderstatus,format_time($val2['createtime'],'ymdhi'),$val2['ordersubtotal'],$val2['orderprice'],substr($orderList[$key]['goodname'], 0, -1),substr($orderList[$key]['goodprice'], 0, -1),substr($orderList[$key]['goodnum'], 0, -1),substr($orderList[$key]['sn'], 0, -1),$val2['consigneename'],$val2['consigneeaddress'],$val2['consigneephone'],$val2['membernote'],$val2['name'],$val2['moblie']);
							$content .=implode(",",$data[$key])."\r\n";
							unset($val2,$out_trade_no,$orderstatus,$orderList);
						}
					}elseif($val['type'] == '20'){ //计次卡商品订单
						$content = "订单号,商户订单号,订单状态,付款时间,订单金额,实付金额,商品名称,售价,数量,卡券号,可使用次数/已使用次数,会员姓名,会员手机号\r\n";
						foreach($memberList as $key=>$val2){
							$out_trade_no = $val2['out_trade_no'] ? $val2['out_trade_no'] : ''; // 商户订单号
							if($val2['orderstatus'] == 1) $orderstatus = '待付款';
							elseif($val2['orderstatus'] == 2) $orderstatus = '待发货';
							elseif($val2['orderstatus'] == 3) $orderstatus = '配送中';
							elseif($val2['orderstatus'] == 4) $orderstatus = '已签收';
							elseif($val2['orderstatus'] == 5) $orderstatus = '已取消';
							elseif($val2['orderstatus'] == 6) $orderstatus = '卡券已发送';
							elseif($val2['orderstatus'] == 7) $orderstatus = '确认到账中';
							elseif($val2['orderstatus'] == 8) $orderstatus = '退货退款';
							elseif($val2['orderstatus']==9 || $val2['orderstatus']==10) $orderstatus = '已退单';
							// 商品名称
							if($val2['ordertype']==1 && $val2['truegoodtype']==2){
								$mallGoods = M('member_delivery_voucher')->where(array('companyid'=>$val2['companyid'],'orderid'=>$val2['orderid']))->field('name,sn')->select();
							}else{
								$mallGoods = M('mall_order_goods')->where(array('companyid'=>$val2['companyid'],'orderid'=>$val2['orderid']))->field('id,goodname,goodprice,goodnum')->select();
							}
							foreach($mallGoods as $gKey=>$gVal){
								if($val2['ordertype']==1 && $val2['truegoodtype']==2){
									$orderList[$key]['goodname'] .= str_replace('&nbsp;','',$gVal['name']).';';
									$orderList[$key]['goodprice'] .= '0.00;';
									$orderList[$key]['goodnum'] .= '1;';
									$orderList[$key]['sn'] .= $gVal['sn'].';';
								}else{
									$orderList[$key]['goodname'] .= str_replace('&nbsp;','',$gVal['goodname']).';';
									$orderList[$key]['goodprice'] .= $gVal['goodprice'].';';
									$orderList[$key]['goodnum'] .= $gVal['goodnum'].';';
								}
								$vouchers = M('member_vouchers')->where(array('companyid'=>$val2['companyid'],'mallordergoodsid'=>$gVal['id']))->field('sn,useendtime,isused,usetime,usenumberlimit,usednumber')->select();
								if($vouchers){
									foreach($vouchers as $vKey=>$vVal){
										$orderList[$key]['sn'] .= $vVal['sn'].';';
										$orderList[$key]['usednumber'] .= $vVal['usenumberlimit'].'/'.$vVal['usednumber'].';';
										/* if($vVal['isused'] == 1) $orderList[$key]['isused'] .= '是;';
										else if($vVal['isused'] == 2) $orderList[$key]['isused'] .= '否;';
										else if($vVal['useendtime'] && $vVal['useendtime']<time()) $orderList[$key]['isused'] .= '已过期;';
										else $orderList[$key]['isused'] .= ''; */
										$orderList[$key]['usetime'] .= format_time($vVal['usetime'],'ymdhi').';';
										$orderList[$key]['handlerusername'] .= $vVal['staffname'].';';
										$orderList[$key]['handlershopname'] .= $vVal['shopname'].';';
									}
								}
							}
							$data[$key] = array($val2['orderid'],$out_trade_no,$orderstatus,format_time($val2['paytime'],'ymdhi'),$val2['ordersubtotal'],$val2['orderprice'],substr($orderList[$key]['goodname'], 0, -1),substr($orderList[$key]['goodprice'], 0, -1),substr($orderList[$key]['goodnum'], 0, -1),substr($orderList[$key]['sn'], 0, -1),substr($orderList[$key]['usednumber'], 0, -1),substr($orderList[$key]['isused'], 0, -1),$val2['name'],$val2['moblie']);
							$content .=implode(",",$data[$key]);
							$content .="\r\n";
							unset($val2,$out_trade_no,$orderstatus,$orderList);
						}
					}elseif($val['type'] == '21'){ ////券商品订单，团购商品，门票商品订单
						$headArr = array('订单号','商户订单号','订单状态','下单时间','订单金额','实付金额','商品名称','售价','数量','卡券号','核销状态','核销人','核销门店','核销时间','会员姓名','会员手机号');
						$content = implode(",",$headArr);
						$content .="\r\n";
						foreach($memberList as $key=>$val2){
							$out_trade_no = $val2['out_trade_no'] ? $val2['out_trade_no'] : ''; // 商户订单号
							if($val2['orderstatus'] == 1) $orderstatus = '待付款';
							elseif($val2['orderstatus'] == 2) $orderstatus = '待发货';
							elseif($val2['orderstatus'] == 3) $orderstatus = '配送中';
							elseif($val2['orderstatus'] == 4) $orderstatus = '已签收';
							elseif($val2['orderstatus'] == 5) $orderstatus = '已取消';
							elseif($val2['orderstatus'] == 6) $orderstatus = '卡券已发送';
							elseif($val2['orderstatus'] == 7) $orderstatus = '确认到账中';
							elseif($val2['orderstatus'] == 8) $orderstatus = '退货退款';
							elseif($val2['orderstatus']==9 || $val2['orderstatus']==10) $orderstatus = '已退单';
							// 商品名称
							if($val2['ordertype']==1 && $val2['truegoodtype']==2){
								$mallGoods = M('member_delivery_voucher')->where(array('companyid'=>$val2['companyid'],'orderid'=>$val2['orderid']))->field('name,sn')->select();
							}else{
								$mallGoods = M('mall_order_goods')->where(array('companyid'=>$val2['companyid'],'orderid'=>$val2['orderid']))->field('id,goodname,goodprice,goodnum')->select();
							}
							foreach($mallGoods as $gKey=>$gVal){
								if($val2['ordertype']==1 && $val2['truegoodtype']==2){
									$orderList[$key]['goodname'] .= str_replace('&nbsp;','',$gVal['name']).';';
									$orderList[$key]['goodprice'] .= '0.00;';
									$orderList[$key]['goodnum'] .= '1;';
									$orderList[$key]['sn'] .= $gVal['sn'].';';
								}else{
									$orderList[$key]['goodname'] .= str_replace('&nbsp;','',$gVal['goodname']).';';
									$orderList[$key]['goodprice'] .= $gVal['goodprice'].';';
									$orderList[$key]['goodnum'] .= $gVal['goodnum'].';';
								}
								$vouchers = M()->table('tp_member_vouchers as voucher')->join(array('LEFT JOIN tp_use_vouchers AS uVoucher ON voucher.id=uVoucher.voucherid','LEFT JOIN tp_company_shops AS shops ON uVoucher.shopid=shops.id'))->where(array('voucher.companyid'=>$val2['companyid'],'voucher.orderid'=>$val2['orderid']))->field('voucher.sn,voucher.useendtime,voucher.isused,uVoucher.usetime,uVoucher.staffname,shops.shopname')->select();
								if($vouchers){
									foreach($vouchers as $vKey=>$vVal){
										$orderList[$key]['sn'] .= $vVal['sn'].';';
										$orderList[$key]['usednumber'] .= $vVal['usenumberlimit'].'/'.$vVal['usednumber'].';';
										if($vVal['isused'] == 1) $orderList[$key]['isused'] .= '是;';
										else if($vVal['isused'] == 2) $orderList[$key]['isused'] .= '否;';
										else if($vVal['useendtime'] && $vVal['useendtime']<time()) $orderList[$key]['isused'] .= '已过期;';
										else $orderList[$key]['isused'] .= '';
										$orderList[$key]['usetime'] .= format_time($vVal['usetime'],'ymdhi').';';
										$orderList[$key]['handlerusername'] .= $vVal['staffname'].';';
										$orderList[$key]['handlershopname'] .= $vVal['shopname'].';';
									}
								}
							}
							$data[$key] = array($val2['orderid'],$out_trade_no,$orderstatus,format_time($val2['paytime'],'ymdhi'),$val2['ordersubtotal'],$val2['orderprice'],substr($orderList[$key]['goodname'], 0, -1),substr($orderList[$key]['goodprice'], 0, -1),substr($orderList[$key]['goodnum'], 0, -1),substr($orderList[$key]['sn'], 0, -1),substr($orderList[$key]['isused'], 0, -1),substr($orderList[$key]['handlerusername'], 0, -1),substr($orderList[$key]['handlershopname'], 0, -1),substr($orderList[$key]['usetime'], 0, -1),$val2['name'],$val2['moblie']);
							$content .=implode(",",$data[$key])."\r\n";
							unset($val2,$out_trade_no,$orderstatus,$orderList);
						}
					}elseif($val['type'] == '22'){ //门店收银历史
						$content = "订单号,消费金额,实收金额,会员手机号,收银时间,处理人,处理人所属门店,收银渠道\r\n";
						foreach($memberList as $key2=>$val2){
							$content .= '"'.$val2['orderid'].'","￥'.$val2['receivables'].'","￥'.$val2['actualamount'].'","'.$val2['mobile'].'","'.format_time($val2['collectiontime'],'ymdhis').'","'.$val2['adminname'].'",'.$val2['shopname'].'",';
							if($val2['type'] == '1'){
								$content .= '风助手微信版';
							}elseif($val2['type'] == '2'){
								$content .= '风助手POS+';
							}else{
								$content .= '-';
							}
							$content .= "\r\n";
						}
					}elseif($val['type'] == '23'){ //卡券核销历史
						$content = "卡券号,卡券类型,卡券名称,效用,会员手机号,核销时间,处理人,核销渠道,处理人所属门店\r\n";
						foreach($memberList as $key2=>$val2){
							$content .= '"'.$val2['vouchernumber'].'",';
							if($val2['vouchertype'] == '2') $content .= "线下优惠券,";
							elseif($val2['vouchertype'] == '3') $content .= "计次卡券,";
							elseif($val2['vouchertype'] == '4') $content .= "团购券,";
							elseif($val2['vouchertype'] == '5') $content .= "门票,";
							elseif($val2['vouchertype'] == '7') $content .= "eshop优惠券,";
							elseif($val2['vouchertype'] == '8') $content .= "门店使用优惠券,";
							elseif($val2['vouchertype'] == '9') $content .= "兑换券,";
							elseif($val2['vouchertype'] == '10') $content .= "红包,";
							elseif($val2['vouchertype'] == '40') $content .= "微信互通券,";
							else $content .= "-,";
							$content .= '"'.$val2['vouchername'].'","'.$val2['utility'].'","'.$val2['mobile'].'","'.format_time($val2['usetime'],'ymdhis').'","'.$val2['staffname'].'",';
							if($val2['type'] == '1') $content .= "风助手微信,";
							elseif($val2['type'] == '2') $content .= "风助手POS+,";
							else $content .= "-,";
							$content .= '"'.$val2['shopname'].'"';
							$content .= "\r\n";
						}
					}elseif($val['type'] == '24'){ //闪惠订单收银历史
						$headArr=array('订单号','商户订单号','消费金额','实付金额','闪惠活动优惠金额','DMS优惠金额','付款人姓名','付款人手机','付款时间','管理员备注');
						$content = implode(",",$headArr);
						$content .="\r\n";
						foreach($memberList as $key2=>$val2){
			                $name = $val2['name'];
			                $moblie = $val2['moblie'];
			                if(!$name && !$moblie){
			                    $name = '未注册会员';
			                    $moblie = '未注册会员';
			                }
			                $data[$key] = array($val2['orderid'],$val2['out_trade_no'],$val2['receivables']?$val2['receivables']:'0.00',$val2['actualamount']?$val2['actualamount']:'0.00',$val2['shanhuidiscount']?$val2['shanhuidiscount']:'0.00',$val2['dmsdiscount']?$val2['dmsdiscount']:'0.00',$name,$moblie,format_time($val2['paydonetime'],'ymdhis'),$val2['note']);
			                $content .=implode(",",$data[$key])."\r\n";
			                unset($name,$moblie);
			            }
					}elseif($val['type'] == '25'){ //手机预订订单收银历史
						$content = "订单号,预订人姓名,预订人手机,预订日期&时段,项目名称,订金,预订状态,会员姓名,会员手机号\r\n";
						foreach($memberList as $key2=>$val2){
			            	$content .= '"'.$val2['orderid'].'","'.$val2['bookname'].'","'.$val2['bookmobile'].'","'.format_time($val2['bookupdatetime'],'ymdhi').'","'.$val2['bookprejectname'].'","￥'.$val2['booktotal'].'",';
			            	if($val2['bookstatus'] == '1'){
			            		$content .= '待确认';
			            	}elseif($val2['bookstatus'] == '2'){
			            		$content .= '预订成功';
			            	}elseif($val2['bookstatus'] == '3'){
			            		$content .= '已满';
			            	}elseif($val2['bookstatus'] == '4'){
			            		$content .= '已取消';
			            	}elseif($val2['bookstatus'] == '5'){
			            		$content .= '已到店';
			            	}elseif($val2['bookstatus'] == '6'){
			            		$content .= '未到店';
			            	}elseif($val2['bookstatus'] == '7'){
			            		$content .= '待付款';
			            	}else{
			            		$content .= '';
			            	}
			            	$content .= ',';
			            	$content .= $val2['name'].",";
			            	$content .= $val2['moblie'].",";
			            	if(!$name && !$moblie){
			            		$content .= '未注册会员,';
			            		$content .= '未注册会员,';
			            	}
			            	$content .= "\r\n";
			            }
					}elseif($val['type'] == '26'){ //闪惠订单-eshop收银订单
						$content = "订单号,商品名称,售价,数量,会员姓名,会员手机号,下单时间,订单状态,订单金额\r\n";
						foreach($memberList as $key2=>$val2){
							$val2['mall'] = M()->table('tp_mall_order_goods as goods')->join(array('tp_mall_goods_sku AS sku ON sku.id = goods.goodskuid'))->where(array('goods.companyid'=>$val2['companyid'],'goods.orderid'=>$val2['orderid']))->field('goods.goodtype,goods.goodid,goods.goodname as chinagoodname,goods.goodpic,goods.goodprice,goods.goodnum,sku.name as skuname')->select();
							$where26['register.companyid'] = $val2['companyid'];
							$where26['register.id'] = $val2['mid'];
							$val2['member'] = M()->table('tp_member_register_info AS register')->join(array('LEFT JOIN tp_member_wechat_info AS wechat ON register.id = wechat.mid'))->where($where)->field('name,moblie,nickname')->find();
							$content .= '"'.$val2['orderid'].'",';
			            	foreach($val2['mall'] as $mKey=>$mVal){
			            		$content .= ''.$mVal['chinagoodname'].';';
			            	}
			            	$content .= ',';
			            	foreach($val2['mall'] as $mKey=>$mVal){
			            		$content .= '"'.$mVal['goodprice'].'";';
			            	}
			            	$content .= ',';
			            	foreach($val2['mall'] as $mKey=>$mVal){
			            		$content .= ''.$mVal['goodnum'].';';
			            	}
			            	$content .= ',';
			            	$membername = $val2['member']['name']?$val2['member']['name']:$val2['member']['nickname'];
			            	$content .= '"'.$membername.'",';
			            	$membermob = $val2['member']['moblie']?$val2['member']['moblie']:'-';
			            	$content .= '"'.$membermob.'",';
			            	$content .= format_time($val['createtime'],'ymdhis').',';
			            	if($val2['orderstatus'] == '4'){
			            		if($val2['goodtype'] == 1){
			            			$content .= '"已签收",';
			            		}else{
			            			$content .= '"卡券已发送",';
			            		}
			            	}elseif($val2['orderstatus'] == '5'){
			            		$content .= '"已取消",';
			            	}else{
			            		$content .= '"-",';
			            	}
			            	$money = $val2['ordermoney']?$val2['ordermoney']:'0.00';
			            	$content .= '"'.$money.'",';
			            	$content .= "\r\n";
			            	unset($money,$membermob,$membername);
			            }
					}elseif($val['type'] == '27'){ //闪惠订单-门店收银订单
					}elseif($val['type'] == '28'){ //闪惠订单-闪惠订单
						$content = "订单号,消费金额,实付金额,闪惠活动优惠金额,DMS优惠金额,会员姓名,会员手机号,付款时间\r\n";
						foreach($memberList as $key2=>$val2){
							$registInfo = M('member_register_info')->where(array('companyid'=>$val2['companyid'], 'id'=>$val2['mid']))->field('id,name,moblie')->find();
							$content .= '"'.$val2['orderid'].'",';
			                $receivables = $val2['receivables']?$val2['receivables']:'0.00';
			                $actualamount = $val2['actualamount']?$val2['actualamount']:'0.00';
			                $shanhuidiscount = $val2['shanhuidiscount']?$val2['shanhuidiscount']:'0.00';
			                $dmsdiscount = $val2['dmsdiscount']?$val2['dmsdiscount']:'0.00';
			                $content .= '"'.$receivables.'",';
			                $content .= '"'.$actualamount.'",';
			                $content .= '"'.$shanhuidiscount.'",';
			                $content .= '"'.$dmsdiscount.'",';
			                if($val2['mid']){
			                	$content .= $registInfo['name'].',';
			                	$content .= $registInfo['moblie'].',';
			                }else{ 
			                	$content .= '未注册会员,';
			                	$content .= '未注册会员,';
			                }
			                $content .= format_time($val2['paydonetime'], 'ymdhis');
							
							$content .= "\r\n";
							unset($receivables,$actualamount,$shanhuidiscount,$dmsdiscount,$registInfo);
						}
					}elseif($val['type'] == '29'){ //咨询表单
						$content1 = "提交时间,处理状态,备注";
						//,闪惠活动优惠金额,DMS优惠金额,会员姓名,会员手机号,付款时间\r\n";
						$content = '';
						$Alists = M("consult_table")->where(array("id"=>$memberList[0]['tid']))->getField("fieldinfo");
						$Alists = json_decode($Alists,true);
						$Acontent = '';
						$Field = '';
						foreach ($Alists as $Aval){
							//$content1 .=",".$Aval['fname'];
							$Field[$Aval['id']] = $Aval['fname'];
						}
						foreach($memberList as $key2=>$val2){
							$content .= format_time($val2['createtime'], 'ymdhis').",";
							if($val2['status']==1) $content .= '待处理,';
							else $content .='已处理,';
							$Blists = json_decode($val2['content'],true);
							$content .=$val2['rank'].',';
							foreach ($Blists as $Bkey => $Bval){
								foreach ($Field as $Fkey => $Fval){
									if($Bval['id'] == $Fkey){
										$Acontent[$Fkey] = $Bval['info'];
										unset($Blists[$Bkey]);
									}
								}
							}
							if($Blists){
								foreach ($Blists as $Bkey => $Bval){
									$Field[$Bval['id']] = $Bval['fname'];
									$Acontent[$Bval['id']] = $Bval['info'];
								}
							}
							foreach ($Field as $Fkey => $Fval){
								$Acontent[$Fkey]?$Acontent[$Fkey]=$Acontent[$Fkey]:$Acontent[$Fkey]='-';
								$content .='"'.$Acontent[$Fkey].'",';
							}
							$content=substr($content,0,-1);
							$content .= "\r\n";
							unset($Acontent);
						}
						foreach ($Field as $Fval){
							$content1 .=",".$Fval;
						}
						$content = $content1."\r\n".$content;
					}elseif($val['type'] == '30'){
						$content = "二维码名称,获取关注数,取消关注数,有效关注数,有效注册数\r\n";
						foreach($memberList as $key2=>$val2){
							$content .= $val2['name'].','.$val2['subscribe'].','.$val2['unsubscribe'].','.$val2['scannum'].','.$val2['registernum']."\r\n";
						}
					}elseif($val['type'] == '31'){ // 卡券投放记录
						$content = "投放时间,会员姓名,会员手机号,卡券类型,卡券名称（规格）,卡券号,投放渠道,状态,核销次数,核销时间,核销人,核销门店\r\n";
						foreach($memberList as $mKey=>$mVal){
							$content .= '"'.format_time($mVal['createtime'],'ymdhi').'","'.$mVal['name'].'","'.$mVal['moblie'].'",';
							if($mVal['vouchertype'] == '2'){
								$content .= "普通优惠券,";
							}elseif($mVal['vouchertype'] == '3'){
								$content .= "计次卡券,";
							}elseif($mVal['vouchertype'] == '4'){
								$content .= "团购券,";
							}elseif($mVal['vouchertype'] == '5'){
								$content .= "门票,";
							}elseif($mVal['vouchertype'] == '6'){
								$content .= "权益卡,";
							}elseif($mVal['vouchertype'] == '7'){
								$content .= "eshop优惠券,";
							}elseif($mVal['vouchertype'] == '8'){
								$content .= "门店使用优惠券,";
							}elseif($mVal['vouchertype'] == '9'){
								$content .= "兑换券,";
							}elseif($mVal['vouchertype'] == '10'){
								$content .= "红包,";
							}elseif($mVal['vouchertype'] == '40'){
								$content .= "通用券,";
							}else{
								$content .= "-,";
							}
							if($mVal['voucherskuname']){
								$content .= '"'.$mVal['vouchername'].'（'.$mVal['voucherskuname'].'）",';
							}else{
								$content .= '"'.$mVal['vouchername'].'",';
							}
							$content .= '"'.$mVal['sn'].'",';
							if($mVal['deliverychannel'] == '1'){
								$content .= "会员等级权益,";
							}elseif($mVal['deliverychannel'] == '2'){
								$content .= "Eshop微商城购买,";
							}elseif($mVal['deliverychannel'] == '3'){
								$content .= "积分商城兑换,";
							}elseif($mVal['deliverychannel'] == '4'){
								$content .= "百宝箱活动,";
							}elseif($mVal['deliverychannel'] == '5'){
								$content .= "老计次卡导入,";
							}elseif($mVal['deliverychannel'] == '6'){
								$content .= "裂变卡券活动,";
							}elseif($mVal['deliverychannel'] == '7'){
								$content .= "裂变红包活动,";
							}elseif($mVal['deliverychannel'] == '8'){
								$content .= "定时批量赠券活动,";
							}elseif($mVal['deliverychannel'] == '9'){
								$content .= "生日送券活动,";
							}elseif($mVal['deliverychannel'] == '10'){
								$content .= "首次微信关注奖励活动,";
							}elseif($mVal['deliverychannel'] == '11'){
								$content .= "完善会员100%资料立赠活动,";
							}elseif($mVal['deliverychannel'] == '12'){
								$content .= "新会员注册奖励活动,";
							}elseif($mVal['deliverychannel'] == '13'){
								$content .= "领券活动,";
							}elseif($mVal['deliverychannel'] == '14'){
								$content .= "首次消费赠券活动,";
							}elseif($mVal['deliverychannel'] == '15'){
								$content .= "邀请送礼活动,";
							}elseif($mVal['deliverychannel'] == '16'){
								$content .= "快捷赠券活动,";
							}elseif($mVal['deliverychannel'] == '17'){
								$content .= "沉睡用户唤醒活动,";
							}elseif($mVal['deliverychannel'] == '18'){
								$content .= "声悦传情,";
							}elseif($mVal['deliverychannel'] == '19'){
								$content .= "储值充值,";
							}else{
								$content .= "-,";
							}
							if($mVal['isused'] == 1){
								$content .= "已使用,";
							}else{
								if($mVal['useendtime'] < time()){
									$content .= "已过期,";
								}else{
									if($mVal['isused'] == 2){
										$content .= "未使用,";
									}elseif($mVal['isused'] == 3){
										$content .= "已冻结,";
									}
								}
							}
							if($mVal['vouchertype'] == 3){
								$content .= '"'.$mVal['usednumber'].'\\'.$mVal['usenumberlimit'].'",';
							}elseif($mVal['vouchertype'] == 6){
								$content .= '"'.$mVal['usednumber'].'",';
							}else{
								if($mVal['isused'] == 1){
									$content .= "1,";
								}elseif($mVal['isused'] == 2){
									$content .= "0,";
								}
							}
							$useVouchers = M('use_vouchers')->where(array('companyid'=>$val['companyid'],'vouchernumber'=>$mVal['sn']))->field('id,usetime,staffname,shopname')->select();
							foreach($useVouchers as $uKey=>$uVal){
								if($mVal['vouchertype'] == 3 || $mVal['vouchertype'] == 6){
									if($mVal['usednumber'] >0){
										$list[$mKey]['usetime'] .= format_time($uVal['usetime'],'ymdhi').';';
										$list[$mKey]['staffname'] .= $uVal['staffname'].';';
										$list[$mKey]['shopname'] .= $uVal['shopname'].';';
									}
								}else{
									if($mVal['isused'] == 1){
										$list[$mKey]['usetime']  = format_time($uVal['usetime'],'ymdhi');
										$list[$mKey]['staffname'] = $uVal['staffname'];
										$list[$mKey]['shopname'] = $uVal['shopname'];
									}
								}
							}
							$content .= '"'.$list[$mKey]['usetime'].'","'.$list[$mKey]['staffname'].'","'.$list[$mKey]['shopname'].'",';
							$content .= "\r\n";
						}
					}
					$content = iconv("utf-8", "GBK//IGNORE", $content); //转化编码，否则会出现乱码  gb2312GB2312 字符集只收录了常用字GBK 才是中文全集
				}
				$fileName = $val['remarkname'];
				$newFileName = $fileName .= ".csv";
				$fileName = iconv("utf-8", "GBK", $fileName); //转化编码，否则会出现乱码   gb2312GB2312 字符集只收录了常用字GBK 才是中文全集
				$path = C('ROOT_DIRECTORY')."/Uploads/exportTask/".$fileName; //保存路径
				//$path = "./Uploads/exportTask/".$fileName; //保存路径
				file_put_contents($path,$content);
				if(file_exists($path)){
					$saveData['state'] = 3;
					$saveData['state2'] = 4;
				}else{
					$saveData['state'] = 2;
					$saveData['state2'] = 3;
				}
				$saveData['downloadpath'] = C('site_url')."/Uploads/exportTask/".$newFileName;
				$saveData['updatetime'] = time();
				M('export_task')->where(array('id'=>$val['id']))->save($saveData);
			}
		}
		$ldata['log'] .= '任务结束时间：'.format_time(time(),'ymdhis');
		M('log_export_task')->add($ldata);
	}
	/**
	 * 消费类型
	 *
	 * @author Asa<asa@renlaifeng.cn>
	 * @since  2016-11-25
	 */
	public function totaltype(){
		return array(
				"106"=>"闪惠","107"=>"风助手","109"=>"首次消费","110"=>"eshop",
				"111"=>"拉卡拉","112"=>"充值","113"=>"手机预订","114"=>"风外卖",
				"115"=>"手机点单","203"=>"会员WAP积分换储值","301"=>"后台储值"
		);
	}
}
?>