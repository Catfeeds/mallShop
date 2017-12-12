<?php
/**
 * 登陆/注册/找回密码/密码已发送
 * Enter description here ...
 * @author yaochengkai
 */
class LoginAction extends WapBaseAction{
	
	private $companyInfoModel;
	
	private $usersModel;
	
	private $companyModel;
	
	private $companyGroupModel;
	
	private $memberIntegralSetModel;
	
	private $memberRegisterInfoModel;
	
	private $memberCardInfoSetModel;
	
	private $memberIntegralModel;
	
	private $memberCardRankModel;
	
	private $memberCardInfoModel;
	
	public function __construct(){
		parent::__construct();
		$this->memberRegisterInfoModel = M('member_register_info');
		$this->companyInfoModel = M('company');
		$this->usersModel = M('users');
		$this->companyModel = M('company');
		$this->companyGroupModel = M('company_group');
	}
	public function test(){
		session(null);
	}
	/**
	 * 
	 * 验证注册码并注册或登录
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2017-3-9
	 */
	public function ajaxIegiCode(){
		if(IS_POST){
			$time = time();
			$moblie = $this->_post('moblie','trim');
			$code = $this->_post('code');
			$companyid = $this->_get('companyid','intval');  // 公司id
			$list = M('member_mobile_code')->where(array('companyid'=>$companyid,'mobile'=>$moblie,'isused'=>'2'))->find();
			if($list){
				if($list['code'] == $code){
					M()->startTrans();
					$set['usetime'] = $time;
					$set['isused'] = 1;
					$co = M('member_mobile_code')->where(array('companyid'=>$companyid,'mobile'=>$moblie))->save($set);
					if($co){
						if($companyid){
							$company = $this->companyInfoModel->where(array('id'=>$companyid,'isclose'=>'0','status'=>array('in','1,3')))->field('id,name,gid,viptime,copyright,servicenumber,permissions')->find();   // 查找公司信息
							if(($company['gid'] !=5 && $company['viptime'] >= $time) || $company['gid'] ==5 ){
								$openid = session('openid'.$companyid);
								$agent = $_SERVER['HTTP_USER_AGENT'];
								$lii = M('member_register_info')->where(array('companyid'=>$companyid,'moblie'=>$moblie,"isregister"=>1))->count(); // 查询手机号是否存在
								if($lii <= 0){
								    // 再次核查是否注册
									$registerInfo = M('member_register_info')->where(array('companyid'=>$companyid,'moblie'=>$moblie))->field("id,moblie")->find();
									$registerData['companyid'] = $companyid;
									$registerData['moblie'] = $moblie;
									$registerData['isregister'] = '1';
									$registerData['registertypetag'] = '1';
									if($registerInfo){
									    $registerId = $registerInfo['id'];
									    $registerData['updatetime'] = $time;
									    $registerData['percent'] = 50;
									    $registerReturn = M('member_register_info')->where(array("companyid"=>$companyid,"id"=>$registerInfo['id']))->save($registerData);
									}else{
									    $registerData['createtime'] = $registerData['updatetime'] = $time;
									    $registerData['percent'] = 50;
									    $registerId = $registerReturn = M('member_register_info')->add($registerData);
									}
									//资料拉取+资料匹配
									if(strpos($agent,"MicroMessenger") && $openid) {   //是否是微信环境
										$wechatInfo = M('member_wechat_info')->where(array('companyid'=>$companyid,'openid'=>$openid))->field('id,mid,nickname,headimgurl')->find(); // 夺标连接匹配信息
										if($wechatInfo){
										    $wechatData['updatetime'] = $time;
										    $wechatData['mid'] = $registerId;
										    $wechatReturn = M('member_wechat_info')->where(array("companyid"=>$companyid,"id"=>$wechatInfo['id']))->save($wechatData);
										}else{
										    $wechatInfo['openid'] = $openid;
											$wechatData['companyid'] = $companyid;
											$wechatData['mid'] = $registerId;
											$wechatData['openid'] = $openid;
											$wechatData['updatetime'] = $wechatData['createtime']  = $time;
											$wechatReturn = M('member_wechat_info')->add($wechatData);
										}
									}else{
										$wechatReturn = '1';
									}
									if($registerReturn && $wechatReturn){
										M()->commit();
										// 改变Tag统计数据
										M('company')->where(array('id'=>$companyid))->setInc('registermembernum');
										$this->memberTagCount($companyid, array(array('name'=>'registertype','after'=>'1'),array('name'=>'gender','after'=>'0')));
										$this->memberTagCount($companyid, array(array('name'=>'howlongspending','after'=>"0"),array('name'=>'spendingfrequency','after'=>"0"),array('name'=>'totalspending','after'=>"0"),array('name'=>'howlongusevouchers','after'=>"0"),array('name'=>'usevouchersfrequency','after'=>"0"),));
										// 暂时隐藏 $this->memberTagCount($companyid, array(array('name'=>'constellation','after'=>$registerData['constellationtag']),array('name'=>'age','after'=>$registerData['ageteg'])));
										//注册活动发券的
										$whereAsa['companyid'] = $companyid;
										$whereAsa['type'] = 5;
										$whereAsa['starttime'] = array('lt',$time);
										$whereAsa['endtime'] = array('gt',$time);
										$whereAsa['issuspend'] = array('neq',1);
										$whereAsa['status'] = array('neq',1);
										$voucherid = M('member_marketing_activities_scrm')->where($whereAsa)->field("id")->select();
										if($voucherid){
											foreach($voucherid as $asaVal){
												$register = $this->sendMemberVouchersSCRM5($asaVal['id'], $registerId,$companyid,'9');
											}
										}
										$data['code'] = 200;
										$data['tips'] = '恭喜,注册成功!';
										session('mid'.$companyid,$registerId);
										session('mname'.$companyid,'');
										if($wechatInfo){
    										session('wname'.$companyid,$wechatInfo['nickname']);
    										session('whead'.$companyid,$wechatInfo['headimgurl']);
    										session('openid'.$companyid,$wechatInfo['openid']);
    										$this->WeChatTemplateMessageSend('1',$wechatInfo['openid'],$companyid,'',array($moblie),array('注册成功',format_time($time,'ymdhis')),'');
										}
										session('loginBoxShow'.$companyid,2);
										//消息模板
										$this->changeMemberBusinessSCRM5(array('cid'=>$companyid,'mid'=>$registerId,'type'=>'102'));
										$qcode = M()->table('tp_quick_response_code as qrc')->join('left join tp_member_wechat_info as mwi on mwi.scene_id = qrc.content')->where(array('mwi.companyid'=>$companyid,'qrc.companyid'=>$companyid,'mwi.mid'=>$registerId))->field('qrc.registernum,qrc.content,qrc.boundshopid,qrc.userid')->find();
										if($qcode){
											//如果是自定义二维码
											if($qcode['userid']>0){
												$boundshopid = M('users')->where(array('companyid'=>$companyid,'id'=>$qcode['userid']))->getField('helpershopid');
											}else{
												$boundshopid = $qcode['boundshopid'];
											}
											//给会员追加所属门店
											M('member_register_info')->where(array('companyid'=>$companyid,'id'=>$registerId))->save(array('boundshopid'=>$boundshopid,'updatetime'=>$time));
											M('quick_response_code')->where(array('companyid'=>$companyid,'content'=>$qcode['content']))->setInc('registernum');
											$daytime = strtotime(format_time($time,'ymd'));
											$quickResponseCodelogcount = M('quick_response_code_daylog')->where(array('companyid'=>$companyid,'qid'=>$qcode['content'],'day'=>$daytime))->count();
											if($quickResponseCodelogcount>0){
												M('quick_response_code_daylog')->where(array('companyid'=>$companyid,'qid'=>$qcode['content'],'day'=>$daytime))->setInc('registernum');
											}else{
												$qlc['id'] = guidNow();
												$qlc['companyid'] = $companyid;
												$qlc['qid'] = $qcode['content'];
												$qlc['day'] = $daytime;
												$qlc['registernum'] = '1';
												$qlc['updatetime'] = $qlc['createtime'] = $time;
												M('quick_response_code_daylog')->add($qlc);
											}
										}
									}else{
										M()->rollback();
										$data['code'] = 300;
										$data['tips'] = '注册失败';
									}
								}else{
									$registerInfo = M('member_register_info')->where(array('companyid'=>$companyid,'moblie'=>$moblie))->field('id,name,moblie,subscribetype')->find();
									if($openid && strpos($agent,"MicroMessenger")){
										// 根据当前openid找到将要绑定的微信资料
										$newWechInfo = M('member_wechat_info')->where(array('companyid'=>$companyid,'openid'=>$openid))->field('id,mid,nickname,headimgurl')->find();
										$oldWechInfo = M('member_wechat_info')->where(array('companyid'=>$companyid,'mid'=>$registerInfo['id']))->field('id')->find();
										if($newWechInfo && $newWechInfo['mid'] != $registerInfo['id']){
											$oldRegisterInfo = M('member_register_info')->where(array('id'=>$newWechInfo['mid']))->field('subscribetype,totalintegration,totalexperiencevalue')->find();
											$oldRegisterInfo['subscribetype'] = $oldRegisterInfo['subscribetype'] ? $oldRegisterInfo['subscribetype'] :0;
											$registerInfoSaveReturn = M('member_register_info')->where(array('companyid'=>$companyid,'id'=>$registerInfo['id']))->save(array('subscribetype'=>$oldRegisterInfo['subscribetype'],'totalexperiencevalue' => array('exp', '`totalexperiencevalue`+'.$oldRegisterInfo['totalexperiencevalue']),'totalintegration' => array('exp', '`totalintegration`+'.$oldRegisterInfo['totalintegration']),'updatetime'=>$time));
											// 老积分获取记录+消费记录+优惠券记录 移植到新的Mid下面
											M('member_spending')->where(array('companyid'=>$companyid,'mid'=>$newWechInfo['mid']))->save(array('mid'=>$registerInfo['id'],'updatetime'=>$time));
											M('member_integral')->where(array('companyid'=>$companyid,'mid'=>$newWechInfo['mid']))->save(array('mid'=>$registerInfo['id'],'updatetime'=>$time));
											M('member_vouchers')->where(array('companyid'=>$companyid,'mid'=>$newWechInfo['mid']))->save(array('mid'=>$registerInfo['id'],'updatetime'=>$time));
											// 保存WechatInfo  MID 修改
											$newWechatInfoSaveReturn = M('member_wechat_info')->where(array('companyid'=>$companyid,'id'=>$newWechInfo['id']))->save(array('mid'=>$registerInfo['id'],'updatetime'=>$time));
											// 删除多余信息 regiserInfo  cardInfo
											M('member_register_info')->where(array('id'=>$newWechInfo['mid']))->delete();
											M('member_card_info')->where(array('mid'=>$newWechInfo['mid']))->delete();
											// 先将会员信息新增一条记录 并且将原来微信信息表中的mid字段改为新的注册的id字段
											if($oldWechInfo){
												$newRegister['companyid'] = $companyid;
												$newRegister['subscribetype'] = $registerInfo['subscribetype'];
												$newRegister['createtime'] = $newRegister['updatetime'] = $time;
												$newRegisterAddReturnId = M('member_register_info')->add($newRegister);
												if($newRegisterAddReturnId){
													$newWechatInfo['mid'] = $newRegisterAddReturnId;
													$newWechatInfo['updatetime'] = $time;
													$oldWechatSaveReturn = M('member_wechat_info')->where(array('companyid'=>$companyid,'id'=>$oldWechInfo['id']))->save($newWechatInfo);
												}
											}else{
												$newRegisterAddReturnId = $oldWechatSaveReturn = 1 ;
											}
										}elseif (!$newWechInfo && $registerInfo['id']){//没有关注信息
    										    $wData['companyid'] = $companyid;
    										    $wData['mid'] = $registerInfo['id'];
    										    $wData['openid'] = $openid;
    										    $wData['updatetime'] = $wData['createtime']  = $time;
    										    $newWechatInfoSaveReturn = M('member_wechat_info')->add($wData);
    										    $registerInfoSaveReturn = $newRegisterAddReturnId = $oldWechatSaveReturn  = 1;
										}else{//没有关注信息
											$registerInfoSaveReturn = $newWechatInfoSaveReturn = $newRegisterAddReturnId = $oldWechatSaveReturn  = 1;
										}
									}else{//非微信环境
									    $newWechInfo = M('member_wechat_info')->where(array('companyid'=>$companyid,'mid'=>$registerInfo['id']))->field('id,mid,nickname,headimgurl')->find();
										$registerInfoSaveReturn = $newWechatInfoSaveReturn = $newRegisterAddReturnId = $oldWechatSaveReturn = 2;
									}
									if($registerInfoSaveReturn && $newWechatInfoSaveReturn && $newRegisterAddReturnId && $oldWechatSaveReturn){
										M()->commit();
										$this->NewchangMemberCardRank($companyid,$registerInfo['id']);//改变会员卡等级
										$qcode = M()->table('tp_quick_response_code as qrc')->join('left join tp_member_wechat_info as mwi on mwi.scene_id = qrc.content')->where(array('mwi.companyid'=>$companyid,'mwi.mid'=>$registerInfo['id']))->field('qrc.registernum,qrc.content')->find();
										if($qcode){
											//如果是自定义二维码
											if($qcode['userid']>0){
												$boundshopid = M('users')->where(array('companyid'=>$companyid,'id'=>$qcode['userid']))->getField('helpershopid');
											}else{
												$boundshopid = $qcode['boundshopid'];
											}
											//给会员追加所属门店
											M('member_register_info')->where(array('companyid'=>$companyid,'id'=>$registerInfo['id']))->save(array('boundshopid'=>$boundshopid,'updatetime'=>$time));
											M('quick_response_code')->where(array('companyid'=>$companyid,'content'=>$qcode['content']))->setInc('registernum');
											$daytime = strtotime(format_time(time(),'ymd'));
											$quickResponseCodelogcount = M('quick_response_code_daylog')->where(array('companyid'=>$companyid,'qid'=>$qcode['content'],'day'=>$daytime))->count();
											if($quickResponseCodelogcount>0){
												M('quick_response_code_daylog')->where(array('companyid'=>$companyid,'qid'=>$qcode['content'],'day'=>$daytime))->setInc('registernum');
											}else{
												$qlc['id'] = guidNow();
												$qlc['companyid'] = $companyid;
												$qlc['qid'] = $qcode['content'];
												$qlc['day'] = $daytime;
												$qlc['registernum'] = '1';
												$qlc['updatetime'] = $qlc['createtime'] = time();
												M('quick_response_code_daylog')->add($qlc);
											}
										}
										session('mid'.$companyid,$registerInfo['id']);
										session('mname'.$companyid,$registerInfo['name']);
										if($newWechInfo){
    										session('wname'.$companyid,$newWechInfo['nickname']);
    										session('whead'.$companyid,$newWechInfo['headimgurl']);
    										session('openid'.$companyid,$newWechInfo['openid']);
										}
										session('loginBoxShow'.$companyid,2);
										$data['code'] = 200;
										//$data['tips'] = '恭喜您，登录成功！';
									}else{
										M()->rollback();
										$data['code'] = 300;
										$data['tips'] = '抱歉，登录失败！';
									}
								}
							}else{
								$data['code'] = 300;
								$data['tips'] = '抱歉，您的操作有误！';
							}
						}else{ // 没有接收到公司id
							$data['code'] = 300;
							$data['tips'] = '抱歉，您的操作有误！';
						}
					}else{
						$data['code'] = '300';
						$data['tips'] = '手机验证码错误';
					}
				}else{
					$data['code'] = '300';
					$data['tips'] = '手机验证码错误';
				}
			}else{
				$data['code'] = '300';
				$data['tips'] = '请先获取验证码！';
			}
		}
		echo json_encode($data);
	}
	/**
	 * 
	 * 获取验证码
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2017-3-9
	 */
	public function getCode(){
		$time = time();
		$moblie = $this->_post('mobile');  // 接收手机号
		$companyid = 1;  // 公司id
		if($moblie && $companyid){
			//查询是否有使用的验证码
			$irgiCode = M('member_mobile_code')->where(array('companyid'=>$companyid,'mobile'=>$moblie,'isused'=>2))->find();
			if($irgiCode){  // 有
				if($irgiCode['useendtime'] >= $time){  // 未过期发送短信
					$sendData = $this->sendSms("【新微风】".$irgiCode['code'].'为您的会员注册验证码,30分钟内有效。',$moblie);
                    $ajax['code'] = '200';
                    $ajax['tips'] ='验证码已下发,请耐心等待';
				}else{ // 已过期
					$date['isused'] = '1';
					$result = M('member_mobile_code')->where(array('moblie'=>$moblie, 'companyid'=>$companyid))->save($date);
					if($result) {
						$code['companyid'] = $companyid;
						$code['mobile'] = $moblie;
						$code['code'] = substr($time,-6);  // 用时间生成验证码（当前时间戳的前4位）
						$code['useendtime'] = $time+1800;  // 过期时间
						$code['isused'] = '2';
						$code['createtime'] = $time;
						$insterCode = M('member_mobile_code')->add($code);
						if ($insterCode){
							$sendData = $this->sendSms("【新微风】".$code['code'].'为您的会员注册验证码,30分钟内有效。',$moblie);
                            $ajax['code'] = '200';
						}else{
							$ajax['code'] = '302';
							$ajax['tips'] = '验证码发送失败,请重新发送';
						}
					}
				}
			}else{ // 没有未使用的验证码
				$code['companyid'] = $companyid;
				$code['mobile'] = $moblie;
				$code['code'] = substr($time,-6);  // 用时间生成验证码（当前时间戳的前4位）
				$code['useendtime'] = $time+1800;  // 过期时间
				$code['isused'] = '2';
				$code['createtime'] = $time;
				$insterCode = M('member_mobile_code')->add($code);
				if($insterCode){
					//发送
                    $sendData = $this->sendSms("【新微风】".$code['code'].'为您的会员注册验证码,30分钟内有效。',$moblie);
                    $ajax['code'] = '200';
				}else{
					$ajax['code'] = '303';
					$ajax['tips'] = '验证码发送失败,请重新发送';
				}
			}
		}else{
			$ajax['code'] = '305';
			$ajax['tips'] = '验证码发送失败,请重新发送';
		}
		echo json_encode($ajax);
	}
	
	
	
	
	/**
	 * session('loginBoxShow'.$companyid) 未登录弹框是否显示字段 1、显示；2、不显示；
	 * oY8S1jlhZIH7ESMOqrokcyyaifTI
	 * 登陆http://new.lightpen.cn/index.php?g=Wap&m=Login&a=login&companyid=12&openid=oY8S1jlhZIH7ESMOqrokcyyaifTI&token=zsgfkv1401100810
	 */
	public function login(){
        $companyid = 1;
	   if(IS_POST) {  // 登录
           //是否存在该公司
           $mobile = $this->_post('moblie');
           $loginPassword = $this->_post('password');
           $list = M('member_mobile_code')->where(array('companyid' => $companyid, 'mobile' => $mobile, 'isused' => '2'))->find();
           if ($list) {
               $nowTime = time();
               if ($loginPassword == $list['code']) {
                   $set['usetime'] = $nowTime;
                   $set['isused'] = 1;
                   M('member_mobile_code')->where(array('companyid' => $companyid, 'mobile' => $mobile))->save($set);
                   $info = M()->table('tp_member_wechat_info AS wechat')
                       ->join(array('LEFT JOIN tp_member_register_info AS register ON wechat.mid=register.id'))
                       ->where(array('wechat.companyid'=>$companyid,'register.mobile'=>$mobile))
                       ->field('wechat.mid,wechat.openid,wechat.nickname,wechat.language,wechat.headimgurl,register.isregister,register.name')->find();
                   if ($info) {
                       session('mid'.$companyid,$info['mid']);
                       session('mname'.$companyid,$info['name']);
                       session('wname'.$companyid,$info['nickname']);
                       session('whead'.$companyid,$info['headimgurl']);
                       session('openid'.$companyid,$info['openid']);
                       $ajax['code'] = 200;
                       $ajax['tips'] = 'success';
                   } else {
                       $agent = $_SERVER['HTTP_USER_AGENT'];
                       if(strpos($agent,"MicroMessenger")) {
                           $data['isregister'] = 1;
                           $data['registertypetag'] = 1;
                           $data['mobile'] = $mobile;
                           $data['ischeckmoblietrue'] = 1;
                           $data['companyid'] = 1;
                           $data['createtime'] = time();
                           $data2['mid'] = M("member_register_info")->add($data);
                           $data2['createtime'] = time();
                           $data2['companyid'] = 1;
                           M("member_wechat_info")->add($data2);
                           session('mid'.$companyid,$data2['mid']);
                           $ajax['code'] = 200;
                           $ajax['tips'] = 'success';
                       }else{
                           $ajax['code'] = 300;
                           $ajax['tips'] = '首次请先在微信环境中登陆';
                       }
                   }
               } else {
                   $ajax['code'] = 300;
                   $ajax['tips'] = '抱歉，您输入的验证码有误！';
               }
           } else {
               $ajax['code'] = 300;
               $ajax['tips'] = '验证码错误！';
           }
           echo json_encode($ajax);
       }else{
	       if(session('mid'.$companyid) && session('mname'.$companyid)){
               // 如果登录过，直接跳走
               if(session('historyUrl') && (strpos(session('historyUrl'),'login') ===FALSE) && (strpos(session('historyUrl'),'companyid='.$companyid) !==FALSE)){
                   $this->redirect(session('historyUrl'));
               }else{
                   $this->redirect(U('Member/center',array('companyid'=>$companyid)));
               }
           }else{
               //登录
               $this->display();
           }
       }
	}
	
