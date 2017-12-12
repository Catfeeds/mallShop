<?php
/**
 * 微信请求入口
 * Enter description here ...
 * @author yongfei.zhao
 *
 */
class WeixinAction extends BaseAction{
	
	private $token;
	
	private $openid;

	private $data;
	
	private $weixin;
	
	private $publicWhere;
	
	private $keywordType;
	
	private $companyid;
	
	private $wechatInfo;
	
	public function __construct(){
		parent::__construct();
		$this->token 		= $this->_get('token');
		$this->publicWhere = array('token'=>$this->token);
		if($this->token){
			$this->wechatInfo = M('wechats')->where($this->publicWhere)->field('appid,appsecret,encodingaeskey,companyid,wechattype,applyswitch')->find();
			$this->companyid = $this->wechatInfo['companyid'];
		}
	    //$this->keywordType = array('News','Text');
	}
	/**
	 * 路由规则默认方法 
	 * Enter description here ...
	 */
	public function index(){
			$wechatOptions = array('token'=>$this->token,'appid'=>$this->wechatInfo['appid'],'appsecret'=>$this->wechatInfo['appsecret'], 'encodingaeskey' => $this->wechatInfo['encodingaeskey']);
			$this->weixin = new Wechat($wechatOptions);
			$this->weixin->valid();
			$this->data = $this->weixin->getRev()->getRevData();
			if($this->wechatInfo['applyswitch'] =='0'){
				list($content, $type) = $this->replyContent($this->data);
				switch($type) {
					case Wechat::MSGTYPE_TEXT:
						$this->weixin->text($content)->reply();
						break;
					case Wechat::MSGTYPE_NEWS:
						$this->weixin->news($content)->reply();
						break;
					case 'transfer_customer_service':
						$this->weixin->transfer_customer_service()->reply();
						break;
				}
			}elseif($this->wechatInfo['applyswitch'] == '1'){
				$url = C('site_url').'/wallApply/api/weixin.php?companyid='.$this->companyid.'&token='.$this->token.'&signature='.$_GET['signature'].'&timestamp='.$_GET['timestamp'].'&nonce='.$_GET['nonce'];
				$xml = arrayToXml($this->data);
				$postReturn =postXmlCurl($xml, $url);
				echo $postReturn;
			}
	}
    /**
     * 获得回复
     * Enter description here ...
     * @param unknown_type $data
     */
    private function replyContent($data){
    	$this->openid = $data['FromUserName'];
    	$data['token'] = $this->token;
    	$data['CreateTime'] = time();
    	$historyInsterId = M('history_wechat_request')->add($data);
    	/* $companyInfo = M('company')->where(array('id'=>$this->companyid))->field('gid,maxrequestsnum,nowrequestsnum')->find();
    	if($companyInfo['maxrequestsnum'] > 0 && $companyInfo['nowrequestsnum'] >= $companyInfo['maxrequestsnum']){
    		exit();
    	}else{
    		M('company')->where(array('id'=>$this->companyid))->setInc('nowrequestsnum');
    	} */
        if ('LOCATION' == $data['Event']) {
            exit;
        }elseif('CLICK' == $data['Event']) {
        	//自定义菜单点击事件
            $data['Content'] = $data['EventKey'];
            $where['keyword'] = $data['EventKey'];
            $where['companyid'] = $this->companyid;
            $where['token'] = $this->token;
            M('diymen_class')->where($where)->setInc('clicknum');
        }elseif('VIEW' == $data['Event']) {
        	//自定义菜单跳转事件
            $where['url'] = htmlspecialchars($data['EventKey']);
            $where['companyid'] = $this->companyid;
            $where['token'] = $this->token;
            M('diymen_class')->where($where)->setInc('clicknum');
        }elseif('subscribe' == $data['Event']) {
            $isSaveLog = $log = '';
        	$scene_id = $this->weixin->getRevSceneId();
        	$scene_id = $scene_id ? $scene_id : 0;
        	$isadd = 2;//标识是否是新增，1：添加；2：修改；
        	M()->startTrans();
        	if($this->openid){
        		$count = M('report_member_system_group')->where(array('companyid'=>$this->companyid))->count();
        		if($count>0){
        			$array = array();
        			$groupInfo = M('report_member_system_group')->where(array('companyid'=>$this->companyid))->getField('subscribetype');
        			$subscribetype = M()->table('tp_member_register_info as mri')->join('tp_member_wechat_info as mwi on mwi.mid = mri.id')->where(array('mri.companyid'=>$this->companyid,'mwi.openid'=>$this->openid))->getField('mri.subscribetype');
        			if($groupInfo){
        				$groupInfo = json_decode($groupInfo,true);
        				foreach ($groupInfo as $gkey=>$gval){
        					if($gkey == $subscribetype){
        						if(!$gval){
        							$array[$gkey] = '0';
        						}else{
        							$array[$gkey] = $gval-1;
        						}
        					}elseif($gkey == '1'){
        						$array[$gkey] = $gval+1;
        					}else{
        						if(!$gval){
        							$array[$gkey] = '0';
        						}else{
        							$array[$gkey] = $gval;
        						}
        					}
        				}
        				M('report_member_system_group')->where(array('companyid'=>$this->companyid))->save(array('subscribetype'=>json_encode($array),'updatetime'=>time()));
        			}else{
        				$array = array('1'=>'1','2'=>'0','0'=>'0');
        				M('report_member_system_group')->where(array('companyid'=>$this->companyid))->save(array('subscribetype'=>json_encode($array),'updatetime'=>time()));
        			}
        		}else{
        			M('report_member_system_group')->add(array('id'=>guidNow(),'companyid'=>$this->companyid,'updatetime'=>time(),'createtime'=>time(),'subscribetype'=>json_encode(array('0'=>'0','1'=>'1','2'=>'0'))));
        		}
        		$memberWechatInfoWhere = array('companyid'=>$this->companyid,'openid'=>$this->openid);
        		$countInfo = M('member_wechat_info')->where($memberWechatInfoWhere)->field('mid,updatetime')->find();
        		if(!$countInfo || $countInfo['updatetime']<strtotime('-1 day')){
        		    $userInfo = $this->weixin->getUserInfo($this->openid);
        		    if($countInfo['mid']&&$userInfo['openid']){
        		        $registerInfo = M('member_register_info')->where(array('id'=>$countInfo['mid']))->save(array('subscribetype'=>1,'updatetime'=>time()));
        		        if(!$registerInfo){
        		            $log .='3.member_register_info更新微信资料时关注状态修改失败,sql:'.M()->getLastSql().';';
        		        }
        		        //更新微信资料表
        		        $wechatData['companyid'] = $this->companyid;
        		        $wechatData['mid'] = $countInfo['mid'];
        		        $wechatData['scene_id'] = $scene_id;
        		        $wechatData['openid'] = $userInfo['openid'];
        		        $wechatData['nickname'] = $userInfo['nickname'];
        		        $wechatData['gender'] = $userInfo['sex'];
        		        $wechatData['language'] = $userInfo['language'];
        		        $wechatData['headimgurl'] = $userInfo['headimgurl'];
        		        $cityareaInfo = M('area')->where(array('name'=>$userInfo['city']))->field('id')->find();
        		        if($cityareaInfo){
        		        	$wechatData['city'] = $cityareaInfo['id'];
        		        }else{
        		        	$wechatData['city'] = 0;
        		        }
        		        $provinceareaInfo = M('area')->where(array('name'=>$userInfo['province']))->field('id')->find();
        		        if($provinceareaInfo){
        		        	$wechatData['province'] = $provinceareaInfo['id'];
        		        }else{
        		        	$wechatData['province'] = 0;
        		        }
        		        $countryareaInfo = M('area')->where(array('name'=>$userInfo['country']))->field('id')->find();
        		        if($countryareaInfo){
        		        	$wechatData['country'] = $countryareaInfo['id'];
        		        }else{
        		        	$wechatData['country'] = 0;
        		        }
        		        $wechatData['subscribe_time'] = $wechatData['wechatmessagetime'] = $userInfo['subscribe_time'];
        		        $wechatData['updatetime'] = time();
        		        $wechatInfo = M('member_wechat_info')->where($memberWechatInfoWhere)->save($wechatData);
        		        if(!$wechatInfo){
        		            $log .='4.member_wechat_info更新微信资料失败,sql:'.M()->getLastSql().';';
        		        }
        		    }elseif ($userInfo['openid']){
        		        $isadd = 1;
        		        $registerData['companyid'] = $this->companyid;
        		        $registerData['subscribetype'] = 1;
        		        $registerData['gender'] = $userInfo['sex'];
        		        $registerData['createtime'] = $registerData['updatetime'] = time();
        		        $registerInfo = $countInfo['mid'] = M('member_register_info')->add($registerData);
        		        if(!$registerInfo){
        		            $log .='5.member_register_info添加微信资料时添加失败,sql:'.M()->getLastSql().';';
        		        }
        		        if($countInfo['mid']){
        		            //添加微信资料表
        		        	$wechatData['companyid'] = $this->companyid;
        		        	$wechatData['mid'] = $countInfo['mid'];
        		        	$wechatData['scene_id'] = $scene_id;
        		        	$wechatData['openid'] = $userInfo['openid'];
        		        	$wechatData['nickname'] = $userInfo['nickname'];
        		        	$wechatData['gender'] = $userInfo['sex'];
        		        	$wechatData['language'] = $userInfo['language'];
        		        	$wechatData['headimgurl'] = $userInfo['headimgurl'];
        		        	$cityareaInfo = M('area')->where(array('name'=>$userInfo['city']))->field('id')->find();
        		        	if($cityareaInfo){
        		        		$wechatData['city'] = $cityareaInfo['id'];
        		        	}else{
        		        		$wechatData['city'] = 0;
        		        	}
        		        	$provinceareaInfo = M('area')->where(array('name'=>$userInfo['province']))->field('id')->find();
        		        	if($provinceareaInfo){
        		        		$wechatData['province'] = $provinceareaInfo['id'];
        		        	}else{
        		        		$wechatData['province'] = 0;
        		        	}
        		        	$countryareaInfo = M('area')->where(array('name'=>$userInfo['country']))->field('id')->find();
        		        	if($countryareaInfo){
        		        		$wechatData['country'] = $countryareaInfo['id'];
        		        	}else{
        		        		$wechatData['country'] = 0;
        		        	}
        		        	$wechatData['subscribe_time'] = $userInfo['subscribe_time'];
        		            $wechatData['updatetime'] = $wechatData['createtime'] = time();
        		            $wechatInfo = M('member_wechat_info')->add($wechatData);
        		            if(!$wechatInfo){
        		                $log .='6.member_wechat_info添加微信资料失败,sql:'.M()->getLastSql().';';
        		            }
        		        }
        		    }
        		}else{
        		    $wechatInfo = 1;
        		    $registerInfo = M('member_register_info')->where(array('id'=>$countInfo['mid'],'companyid'=>$this->companyid))->save(array('subscribetype'=>1,'updatetime'=>time()));
        		    if(!$registerInfo){
        		        $log .='7.member_register_info24h内更新微信资料是修改失败,sql:'.M()->getLastSql().';';
        		    }
        		}
        	}else{
        	    //$wechatInfo = $registerInfo = 1;
        	    $log .= '1.没有openid;';
        	}
    	    $subscribetime = time();
    		$registerInfo = $wechatInfo = 1;
        	$subscribeLinkWhere['companyid'] = $this->companyid;
        	$subscribeLinkWhere['openid'] = $this->openid;
        	$subscribeLinkCount = M('wechat_member_subscribe_link')->where($subscribeLinkWhere)->count();
        	$subscribeLinkData['type'] = 1;
        	$subscribeLinkData['scene_id'] = $scene_id;
        	$subscribeLinkData['subscribetime'] = $subscribeLinkData['updatetime'] = $subscribetime;
        	if($subscribeLinkCount){
        		$subscribeLinkInfo = M('wechat_member_subscribe_link')->where($subscribeLinkWhere)->save($subscribeLinkData);
        		if(!$subscribeLinkInfo){
        		    $log .='8.wechat_member_subscribe_link修改失败,sql:'.M()->getLastSql().';';
        		}
        	}else{
        		$subscribeLinkData['companyid'] = $this->companyid;
        		$subscribeLinkData['token'] = $this->token;
        		$subscribeLinkData['openid'] = $this->openid;
        		$subscribeLinkInfo = M('wechat_member_subscribe_link')->add($subscribeLinkData);
        		if(!$subscribeLinkInfo){
        		    $log .='9.wechat_member_subscribe_link添加失败,sql:'.M()->getLastSql().';';
        		}
        	}
        	//二维码扫描次数
        	if($scene_id>0){
        		$quickResponseCodeInfo = M('quick_response_code')->where(array('companyid'=>$this->companyid,'content'=>$scene_id))->setInc('scannum');
        		M('quick_response_code')->where(array('companyid'=>$this->companyid,'content'=>$scene_id))->setInc('subscribe');
        		$daytime = strtotime(format_time(time(),'ymd'));
        		$quickResponseCodelogcount = M('quick_response_code_daylog')->where(array('companyid'=>$this->companyid,'qid'=>$scene_id,'day'=>$daytime))->count();
        		if($quickResponseCodelogcount>0){
        			M('quick_response_code_daylog')->where(array('companyid'=>$this->companyid,'qid'=>$scene_id,'day'=>$daytime))->setInc('subscribe');
        			M('quick_response_code_daylog')->where(array('companyid'=>$this->companyid,'qid'=>$scene_id,'day'=>$daytime))->setInc('scannum');
        		}else{
        			$qlc['id'] = guidNow();
        			$qlc['companyid'] = $this->companyid;
        			$qlc['qid'] = $scene_id;
        			$qlc['day'] = $daytime;
        			$qlc['subscribe'] = '1';
        			$qlc['scannum'] = '1';
        			$qlc['updatetime'] = $qlc['createtime'] = time();
        			M('quick_response_code_daylog')->add($qlc);
        		}
        		M('member_wechat_info')->where(array('companyid'=>$this->companyid,'openid'=>$this->openid))->save(array('scene_id'=>$scene_id));
        	}
        	$wechatCount = M('member_wechat_info')->where(array('companyid'=>$this->companyid,'openid'=>$this->openid))->count();
        	if($wechatInfo&&$registerInfo&&$subscribeLinkInfo&&$isadd=='1'&&$wechatCount==1){
        		M()->commit();
        		$send = 1;//标识发送关注回复
        	}elseif($wechatInfo&&$registerInfo&&$subscribeLinkInfo&&$isadd=='2'){
        		M()->commit();
        		$send = 1;//标识发送关注回复
        	}else{
        		M()->rollback();
        		$send = 1;
        		$isSaveLog = '1';
        		$log .= '2.各返回值参数:$wechatInfo=='.$wechatInfo.';$registerInfo=='.$registerInfo.';$subscribeLinkInfo=='.$subscribeLinkInfo.';$quickResponseCodeInfo=='.$quickResponseCodeInfo.';$isadd=='.$isadd.';$wechatCount=='.$wechatCount;
        	}
        	if($isSaveLog == '1'){
        	    $logData['companyid'] = $this->companyid;
        	    $logData['token'] = $this->token;
        	    $logData['openid'] = $this->openid;
        	    $logData['type'] = '3';
        	    $logData['log'] = '关注日志：'.$log;
        	    $logData['createtime'] = time();
        	    M('get_wechat_subscribe_log')->add($logData);
        	}
        	//邀请赠礼的时候，通过扫二维码
        	$this->wechatYVoucher(array("scene_id"=>$scene_id,"companyid"=>$this->companyid,"mid"=>$countInfo['mid'],"nickname"=>$userInfo['nickname'],"headimgurl"=>$userInfo['headimgurl']));
        	//展业伙伴扫码的时候，通过扫二维码
        	$this->wechatYVoucher2(array("scene_id"=>$scene_id,"companyid"=>$this->companyid,"mid"=>$countInfo['mid'],"nickname"=>$userInfo['nickname'],"headimgurl"=>$userInfo['headimgurl'],"openid"=>$this->openid));
        	 
        	
        	if($send){
        		$areplyInfo = M('areply')->where($this->publicWhere)->find();
        		if ($areplyInfo['type'] == 2) {
        			return array(array(array('Title'=>htmlspecialchars_decode($areplyInfo['title']),'Description'=>htmlspecialchars_decode($areplyInfo['content']),'PicUrl'=>$areplyInfo['picurl'],'Url'=>htmlspecialchars_decode($areplyInfo['url']))),'news');
        		}elseif ($areplyInfo['type'] == 1){
        			if(strstr($areplyInfo['info'],'#nickname#')){
							$nickname = M('member_wechat_info')->where(array('companyid'=>$this->companyid,'openid'=>$this->openid))->getField('nickname');
							$areplyInfo['info'] = str_replace('#nickname#',$nickname,$areplyInfo['info']);
						}
        			return array(emoji_decode(htmlspecialchars_decode($areplyInfo['info'])),'text');
        		}
        	}
        	unset($memberWechatInfoWhere,$countInfo,$userInfo,$wechatData,$cityareaInfo,$provinceareaInfo,$countryareaInfo,$wechatInfo,$registerData,$registerInfo,$subscribeLinkWhere,$subscribeLinkCount,$subscribeLinkData,$subscribeLinkInfo,$quickResponseCodeInfo,$wechatCount);
        }elseif('unsubscribe' == $data['Event']) {
            
            $isSaveLog = $log = '';
        	M()->startTrans();
        	$unsubscribetime = time();
        	$subscribeLinkWhere['companyid'] = $this->companyid;
        	$subscribeLinkWhere['openid'] = $this->openid;
        	$subscribeLinkInfo = M('wechat_member_subscribe_link')->where($subscribeLinkWhere)->field('id,mid,scene_id')->find();
        	$subscribeLinkData['type'] = 2;
        	$subscribeLinkData['scene_id'] = 0;
        	$subscribeLinkData['unsubscribetime'] =$subscribeLinkData['updatetime'] = $unsubscribetime;
        	if($subscribeLinkInfo){
        		if($subscribeLinkInfo['scene_id']>0){
        			M('quick_response_code')->where(array('companyid'=>$this->companyid,'content'=>$subscribeLinkInfo['scene_id']))->setDec('scannum');
        			M('quick_response_code')->where(array('companyid'=>$this->companyid,'content'=>$subscribeLinkInfo['scene_id']))->setInc('unsubscribe');
        			$daytime = strtotime(format_time(time(),'ymd'));
        			$quickResponseCodelogcount = M('quick_response_code_daylog')->where(array('companyid'=>$this->companyid,'qid'=>$subscribeLinkInfo['scene_id'],'day'=>$daytime))->count();
        			if($quickResponseCodelogcount>0){
        				M('quick_response_code_daylog')->where(array('companyid'=>$this->companyid,'qid'=>$subscribeLinkInfo['scene_id'],'day'=>$daytime))->setInc('unsubscribe');
        				M('quick_response_code_daylog')->where(array('companyid'=>$this->companyid,'qid'=>$subscribeLinkInfo['scene_id'],'day'=>$daytime))->setDec('scannum');
        			}else{
        				$qlc['id'] = guidNow();
        				$qlc['companyid'] = $this->companyid;
        				$qlc['qid'] = $subscribeLinkInfo['scene_id'];
        				$qlc['day'] = $daytime;
        				$qlc['subscribe'] = '1';
        				$qlc['unsubscribe'] = '1';
        				$qlc['updatetime'] = $qlc['createtime'] = $unsubscribetime;
        				M('quick_response_code_daylog')->add($qlc);
        			}
        		}
        		$subscribeLinkSave = M('wechat_member_subscribe_link')->where($subscribeLinkWhere)->save($subscribeLinkData);
			}else{
        		$subscribeLinkData['companyid'] = $this->companyid;
        		$subscribeLinkData['token'] = $this->token;
        		$subscribeLinkData['openid'] = $this->openid;
        		$subscribeLinkSave = M('wechat_member_subscribe_link')->add($subscribeLinkData);
        	}
        	$wechatMid = M('member_wechat_info')->where($subscribeLinkWhere)->getField('mid');
        	if($wechatMid){
        		$count = M('report_member_system_group')->where(array('companyid'=>$this->companyid))->count();
        		if($count>0){
        			$array = array();
        			$groupInfo = M('report_member_system_group')->where(array('companyid'=>$this->companyid))->getField('subscribetype');
        			$subscribetype = M('member_register_info')->where(array('companyid'=>$this->companyid,'id'=>$wechatMid))->getField('subscribetype');
        			if($groupInfo){
        				$groupInfo = json_decode($groupInfo,true);
        				foreach ($groupInfo as $gkey=>$gval){
        					if($gkey == $subscribetype){
        						if(!$gval){
        							$array[$gkey] = '0';
        						}else{
        							$array[$gkey] = $gval-1;
        						}
        					}elseif($gkey == '2'){
        						$array[$gkey] = $gval+1;
        					}else{
        						if(!$gval){
        							$array[$gkey] = '0';
        						}else{
        							$array[$gkey] = $gval;
        						}
        					}
        				}
        				M('report_member_system_group')->where(array('companyid'=>$this->companyid))->save(array('subscribetype'=>json_encode($array),'updatetime'=>time()));
        			}else{
        				$array = array('1'=>'0','2'=>'1','0'=>'0');
        				M('report_member_system_group')->where(array('companyid'=>$this->companyid))->save(array('subscribetype'=>json_encode($array),'updatetime'=>time()));
        			}
        		}else{
        			M('report_member_system_group')->add(array('id'=>guidNow(),'companyid'=>$this->companyid,'updatetime'=>time(),'createtime'=>time(),'subscribetype'=>json_encode(array('0'=>'0','1'=>'0','2'=>'1'))));
        		}
        	    $registerInfo = M('member_register_info')->where(array('id'=>$wechatMid))->save(array('subscribetype'=>2,'updatetime'=>time()));
        	}else{
        	    $registerInfo = 1;
        	}
        	if($subscribeLinkSave&&$registerInfo){
        		M()->commit();
        	}else{
        		M()->rollback();
        		$isSaveLog = 1;
        		$log .= '2:事物执行失败，具体信息如下，wechat_member_subscribe_link返回值：'.$subscribeLinkSave.'sql:'.M('wechat_member_subscribe_link')->getLastSql().'，member_register_info返回值：'.$registerInfo.'sql:'.M('member_register_info')->getLastSql();
        	}
        	if($isSaveLog == '1'){
        	    $logData['companyid'] = $this->companyid;
        	    $logData['token'] = $this->token;
        	    $logData['openid'] = $this->openid;
        	    $logData['type'] = '3';
        	    $logData['log'] = '取消关注日志：'.$log;
        	    $logData['createtime'] = time();
        	    M('get_wechat_subscribe_log')->add($logData);
        	}
        	unset($subscribeLinkData,$subscribeLinkWhere,$subscribeLinkInfo,$wechatInfo,$registerInfo);
        }elseif('MASSSENDJOBFINISH' == $data['Event']) {
        	//群发消息反馈
        	$where['msg_id'] = $data['MsgID'];
        	$where['companyid'] = $this->companyid;
        	//$where['token'] = $this->token;
        	M('message_wechats')->where($where)->save(array('sentnum'=>$data['SentCount'],'iscompleteed'=>3,'completetime'=>time(),'month'=>format_time(time(),'/ym')));
        }elseif ('SCAN' == $data['Event']){
            $scene_id = $data['EventKey'];
            if($scene_id){
                $boundKeyword = M('quick_response_code')->where(array('companyid'=>$this->companyid,'content'=>$scene_id))->getField('boundkeyword');
                if($boundKeyword){
                    return $this->keyword($boundKeyword,$this->openid);
                }
            }
        }elseif ('card_pass_check' == $data['Event']){
            // 审核事件推送（审核通过）
            $voucherData['checkstatus'] = '1';
            $voucherData['updatetime'] = time();
            M('wechat_voucher_apply_history')->where(array('companyuid'=>$this->companyid,'cardid'=>$data['CardId']))->save($voucherData);
        }elseif ('card_not_pass_check' == $data['Event']){
            // 审核事件推送（审核未通过）
            $voucherData['checkstatus'] = '2';
            $voucherData['updatetime'] = time();
            M('wechat_voucher_apply_history')->where(array('companyuid'=>$this->companyid,'cardid'=>$data['CardId']))->save($voucherData);
        }elseif ('user_get_card' == $data['Event']){
            // 粉丝领券事件推送
            if($data['IsGiveByFriend'] == 1){
                $oldCode = $data['OldUserCardCode'];
                if($oldCode){
                    M('wechat_voucher_consume_history')->where(array('companyuid'=>$this->companyid,'cardcode'=>$oldCode))->delete();
                }
            }
            $vouchersData['companyid'] = $this->companyid;
            $vouchersData['openid'] = $data['FromUserName'];
            $vouchersData['isconsume'] = 2;
            $vouchersData['cardid'] =  $data['CardId'];
            $vouchersData['cardcode'] = $data['UserCardCode'];
            $vouchersData['outerid'] = $data['OuterId'];
            $vouchersData['updatetime'] = $vouchersData['createtime'] = time();
            $info = M('wechat_voucher_apply_history')->where(array('companyid'=>$this->companyid,'cardid'=>$data['CardId']))->field('id,activitytimetype,starttime,endtime,takeeffectday,invalidday')->find();
            if ($info['activitytimetype'] == '1'){
                $vouchersData['codestarttime'] = $info['starttime'];
                $vouchersData['codeendtime'] = $info['endtime'];
            }elseif ($info['activitytimetype'] == '2'){
                $vouchersData['codestarttime'] = time()+($info['takeeffectday']*24*60*60);
                $vouchersData['codeendtime'] = time()+($info['invalidday']*24*60*60);
            }
            M('wechat_voucher_consume_history')->add($vouchersData);
        }elseif ('user_consume_card' == $data['Event']){
            // 核销优惠券事件推送
            $cardCode = $data['UserCardCode'];
            if($cardCode){
                $vouchersWhere['cardcode'] = $cardCode;
                $vouchersWhere['companyid'] = $this->companyid;
                $vouchersWhere['openid'] = $data['FromUserName'];
                $vouchersWhere['cardid'] =  $data['CardId'];
                $vouchersData['consumesource'] = $data['ConsumeSource'];
                $vouchersData['locationname'] = $data['LocationName'];
                $vouchersData['staffopenid'] = $data['StaffOpenId'];
                $vouchersData['isconsume'] = 1;
                $vouchersData['updatetime'] = time();
                M('wechat_voucher_consume_history')->where($vouchersWhere)->save($vouchersData);
            }
        }elseif ('user_del_card' == $data['Event']){
            // 用户删除卡券
            M('wechat_voucher_consume_history')->where(array('companyid'=>$this->companyid,'cardcode'=>$data['UserCardCode']))->delete();
        }elseif ('poi_check_notify' == $data['Event']){
            // 门店审核
            // 审核结果的事物
            if($data['Result'] == 'succ'){
                $shopData['auditstatus'] = '2';
            }else{
                $shopData['auditstatus'] = '3';
            }
            $shopData['poiid'] = $data['PoiId'];
            $shopData['updatetime'] = time();
            M('company_shops')->where(array('id'=>$data['UniqId']))->save($shopData);
        }
        
        if($data['MsgType'] == 'text'){
        	$wechatInfo = M('member_wechat_info')->where(array('companyid'=>$this->companyid,'openid'=>$this->openid))->field('mid')->find();
        	if($wechatInfo){
        		M('member_wechat_24hourmessage')->add(array('companyid'=>$this->companyid,'mid'=>$wechatInfo['mid'],'option'=>$this->openid,'info'=>$data['Content'],'isread'=>'1','msgtype'=>'2','createtime'=>time()));
        		//微信24小时
        		$wechatmessage['wechatmessagetime'] = time();
        		$wechatmessage['wechatmessageisread'] = 2;
        		$wechatmessage['wechatmessageisreply'] = 2;
        		M('member_wechat_info')->where(array('companyid'=>$this->companyid,'openid'=>$this->openid))->save($wechatmessage);
        	}
        }
        if('MASSSENDJOBFINISH' == $data['Event']) {
        	//群发消息反馈
        	$where['msg_id'] = $data['MsgID'];
        	$where['companyid'] = $this->companyid;
        	//$where['token'] = $this->token;
        	M('message_wechats')->where($where)->save(array('sentnum'=>$data['SentCount'],'iscompleteed'=>3,'completetime'=>time(),'month'=>format_time(time(),'/ym')));
        }
        //*************************************获得附近商家*************************************
        if('location' == $data['MsgType']){
        	$Location_X = $data['Location_X'];
        	$Location_Y = $data['Location_Y'];
        	//公司分店、距离
        	$companyShopsList = M('company_shops')->where(array('companyid'=>$this->companyid,'isshow'=>1))->field('id,companyid,tel,name,address,logourl,latitude,longitude')->select();
        	if($companyShopsList){
        		$companyTitle = '点击查看周边全部'.count($companyShopsList).'家门店';
        		$shops[] = array('Title'=>$companyTitle,'Description'=>'','PicUrl'=>C('site_url').'/Tpl/User/default/common/images/map.jpg','Url'=>C('site_url').U('Wap/MemberDining/shops',array('companyid'=>$this->companyid)));
	        	foreach($companyShopsList as $cslKey=>$cslVal){
	        		if($Location_X&&$Location_Y){
	        			$wecahtLatLon = Convert_GCJ02_To_BD09($Location_X,$Location_Y);
	        			$companyShopsList[$cslKey]['position'] =get_distance($wecahtLatLon['lat'],$wecahtLatLon['lng'],$cslVal['latitude'],$cslVal['longitude']); 
	        		}
	        	}
	        	$newCompanyShopsList = arraySort($companyShopsList,'position','SORT_ASC');
	        	if($newCompanyShopsList){
	        		foreach($newCompanyShopsList as $ncslKey=>$ncslVal){
	        				 $shops[] = array('Title'=>distance($ncslVal['position']).'【'.$ncslVal['name'].'】','Description'=>'','PicUrl'=>$ncslVal['logourl'],'Url'=>C('site_url').U('Wap/MemberDining/shopsInfo',array('id'=>$ncslVal['id'],'companyid'=>$this->companyid)));
	        				 if($ncslKey == 8){
	        				 	break;
	        				 }
	        		}
	        	}
	        	$return = array($shops,'news');
        	}else{
        		$return = array('您附近暂无该商家！','text');
        	}
        	return $return;
        }
        return $this->keyword($data['Content'],$this->openid);
    }
    /**
     * 通过关键词获得回复
     * Enter description here ...
     * @param unknown_type $key
     * 类型：News（图文回复）、Text（文字回复）
     */
    function keyword($key,$openid){
        if($key&&$openid){
            $keywordWhere['token']   = $this->token;
            //$keywordWhere['keyword'] =  $key ;
            $keywordWhere['_string'] = " keyword = binary '".$key."' ";
            $keywordWhere['module'] = 'News';
            $newsKeywordInfo = M('keyword')->where($keywordWhere)->limit('10')->order('id DESC')->select();
        }
        if ($newsKeywordInfo){
			//修改请求记录 成功请求
			// M('history_wechat_request')->where(array('MsgId'=>$this->data['MsgId'],'token'=>$this->token))->save(array('isshow'=>1,'isnews'=>1)); 是否命中显示标识，由于SQL耗时暂时废除
        	foreach ($newsKeywordInfo as $nKey=>$nVal){
        		//修改展现数
        		M('keyword')->where(array('companyid'=>$this->companyid,'id'=>$nVal['id']))->setInc('shownum');
        		$url = 'javascript:void(0);';
        		if($nVal['url']){
        			$url = htmlspecialchars_decode($nVal['url']);
        		}
        		$news[] = array('Title'=>$nVal['title'],'Description'=>$nVal['content'],'PicUrl'=>$nVal['picurl'],'Url'=>$url);
        	}
        	return array($news,'news');
        }else{
            if($key && $openid){
            	$keywordWhere['module'] = 'Text';
            	$textKeywordInfo = M('keyword')->where($keywordWhere)->order('id DESC')->find();
            }
        	if($textKeywordInfo){
        		//修改展现数
        		M('keyword')->where(array('companyid'=>$this->companyid,'id'=>$textKeywordInfo['id']))->setInc('shownum');
        		//修改请求记录 成功请求
        		// M('history_wechat_request')->where(array('MsgId'=>$this->data['MsgId'],'token'=>$this->token))->save(array('isshow'=>1)); 是否命中显示标识，由于SQL耗时暂时废除
        		if(strstr($textKeywordInfo['content'],'#nickname#')){
        			$nickname = M('member_wechat_info')->where(array('companyid'=>$this->companyid,'openid'=>$openid))->getField('nickname');
        			$textKeywordInfo['content'] = str_replace('#nickname#',$nickname,$textKeywordInfo['content']);
        		}
        		return array(emoji_decode(htmlspecialchars_decode($textKeywordInfo['content'])),'text');
        	}else{
        		$otherInfo = M('other')->where(array('token' => $this->token,'isshow'=>1))->find();
        		if ($otherInfo) {
        			if(strstr($otherInfo['info'],'#nickname#')){
        				$nickname = M('member_wechat_info')->where(array('companyid'=>$this->companyid,'openid'=>$openid))->getField('nickname');
        				$otherInfo['info'] = str_replace('#nickname#',$nickname,$otherInfo['info']);
        			}
        			return array(emoji_decode(htmlspecialchars_decode($otherInfo['info'])),'text');
        		}else{
        			return array('ceshi','transfer_customer_service');
        		}
        	}
        }
    }  	
}
?>