	/**
	 * 获取验证码
	 */
	public function mobileCode(){
		$moblie = $this->_post('mobile');  // 接收手机号
		$companyid = $this->_get('companyid','intval');  // 公司id
		$registerCount = M('member_register_info')->where(array('companyid'=>$companyid,'moblie'=>$moblie,"isregister"=>1))->count(); // 查询手机号是否存在
		if($moblie && $companyid && $registerCount>0){
			$codeReturn = M('member_mobile_code')->field('code')->where(array('companyid'=>$companyid,'mobile'=>$moblie,'useendtime'=>array('gt',time()),'isused'=>'2'))->find();  // 查询对应的验证码
			$data['code'] = '300';
			$data['tips'] = '验证码获取失败！';
			if($codeReturn){
				//发送
				$sendData = $this->sendSms($moblie,$codeReturn['code'].'为您的会员登录验证码,30分钟内有效。',$companyid);  // sendSms():发送验证码的接口;
				if($sendData['code'] == 200){
					$data['code'] = '200';
					$data['tips'] ='验证码短信已发送到你的手机上，有效时间为30分钟，请及时查收。';
				}
			}else{
				$code['companyid'] = $companyid;
				$code['mobile'] = $moblie;
				$code['code'] = substr(time(),-6);  // 用时间生成验证码（当前时间戳的前4位）
				$code['useendtime'] = time()+1800;  // 过期时间
				$code['isused'] = '2';
				$code['createtime'] = time();
				$insterData = M('member_mobile_code')->add($code);
				if($insterData){
					//发送
					$sendData = $this->sendSms($moblie,$code['code'].'为您的会员登录验证码,30分钟内有效。',$companyid);
					if($sendData['code'] == 200){
						$data['code'] = '200';
						$data['tips'] ='验证码短信已发送到你的手机上，有效时间为30分钟，请及时查收。';
					}
				}
			}
		}else{
			$data['code'] = '300';
			$data['tips'] = '抱歉，您的输入的手机号还未注册，请注册！';
		}
		echo json_encode($data);
	}
	

    /* 临时跳转 */
	public function register(){
		$this->redirect(U('Login/login',array('companyid'=>$this->_get('companyid'))));
	}
	/**
	 * 安全退出
	 */
	public function quit(){
		session(null);
		$data['code'] = 200;
		$data['tips'] = '安全退出！';
		echo json_encode($data);
	}
}
?>