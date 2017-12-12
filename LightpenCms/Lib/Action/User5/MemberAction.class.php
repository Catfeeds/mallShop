<?php
/**
 * 会员资料管理
 * Enter description here ...
 * @author yongfei.zhao
 */
class MemberAction extends UserAction{
	
	private $uid;
	
	private $companyid;
	
	private $shopsid;
	
	private $memberRegisterInfoModel;
	
	private $memberWechatInfoModel;
	
	private $memberCardInfoModel;
	public function __construct(){
		parent::__construct();
		$this->memberRegisterInfoModel 	= M('member_register_info');
		$this->memberWechatInfoModel 	= M('member_wechat_info');
		$this->memberCardInfoModel 	= M('member_card_info');
		$this->uid 		= session('uid');
		$this->companyid = session('cid');
		$this->shopsid = session('shopsid');
		$this->limit = 15;
	}
	/**
	 * 会员资料管理
	 * Enter description here ...
	 */
	public function myClients(){
		$this->check_url = 'membermyclients';
		//$this->memberTag = $this->memberTags();
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'我的会员','url'=>'','rel'=>'','target'=>''),array('name'=>'我的注册会员','url'=>'','rel'=>'','target'=>'')));
		$search['ranks'] = M('member_card_rank')->where(array('companyid'=>$this->companyid))->field('id,name')->order('number ASC,createtime DESC')->select();
		//$search['alltags'] = M('member_group')->where(array('companyid'=>$this->companyid))->field('id,name')->order('createtime desc')->select();
		$this->assign('search',$search);
		$where['register.companyid'] = $this->companyid;
		//只有注册才显示
		$this->isregister = $where['register.isregister'] = '1';
		//手机
		$mobile = $this->_request('mobile');
		if($mobile){
			$where['register.mobile'] = array('like','%'.$mobile.'%');
		}
		$this->assign('mobile',$mobile);
		//姓名
		$name = $this->_request('name');
		if($name){
			$where['register.name'] = array('like','%'.$name.'%');
		}
		$this->assign('name',$name);

		//收货地址
		$allshopaddress = $this->_request('allshopaddress');
		if($allshopaddress){
			$where['register.allshopaddress'] = array('like','%'.$allshopaddress.'%');
		}
		$this->assign('allshopaddress',$allshopaddress);
		//生日
		$birthday1 = $this->_request('birthday1');
		$birthday2 = $this->_request('birthday2');
		if($birthday1 && $birthday2 && $birthday1 <= $birthday2){
			$where['register.birthday'] = array('between',array($birthday1,$birthday2));
		}elseif ($birthday1){
			$where['register.birthday'] = array('gt',$birthday1);
		}elseif ($birthday2){
			$where['register.birthday'] = array(array('elt',$birthday2),array('neq','0000-00-00'),'and');
		}
		$this->assign('birthday1',$this->_request('birthday1'));
		$this->assign('birthday2',$this->_request('birthday2'));
		//注册日期
		$createtime1 = strtotime($this->_request('createtime1'));
		$createtime2 = strtotime($this->_request('createtime2'));
		if($createtime1 && $createtime2 && $createtime1 <= $createtime2){
			$where['register.createtime'] = array('between',array($createtime1,$createtime2));
		}elseif ($createtime1){
			$where['register.createtime'] = array('gt',$createtime1);
		}elseif ($createtime2){
			$where['register.createtime'] = array('lt',$createtime2);
		}
		$this->assign('createtime1',$this->_request('createtime1'));
		$this->assign('createtime2',$this->_request('createtime2'));
		$memberCount = M()->table('tp_member_register_info AS register')->join(array('LEFT JOIN tp_member_card_info AS card ON register.id = card.mid','LEFT JOIN tp_member_card_rank AS rank ON card.rankid = rank.id'))->where($where)->count();
		$Page=new NewPage($memberCount,15);
		$memberList = M()->table('tp_member_register_info AS register')->join(array('LEFT JOIN tp_member_card_info AS card ON register.id = card.mid','LEFT JOIN tp_member_card_rank AS rank ON card.rankid = rank.id'))->where($where)
		            ->field('register.id,register.name,register.accountbalance,register.gender,register.mobile,register.totalintegration,register.membertagsid,register.subscribetype,register.totalexperiencevalue,rank.name AS rankname')
					->order('register.createtime DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('page',$Page->diyshow());
		$this->assign('memberList',$memberList);
		//$this->reportScrmCompanyConsoleInfo = M('report_scrm_company_console_info')->where(array('companyid'=>$this->companyid))->field('membernum,thismonthaddmembernum,updatetimech')->find();
		$membercustomfield = M('company')->where(array('id'=>$this->companyid))->getField('membercustomfield');
		if($membercustomfield){
			$membercustomfield = json_decode($membercustomfield,true);
			$this->assign('membercustomfield',$membercustomfield);
		}
		$this->shopList = M('company_shops')->where(array('companyid'=>$this->companyid))->field('id,shopname')->select();
		$this->display();
	}
	/**
	 * 
	 * 会员资料自定义字段
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2017-2-20
	 */
	public function ajaxmembercustomfield(){
		$ajax['code'] = 'warn';
		$ajax['msg'] = '网络繁忙,请稍候重试';
		if(IS_POST){
			$date['membercustomfield'] = htmlspecialchars_decode($this->_post('date'));
			$where['companyid'] = $this->companyid;
			$date['updatetime'] = time();
			$result = M('company')->where(array('id'=>$this->companyid))->save($date);
			if($result){
				$ajax['code'] = 'success';
				$ajax['msg'] = '操作成功';
			}else{
				$ajax['code'] = 'error';
				$ajax['msg'] = '操作失败';
			}
		}
		echo json_encode($ajax);
	}
	/**
	 * 获得会员信息
	 */
	public function memberInfo(){
		$this->memberTag = $this->memberTags();
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'我的会员','url'=>'','rel'=>'','target'=>''),array('name'=>'我的注册会员','url'=>U('Member/myClients'),'rel'=>'','target'=>''),array('name'=>'会员详情','url'=>'','rel'=>'','target'=>'')));
		$search['ranks'] = M('member_card_rank')->where(array('companyid'=>$this->companyid))->field('id,name')->order('number ASC,createtime DESC')->select();
		//$search['alltags'] = M('member_group')->where(array('companyid'=>$this->companyid))->field('id,name')->order('createtime desc')->select();
		$search['wechatid'] = M('wechats')->where(array('companyid'=>$this->companyid))->getField('id');
		$this->assign('search',$search);
		$memberRegisterWhere['register.id'] = $this->_get('id');
		$memberRegisterWhere['register.companyid'] = $this->companyid;
		$memberInfo = $this->memberRegisterInfoModel->table('tp_member_register_info AS register')->join(array('LEFT JOIN tp_member_card_info AS card ON register.id = card.mid','LEFT JOIN tp_member_wechat_info AS wechat ON register.id = wechat.mid','LEFT JOIN tp_member_card_rank AS rank ON card.rankid = rank.id'))->where($memberRegisterWhere)
			->field('register.boundshopid,register.customfieldinfo,register.accountbalance,register.totalspendingtag,register.agetag,register.spendingfrequencytag,register.howlongspendingtag,register.usevouchersfrequencytag,register.howlongusevoucherstag,register.note,register.constellationtag,register.registertypetag,register.id,register.name,register.gender,register.mobile,register.totalintegration,register.totalexperiencevalue,register.birthday,register.note,register.membertagsid,register.createtime,wechat.openid,wechat.nickname,wechat.wechatmessagetime,rank.name AS rankname,register.subscribetype')->find();
		if($memberInfo['nickname'] && $memberInfo['name']){
			$memberInfo['newname'] = $memberInfo['nickname'].' '.$memberInfo['name'];
		}elseif($memberInfo['nickname']){
			$memberInfo['newname'] = $memberInfo['nickname'];
		}elseif($memberInfo['name']){
			$memberInfo['newname'] = $memberInfo['name'];
		}else{
			$memberInfo['newname'] = '匿名';
		}
		$age = '-';
		if($memberInfo['birthday'] != '0000-00-00'){
			$age = get_age($memberInfo['birthday']);
			if($age){
				$age = $age.'岁';
			}else{
				$age = '-';
			}
		}
		if($memberInfo['customfieldinfo']){
			$memberInfo['customfield'] = json_decode($memberInfo['customfieldinfo'],true);
		}
		$memberInfo['age'] = $age;
		$this->assign('memberInfo',$memberInfo);
		$shopAddresscount = M('member_shop_address')->where(array('companyid'=>$this->companyid,'mid'=>$this->_get('id')))->count();
		$Page = New NewPage($shopAddresscount,15);
		$shopAddress = M('member_shop_address')->where(array('companyid'=>$this->companyid,'mid'=>$this->_get('id')))->field('id,name,mobile,areacode,province,city,district,address,isdefault')->limit($Page->firstRow.','.$Page->listRows)->order('createtime desc')->select();
		$this->assign('page',$Page->diyshow());
		$this->assign('shopAddress',$shopAddress);
		$this->paytype = $paytype = $this->_get("paytype")? $this->_get("paytype"):'1';
		$membercustomfield = M('company')->where(array('id'=>$this->companyid))->getField('membercustomfield');
		if($membercustomfield){
			$membercustomfield = json_decode($membercustomfield,true);
			$this->assign('membercustomfield',$membercustomfield);
			//$this->assign('membercustomfieldceil',ceil((count($membercustomfield)-1)/3));
		}
		$this->spendingTypeAsa = $this->spendingTypeAsa();
		$this->spendingpayTypeAsa = $this->spendingpayTypeAsa();
		$this->spendingRechargeTypeAsa = $this->spendingRechargeTypeAsa();
		$this->integralTypeAsa = $this->integralTypeAsa();
		$this->shopList = M('company_shops')->where(array('companyid'=>$this->companyid))->field('id,shopname')->select();
		$this->display();
	}
	/**
	 * 
	 * ajax添加/修改地址
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-10-27
	 */
	public function ajaxSaveAddress(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍候重试';
		if(IS_POST){
			$data['companyid'] = $where['companyid'] = $this->companyid;
			$data['mid'] = $where['mid'] = $mid = $this->_post('mid');
			$id = $this->_post('id');
			$data['name'] = $this->_post('name');
			$data['mobile'] = $this->_post('mobile');
			$data['address'] = $this->_post('address');
			$data['isdefault'] = $this->_post('isdefault');
			$data['areacode'] = $this->_post('country').','.$this->_post('province').','.$this->_post('city');
			$data['updatetime'] = time();
			$data['province'] = M('area_member')->where(array('id'=>$this->_post('country'),'parentid'=>'0'))->getField('name');
			$data['city'] = M('area_member')->where(array('id'=>$this->_post('province'),'parentid'=>$this->_post('country')))->getField('name');
			$data['district'] = M('area_member')->where(array('id'=>$this->_post('city'),'parentid'=>$this->_post('province')))->getField('name');
			M()->startTrans();
			if($data['isdefault'] == '1'){
				$count = M('member_shop_address')->where($where)->count();
				if($count>0){
					$result = M('member_shop_address')->where($where)->save(array('isdefault'=>'2','updatetime'=>time()));
				}else{
					$result = '1';
				}
			}else{
				$result = '1';
			}
			if($id){
				$address = M('member_shop_address')->where(array('companyid'=>$this->companyid,'id'=>$id))->save($data);
			}else{
				//$data['id'] = guidNow();
				$data['createtime'] = $data['updatetime'];
				$address = M('member_shop_address')->add($data);
			}
			$addressList = M('member_shop_address')->where($where)->field('name,mobile,province,city,district,address,isdefault')->order('createtime')->select();
			if($addressList){
				$mdata['address'] = '';
				$mdata['allshopaddress'] = '';
				foreach ($addressList as $key=>$val){
					if($val['isdefault'] == '1'){
						$mdata['address'] = $val['name'].' '.$val['mobile'].' '.$val['province'].$val['city'].$val['district'].$val['address'];
					}
					$mdata['allshopaddress'] .= $val['name'].' '.$val['mobile'].' '.$val['province'].$val['city'].$val['district'].$val['address'].';';
				}
			}
			$mdata['updatetime'] = $data['updatetime'];
			$member = M('member_register_info')->where(array('companyid'=>$this->companyid,'id'=>$mid))->save($mdata);
			if($result && $address && $member){
				M()->commit();
				$ajax['code'] = '200';
				$ajax['msg'] = '操作成功';
			}else{
				M()->rollback();
				$ajax['msg'] = '操作失败';
			}
		}
		echo json_encode($ajax);
	}
	/**
	 * 
	 * ajax删除地址
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-10-27
	 */
	public function ajaxDelAddress(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍候重试';
		if(IS_POST){
			$where['companyid'] = $this->companyid;
			$where['id'] = $this->_post('id');
			M()->startTrans();
			$result = M('member_shop_address')->where($where)->getField('mid');
			$del = M('member_shop_address')->where($where)->delete();
			$addressList = M('member_shop_address')->where(array('companyid'=>$this->companyid,'mid'=>$result))->field('name,mobile,province,city,district,address,isdefault')->order('createtime')->select();
			if($addressList){
				$mdata['address'] = '';
				$mdata['allshopaddress'] = '';
				foreach ($addressList as $key=>$val){
					if($val['isdefault'] == '1'){
						$mdata['address'] = $val['name'].' '.$val['mobile'].' '.$val['province'].$val['city'].$val['district'].$val['address'];
					}
					$mdata['allshopaddress'] .= $val['name'].' '.$val['mobile'].' '.$val['province'].$val['city'].$val['district'].$val['address'].';';
				}
			}
			$mdata['updatetime'] = time();
			$member = M('member_register_info')->where(array('companyid'=>$this->companyid,'id'=>$result))->save($mdata);
			if($result && $del && $member){
				M()->commit();
				$ajax['code'] = '200';
				$ajax['msg'] = '操作成功';
			}else{
				M()->rollback();
				$ajax['msg'] = '操作失败';
			}
		}
		echo json_encode($ajax);
	}
	/**
	 * 
	 * ajax查询会员收货地址
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-10-26
	 */
	public function ajaxSelectAddress(){
		$ajax['code'] = '300';
		$ajax['countrys'] = '<option value="">请选择省份</option>';
		$ajax['provinces'] = '<option value="">请选择城市</option>';
		$ajax['citys'] = '<option value="">请选择区/县</option>';
		if(IS_POST){
			$id = $this->_post('id');
			$areacode = explode(',', $this->_post('areacode'));
			$info['countrys'] = M('area_member')->where(array('parentid'=>0,'isshow'=>1))->field('id,name')->order('sort')->select();
			if($id){
				if($info['countrys']){
					$ajax['code'] = '200';
					foreach($info['countrys'] as $ckey=>$cval){
						$ajax['countrys'] .= '<option value="'.$cval['id'].'"';
						if($cval['id'] == $areacode['0']){
							$ajax['countrys'] .= ' selected="selected" ';
						}
						$ajax['countrys'] .= '>'.$cval['name'].'</option>';
					}
					$info['provinces'] = M('area_member')->where(array('isshow'=>1,'parentid'=>$areacode['0']))->field('id,name')->select();
					if($info['provinces']){
						foreach($info['provinces'] as $pkey=>$pval){
							$ajax['provinces'] .= '<option value="'.$pval['id'].'"';
							if($pval['id'] == $areacode['1']){
								$ajax['provinces'] .= ' selected="selected" ';
							}
							$ajax['provinces'] .= '>'.$pval['name'].'</option>';
						}
						$info['citys'] = M('area_member')->where(array('isshow'=>1,'parentid'=>$areacode['1']))->field('id,name')->select();
						if($info['citys']){
							foreach($info['citys'] as $cikey=>$cival){
								$ajax['citys'] .= '<option value="'.$cival['id'].'"';
								if($cival['id'] == $areacode['2']){
									$ajax['citys'] .= ' selected="selected" ';
								}
								$ajax['citys'] .= '>'.$cival['name'].'</option>';
							}
						}
					}
				}
			}else{
				if($info['countrys']){
					$ajax['code'] = '200';
					foreach($info['countrys'] as $ckey=>$cval){
						$ajax['countrys'] .= '<option value="'.$cval['id'].'">'.$cval['name'].'</option>';
					}
				}
			}
		}
		echo json_encode($ajax);
	}
	/**
	 *
	 * 异步加载市
	 *
	 * @author Leo<1251868177@qq.com>
	 * @since  2015-11-6
	 */
	public function ajaxAddressCity(){
		$returnData['code'] = 300;
		$returnData['tips'] = '抱歉，服务器繁忙，请稍后重试';
		$parentid = $this->_post('parentid');
		if($parentid){
			$addressList = M('area_member')->where(array('isshow'=>'1','parentid'=>$parentid))->order('sort ASC')->field('id,name')->select();
			if($addressList){
				$string = '<option value="">请选择城市</option>';
				foreach ($addressList as $key=>$val){
					$string .= '<option value="'.$val['id'].'">'.$val['name'].'</option>';
				}
			}
			$returnData['code'] = 200;
			$returnData['tips'] = $string;
		}
		echo json_encode($returnData);
	}
	/**
	 *
	 * 异步加载区
	 *
	 * @author Leo<1251868177@qq.com>
	 * @since  2015-11-6
	 */
	public function ajaxAddressDistrict(){
		$returnData['code'] = 300;
		$returnData['tips'] = '抱歉，服务器繁忙，请稍后重试';
		$parentid = $this->_post('parentid');
		if($parentid){
			$addressList = M('area_member')->where(array('isshow'=>'1','parentid'=>$parentid))->order('sort ASC')->field('id,name')->select();
			if($addressList){
				$string = '<option value="">请选择区/县</option>';
				foreach ($addressList as $key=>$val){
					$string .= '<option value="'.$val['id'].'">'.$val['name'].'</option>';
				}
				$string .= '</select>';
				$returnData['code'] = 200;
				$returnData['tips'] = $string;
			}
		}
		echo json_encode($returnData);
	}
	/**
	 * 
	 * ajax 修改会员信息
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-10-25
	 */
	public function ajaxSaveMember(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍候重试';
		if(IS_POST){
			$where['companyid'] = $this->companyid;
			$where['id'] = $this->_post('mid');
			$data['name'] = $this->_post('name');
			$data['mobile'] = $this->_post('mobile');
			$data['gender'] = $this->_post('gender');
			$data['birthday'] = $this->_post('birthday');
			$data['boundshopid'] = $this->_post('boundshopid');
			$data['updatetime'] = time();
			$data['customfieldinfo'] = htmlspecialchars_decode($this->_post('customfieldInfo'));
			$data['constellationtag'] = $this->constellation($data['birthday']);
			$data['agetag'] = $this->agetag($data['birthday']);
			$count = M('member_register_info')->where(array('mobile'=>$data['mobile'],'companyid'=>$this->companyid,'id'=>array('neq',$where['id'])))->count();
			if($count>0){
				$ajax['msg'] = '该手机号已被使用，请更换';
			}else{
				M()->startTrans();
				if($where['id']){
					$info = M('member_register_info')->where($where)->field('constellationtag,agetag,gender,issend100expint,issend100expvoucher')->find();
					if($info['constellationtag'] == '0' || $info['constellationtag']){
						$this->memberTagCount($this->companyid, array(array('name'=>'constellation','before'=>$info['constellationtag'],'after'=>$data['constellationtag'])));
					}
					if($info['agetag'] == '0' || $info['agetag']){
						$this->memberTagCount($this->companyid, array(array('name'=>'age','before'=>$info['agetag'],'after'=>$data['agetag'])));
					}
					if($info['gender'] == '0' || $info['gender']){
						$this->memberTagCount($this->companyid, array(array('name'=>'gender','before'=>$info['gender'],'after'=>$data['gender'])));
					}
					if($data['name']){
					    $num += '1';
					}
					if($data['mobile']){
					    $num += '1';
					}
					if($data['gender']){
					    $num += '1';
					}
					if($data['birthday']){
					    $num += '1';
					}
					if($data['customfieldinfo']){
						$customfieldinfo = json_decode($data['customfieldinfo'],true);
						foreach($customfieldinfo as $key=>$val){
							if($val['value']!=''){
								$num += '1';
							}
							unset($val);
						}
					}
					$fieldcount = 0;
					$membercustomfield = M('company')->where(array('id'=>$this->companyid))->getField('membercustomfield');
					if($membercustomfield){
						$membercustomfield = json_decode($membercustomfield,true);
						$fieldcount = count($membercustomfield);
					}
					$data['percent'] = number_format(($num/(4+$fieldcount)),2)*100;
					if($data['percent']==100 && $info['issend100expint']!=1){
					    $issend100expint = 1; // 标识赠送完善资料赠送的积分
					}
					if($data['percent']==100 && $info['issend100expvoucher']!=1){
						$issend100expvoucher = 1;
					}
					$result = M('member_register_info')->where($where)->save($data);
					if($result){
						M()->commit();
						if($issend100expint == '1'){
						    $this->changeMemberBusinessSCRM5(array('cid'=>$this->companyid,'uid'=>$this->uid,'mid'=>$where['id'],'type'=>'101'));
						} 
						if($issend100expvoucher == '1'){
							$whereA['companyid'] = $this->companyid;
							$whereA['type'] = 4;
							$whereA['starttime'] = array('lt',time());
							$whereA['endtime'] = array('gt',time());
							$whereA['issuspend'] = array('neq',1);
							$whereA['status'] = array('neq',1);
							$voucherid = M('member_marketing_activities_scrm')->where($whereA)->field("id,tagjsoninfo")->select();
							if($voucherid){
							    foreach($voucherid as $vVal){
							    	$tag = json_decode($vVal['tagjsoninfo'],true);
							    	if($tag){
							    		foreach ($tag as $tKey=>$tVal){
							    			if($tVal){
							    				if($tKey == 'rankid'){ //等级标签
							    					$memberWhere['info.rankid'] =  array('in',substr($tVal,1,-1));;
							    				}elseif($tKey == 'membertagsid'){ //自定义标签
							    					if($tVal && $tVal != ','){
							    						$wheretags = '';
							    						$arrtags = explode(',', $tVal);
							    						foreach ($arrtags as $atKey=>$atVal){
							    							if($atVal){ $wheretags .=" (member.membertagsid like '%,".$atVal.",%') AND"; }
							    							unset($atVal);
							    						}
							    						if($wheretags){
							    							$wheretags = substr($wheretags, 0,-3);
							    							$memberWhere['_string'] = $wheretags;
							    						}
							    					}
							    				}else{//  其他
							    					$memberWhere['member.'.$tKey] =  array('in',substr($tVal,1,-1));;
							    				}
							    			}
							    			unset($tVal,$wheretags,$arrtags);
							    		}
								    	//  查询符合的会员
							    	}
							    	$memberWhere['member.companyid'] = $this->companyid;
							    	$memberWhere['member.id'] = $where['id'];
							    	$memberCount = M()->table('tp_member_register_info as member')->join(array('left join tp_member_card_info as info on info.mid=member.id'))->where($memberWhere)->count();
							    	if($memberCount>0){
							    		$register = $this->sendMemberVouchersSCRM5($vVal['id'], $where['id'],$this->companyid,'9');
							    	}
							    }
							}
						}
						$ajax['code'] = '200';
						$ajax['msg'] = '编辑成功';
					}else{
						M()->rollback();
						$ajax['msg'] = '编辑失败';
					}
				}else{
					$ajax['msg'] = '编辑失败';
				}
			}
		}
		echo json_encode($ajax);
	}
	/**
	 *
	 * ajax 修改备注
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-10-25
	 */
	public function ajaxSaveNote(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍候重试';
		if(IS_POST){
			$note = $this->_post('note');
			$id = $this->_post('mid');
			if($id){
				$where['companyid'] = $this->companyid;
				$where['id'] = $id;
				$data['note'] = $note;
				$data['updatetime'] = time();
				$result = M('member_register_info')->where($where)->save($data);
				if($result){
					$ajax['code'] = '200';
					$ajax['msg'] = '编辑成功';
				}else{
					$ajax['msg'] = '编辑失败';
				}
			}else{
				$ajax['msg'] = '编辑失败';
			}
		}
		echo json_encode($ajax);
	}
	/**
	 * 
	 * ajax创建标签
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-10-26
	 */
	public function ajaxAddMemberTag(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍候重试';
		if(IS_POST){
			$data['name'] = $this->_post('membertag');
			$count = M('member_group')->where(array('companyid'=>$this->companyid,'name'=>$data['name']))->count();
			if($count>0){
				$ajax['msg'] = '标签名称重复';
			}else{
				$data['id'] = guidNow();
				$data['companyid'] = $this->companyid;
				$data['createtime'] = $data['updatetime'] = time();
				$result = M('member_group')->add($data);
				if($result){
					$ajax['code'] = '200';
					$ajax['html'] = '';
					$ajax['html'] .= '<tr>';
					$ajax['html'] .= '<td>'.$data['name'].'</td>';
					$ajax['html'] .= '<td><a href="javascript:void(0);" class="tips js-select-tag" data-id="'.$data['id'].'">选取</a></td>';
					$ajax['html'] .= '</tr>';
				}else{
					$ajax['msg'] = '创建失败';
				}
			}
		}
		echo json_encode($ajax);
	}
	/**
	 * 
	 * ajax筛选标签
 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-10-25
	 */
	public function ajaxSelectMemberTags(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍候重试';
		if(IS_POST){
			$name = $this->_post('tagname');
			if($name){
				$where['name'] = array('like','%'.$name.'%');
			}
			$where['companyid'] = $this->companyid;
			$list = M('member_group')->where($where)->field('id,name')->order('createtime desc')->select();
			if($list){
				$ajax['code'] = '200';
				$ajax['html'] = '';
				foreach($list as $key=>$satVal){
					$ajax['html'] .= '<tr>';
					$ajax['html'] .= '<td>'.$satVal['name'].'</td>';
					$ajax['html'] .= '<td><a href="javascript:void(0);" class="tips js-select-tag" data-id="'.$satVal['id'].'">选取</a></td>';
					$ajax['html'] .= '</tr>';
				}
			}else{
				$ajax['code'] = '200';
				$ajax['html'] = '<tr class="text-center not-hover"><td colspan="2">暂无</td></tr>';
			}
		}
		echo json_encode($ajax);
	}
	/**
	 * 异步添加会员tags
	 */
	public function ajaxAddMemberTags(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍候重试';
		if(IS_POST){
			$mid = $this->_post('mid');
			$membertagsid = $this->_post('membertagsid');
			$allmembertags = explode(',', $membertagsid);
			$tagid = substr($membertagsid,1,-1);
			M()->startTrans();
			if($mid && $allmembertags){
					$memberList = M('member_register_info')->where(array('companyid'=>$this->companyid,'id'=>array('in',$mid)))->field('id,membertagsid')->select();
					if($memberList){
						$b = 0;
						foreach ($memberList as $mlKey=>$mlVal){
							$this->memberTagCount($this->companyid, array(array('name'=>'membertagsid','before'=>$mlVal['membertagsid'],'after'=>','.$membertagsid.',')));
							$result = M('member_register_info')->where(array('companyid'=>$this->companyid,'id'=>$mlVal['id']))->save(array('membertagsid'=>$membertagsid,'updatetime'=>time()));
							$count = M('member_group_link')->where(array('mid'=>$mlVal['id'],'groupid'=>array('in',$tagid)))->count();
							if($count>0){
								$del = M('member_group_link')->where(array('mid'=>$mlVal['id'],'groupid'=>array('in',$tagid)))->delete();
							}else{
								$del = '1';
							}
							if($del&&$result){
								$a = 0;
								foreach ($allmembertags as $aKey=>$aVal){
									if($aVal){
										$data['id'] = guidNow();
										$data['groupid'] = $aVal;
										$data['mid'] = $mlVal['id'];
										$add = M('member_group_link')->add($data);
										if($add){
											$a+=1;
										}
									}
									unset($data,$aVal,$add);
								}
								if(($a+2) == count($allmembertags)){
									$b+=1;
								}
							}
						}
						if($b == count($memberList)){
							M()->commit();
							$ajax['code'] = '200';
							$ajax['msg'] = '操作成功';
						}else{
							M()->rollback();
							$ajax['msg'] = '操作失败';
						}
					}else{
						$ajax['msg'] = '操作失败';
					}
			}else{
				$ajax['msg'] = '操作失败';
			}
		}
		echo json_encode($ajax);
	}
	/**
	 * 
	 * 创建导出任务
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-10-28
	 */
	public function memberexcel(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍候重试';
		if(IS_POST){
			$where['register.companyid'] = $this->companyid;
			//只有注册才显示
			$where['register.isregister'] = '1';
			//手机
			$mobile = $this->_request('mobile');
			if($mobile){
				$where['register.mobile'] = array('like','%'.$mobile.'%');
			}
			//累计积分
			$totalexperiencevalue1 = $this->_request('totalexperiencevalue1');
			$totalexperiencevalue2 = $this->_request('totalexperiencevalue2');
			if($totalexperiencevalue1 && $totalexperiencevalue2 && $totalexperiencevalue1 <= $totalexperiencevalue2){
				$where['register.totalexperiencevalue'] = array('between',array($totalexperiencevalue1,$totalexperiencevalue2));
			}elseif ($totalexperiencevalue1){
				$where['register.totalexperiencevalue'] = array('egt',$totalexperiencevalue1);
			}elseif ($totalexperiencevalue2){
				$where['register.totalexperiencevalue'] = array('elt',$totalexperiencevalue2);
			}
			//姓名
			$name = $this->_request('name');
			if($name){
				$where['register.name'] = array('like','%'.$name.'%');
			}
			//可用积分
			$totalintegration1 = $this->_request('totalintegration1');
			$totalintegration2 = $this->_request('totalintegration2');
			if($totalintegration1 && $totalintegration2 && $totalintegration1 <= $totalintegration2){
				$where['register.totalintegration'] = array('between',array($totalintegration1,$totalintegration2));
			}elseif ($totalintegration1){
				$where['register.totalintegration'] = array('egt',$totalintegration1);
			}elseif ($totalintegration2){
				$where['register.totalintegration'] = array('elt',$totalintegration2);
			}
			//收货地址
			$allshopaddress = $this->_request('allshopaddress');
			if($allshopaddress){
				$where['register.allshopaddress'] = array('like','%'.$allshopaddress.'%');
			}
			//店内NOTE
			$note = $this->_request('note');
			if($note){
				$where['register.note'] = array('like','%'.$note.'%');
			}
			//生日
			$birthday1 = $this->_request('birthday1');
			$birthday2 = $this->_request('birthday2');
			if($birthday1 && $birthday2 && $birthday1 <= $birthday2){
				$where['register.birthday'] = array('between',array($birthday1,$birthday2));
			}elseif ($birthday1){
				$where['register.birthday'] = array('gt',$birthday1);
			}elseif ($birthday2){
				$where['register.birthday'] = array(array('elt',$birthday2),array('neq','0000-00-00'),'and');
			}
			//注册日期
			$createtime1 = strtotime($this->_request('createtime1'));
			$createtime2 = strtotime($this->_request('createtime2'));
			if($createtime1 && $createtime2 && $createtime1 <= $createtime2){
				$where['register.createtime'] = array('between',array($createtime1,$createtime2));
			}elseif ($createtime1){
				$where['register.createtime'] = array('gt',$createtime1);
			}elseif ($createtime2){
				$where['register.createtime'] = array('lt',$createtime2);
			}
			/********************标签搜索************************/
			//会员来源
			$registertypetag = $this->_request('registertypetag');
			if($registertypetag){
				$where['register.registertypetag'] = array('in',substr($registertypetag,1,-1));
			}
			//等级
			$rankid = $this->_request('rankid');
			if($rankid){
				$where['card.rankid'] = array('in',substr($rankid,1,-1));
			}
			//性别
			$gender = $this->_request('gender');
			if($gender){
				$where['register.gender'] = array('in',substr($gender,1,-1));
			}
			//年龄
			$agetag = $this->_request('agetag');
			if($agetag){
				$where['register.agetag'] = array('in',substr($agetag,1,-1));
			}
			//星座
			$constellationtag = $this->_request('constellationtag');
			if($constellationtag){
				$where['register.constellationtag'] = array('in',substr($constellationtag,1,-1));
			}
			//微信关注
			$subscribetype = $this->_request('subscribetype');
			if($subscribetype){
				$where['register.subscribetype'] = array('in',substr($subscribetype,1,-1));
			}
			//多久未消费
			$howlongspendingtag = $this->_request('howlongspendingtag');
			if($howlongspendingtag){
				$where['register.howlongspendingtag'] = array('in',substr($howlongspendingtag,1,-1));
			}
			//年消费频次
			$spendingfrequencytag = $this->_request('spendingfrequencytag');
			if($spendingfrequencytag){
				$where['register.spendingfrequencytag'] = array('in',substr($spendingfrequencytag,1,-1));
			}
			//累积消费
			$totalspendingtag = $this->_request('totalspendingtag');
			if($totalspendingtag){
				$where['register.totalspendingtag'] = array('in',substr($totalspendingtag,1,-1));
			}
			// 多久使用卡券
			$howlongusevoucherstag = $this->_request('howlongusevoucherstag');
			if($howlongusevoucherstag){
				$where['register.howlongusevoucherstag'] = array('in',substr($howlongusevoucherstag,1,-1));
			}
			// 使用卡券频次
			$usevouchersfrequencytag = $this->_request('usevouchersfrequencytag');
			if($usevouchersfrequencytag){
				$where['register.usevouchersfrequencytag'] = array('in',substr($usevouchersfrequencytag,1,-1));
			}
			//自定义标签
			$membertagsid = $this->_request('membertagsid');
			if($membertagsid && $membertagsid != ','){
				$wheretags = '';
				$arrtags = explode(',', $membertagsid);
				foreach ($arrtags as $atKey=>$atVal){
					if($atVal){
						$wheretags .=" (register.membertagsid like '%,".$atVal.",%') OR";
					}
				}
				if($wheretags){
					$wheretags = substr($wheretags, 0,-3);
					$where['_string'] = $wheretags;
				}
			}
			$memberList = M()->table('tp_member_register_info AS register')->join(array('LEFT JOIN tp_member_card_info AS card ON register.id = card.mid','LEFT JOIN tp_member_card_rank AS rank ON card.rankid = rank.id'))->where($where)
			->field('register.id,register.name,register.gender,register.mobile,register.totalintegration,register.membertagsid,register.subscribetype,register.note,register.totalexperiencevalue,rank.name AS rankname')
			->order('register.createtime DESC')->select();
			$data['rule'] = M()->getLastSql();
			$data['type'] = '4';
			$data['companyid'] = $this->companyid;
			$id = guidNow();
			$data['name'] = '我的会员资料'.'_'.$id;
			$data['remarkname'] = $id;
			$data['updatetime'] = $data['createtime'] = time();
			$addSuc = M('export_task')->add($data);
			if($addSuc){
				$ajax['code'] = 200;
			}else{
				$ajax['msg'] = '任务创建失败';
			}
		}
		echo json_encode($ajax);
	}
	/**
	 * 所有会员添加标签
	 */
	public function ajaxAddMembersTags(){
		$memberGroupOptionInfo = '';
		$where['register.companyid'] = $this->companyid;
		//MID
		$id = $this->_request('id');
		if($id){
			$where['register.id'] = $id;
		}
		//建档日期
		$createtime1 = strtotime($this->_request('createtime1'));
		$createtime2 = strtotime($this->_request('createtime2'));
		if ($createtime1 && $createtime1 && $createtime1 <= $createtime2){
			$where['register.createtime'] = array('between',array($createtime1,$createtime2));
		}elseif ($createtime1){
			$where['register.createtime'] = array('egt',$createtime1);
		}elseif ($createtime2){
			$where['register.createtime'] = array('elt',$createtime2);
		}
		//姓名
		$name = $this->_request('name');
		if($name){
			$where['register.name'] = array('like','%'.$name.'%');
		}
		//手机
		$mobile = $this->_request('mobile');
		if($mobile){
			$where['register.mobile'] = array('like','%'.$mobile.'%');
		}
		//邮箱
		$email = $this->_request('email');
		if($email){
			$where['register.email'] = array('like','%'.$email.'%');
		}
		//性别
		$gender = $this->_request('gender');
		if($gender){
			$where['register.gender'] = $gender;
		}
		//年龄
		$age1 = $this->_request('age1');
		$age2 = $this->_request('age2');
		if($age1 && $age2 &&$age1 <= $age2){
			$where['register.birthday'] = array('between',array((date('Y')-$age2).date('-m-d'),(date('Y')-$age1).date('-m-d')));
		}elseif($age1){
			$where['register.birthday'] = array(array('elt',(date('Y')-$age1).date('-m-d')),array('neq','0000-00-00'),'and');
		}elseif($age2){
			$where['register.birthday'] = array(array('egt',(date('Y')-$age2).date('-m-d')),array('neq','0000-00-00'),'and');
		}
		//生日
		$birthday1 = $this->_request('birthday1');
		$birthday2 = $this->_request('birthday2');
		if($birthday1 && $birthday2 && $birthday1 <= $birthday2){
			$where['register.birthday'] = array('between',array($birthday1,$birthday2));
		}elseif($birthday1){
			$where['register.birthday'] = array('egt',$birthday1);
		}elseif($birthday2){
			$where['register.birthday'] = array(array('elt',$birthday2),array('neq','0000-00-00'),'and');
		}
		//店内NOTE
		$note = $this->_request('note');
		if($note){
			$where['register.note'] = array('like','%'.$note.'%');
		}
		//收货地址
		$allshopaddress = $this->_request('allshopaddress');
		if($allshopaddress){
			$where['register.allshopaddress'] = array('like','%'.$allshopaddress.'%');
		}
		//是否开卡
		$isactivatecard = $this->_request('isactivatecard');
		if($isactivatecard == '1'){
			$where['card.id'] = array('gt',0);
		}elseif($isactivatecard == '2'){
			$where['card.id'] = array('elt',0);
		}
		//会员卡号
		$cardnum = $this->_request('cardnum');
		$cardnumprefix = M('member_card_info_set')->where(array('companyid'=>$this->companyid))->getField('cardnumprefix');
		if($cardnum){
			$where['card.cardnum'] = str_replace($cardnumprefix,'',$cardnum);
		}
		//开卡时间
		$activatetime1 = strtotime($this->_request('activatetime1'));
		$activatetime2 = strtotime($this->_request('activatetime2'));
		if ($activatetime1 && $activatetime2 && $activatetime1 <= $activatetime2){
			$where['card.createtime'] = array('between',array($activatetime1,$activatetime2));
		}elseif ($activatetime1){
			$where['card.createtime'] = array('egt',$activatetime1);
		}elseif ($activatetime2){
			$where['card.createtime'] = array('elt',$activatetime2);
		}
		//绑定实体卡号
		$entitynumber = $this->_request('entitynumber');
		if($entitynumber){
			$where['register.entitynumber'] = array('like','%'.$entitynumber.'%');
		}
		//绑定时间
		$bindingtime1 = strtotime($this->_request('bindingtime1'));
		$bindingtime2 = strtotime($this->_request('bindingtime2'));
		if($bindingtime1 && $bindingtime2 && $bindingtime1 <= $bindingtime2){
			$where['register.bindingtime'] = array('between',array($bindingtime1,$bindingtime2));
		}elseif ($bindingtime1){
			$where['register.bindingtime'] = array('egt',$bindingtime1);
		}elseif ($bindingtime2){
			$where['register.bindingtime'] = array('elt',$bindingtime2);
		}
		//经验值
		$totalexperiencevalue1 = $this->_request('totalexperiencevalue1');
		$totalexperiencevalue2 = $this->_request('totalexperiencevalue2');
		if($totalexperiencevalue1 && $totalexperiencevalue2 && $totalexperiencevalue1 <= $totalexperiencevalue2){
			$where['register.totalexperiencevalue'] = array('between',array($totalexperiencevalue1,$totalexperiencevalue2));
		}elseif($totalexperiencevalue1){
			$where['register.totalexperiencevalue'] = array('egt',$totalexperiencevalue1);
		}elseif($totalexperiencevalue2){
			$where['register.totalexperiencevalue'] = array('elt',$totalexperiencevalue2);
		}
		//积分
		$totalintegration1 = $this->_request('totalintegration1');
		$totalintegration2 = $this->_request('totalintegration2');
		if($totalintegration1 && $totalintegration2 && $totalintegration1 <= $totalintegration2){
			$where['register.totalintegration'] = array('between',array($totalintegration1,$totalintegration2));
		}elseif($totalintegration1){
			$where['register.totalintegration'] = array('egt',$totalintegration1);
		}elseif($totalintegration2){
			$where['register.totalintegration'] = array('elt',$totalintegration2);
		}
		//积点
		$totaljidian1 = $this->_request('totaljidian1');
		$totaljidian2 = $this->_request('totaljidian2');
		if($totaljidian1 && $totaljidian2 && $totaljidian1 <= $totaljidian2){
			$where['register.totaljidian'] = array('between',array($totaljidian1,$totaljidian2));
		}elseif($totaljidian1){
			$where['register.totaljidian'] = array('egt',$totaljidian1);
		}elseif($totaljidian2){
			$where['register.totaljidian'] = array('elt',$totaljidian2);
		}
		//卡内余额
		$accountbalance1 = $this->_request('accountbalance1');
		$accountbalance2 = $this->_request('accountbalance2');
		if($accountbalance1 && $accountbalance2 && $accountbalance1 <= $accountbalance2){
			$where['register.accountbalance'] = array('between',array($accountbalance1,$accountbalance2));
		}elseif($accountbalance1){
			$where['register.accountbalance'] = array('egt',$accountbalance1);
		}elseif($accountbalance2){
			$where['register.accountbalance'] = array('elt',$accountbalance2);
		}
		//微信OPENID
		$wechatOpenid = $this->_request('wechatOpenid');
		if ($wechatOpenid){
			$where['wechat.openid'] = $wechatOpenid;
		}
		//微信昵称
		$wechatNickname = $this->_request('wechatNickname');
		if ($wechatNickname){
			$where['wechat.nickname'] = array('like','%'.$wechatNickname.'%');
		}
		//语言
		$wechatLang = $this->_request('wechatLang');
		if($wechatLang){
			$where['wechat.language'] = $wechatLang;
		}
		//国家、省、市
		$wechatCountry = $this->_request('wechatCountry');
		if ($wechatCountry){
			$where['wechat.country'] = $wechatCountry;
		}
		$wechatProvince = $this->_request('wechatProvince');
		$search['provinces'] = '';
		if ($wechatProvince){
			$where['wechat.province'] = $wechatProvince;
		}
		$wechatCity = $this->_request('wechatCity');
		$search['citys'] = '';
		if($wechatCity){
			$where['wechat.city'] = $wechatCity;
		}
		//关注渠道
		$scene_id = $this->_request('scene_id');
		if($scene_id){
			$where['wechat.scene_id'] = $scene_id;
		}
		//自动标签搜索是否关注
		$subscribetype = $this->_request('subscribetype');
		if($subscribetype == 1){
			$where['register.subscribetype'] = 1;
		}elseif ($subscribetype == 2){
			$where['register.subscribetype'] = array('in','0,2');
		}
		//自动标签搜索是否绑定会员卡
		$isentitynumber = $this->_request('isentitynumber');
		if ($isentitynumber == 1){
			$where['register.entitynumber'] = array('neq','');
		}elseif ($isentitynumber == 2){
			$where['register.entitynumber'] = array('eq','');
		}
		//等级标签
		$rankid = $this->_request('rankid');
		if($rankid && $rankid != ','){
			$where['card.rankid'] = array('in',substr($rankid, 1,-1));
		}
		//自定义标签
		$tagsid = $this->_request('tagsid');
		if($tagsid && $tagsid != ','){
			$wheretags = '';
			$arrtags = explode(',', $tagsid);
			foreach ($arrtags as $atKey=>$atVal){
				if($atVal){
					$wheretags .=" (register.membertagsid like '%,".$atVal.",%') AND";
				}
			}
			if($wheretags){
				$wheretags = substr($wheretags, 0,-3);
				$where['_string'] = $wheretags;
			}
		}
		//最近消费日期
		$lastspendingdays1 = strtotime($this->_request('lastspendingdays1'));
		$lastspendingdays2 = strtotime($this->_request('lastspendingdays2'));
		if($lastspendingdays1 && $lastspendingdays2 && $lastspendingdays1 <= $lastspendingdays2){
			$where['register.lastspendingtime'] = array('between',array($lastspendingdays1,$lastspendingdays2));
		}elseif($lastspendingdays1){
			$where['register.lastspendingtime'] = array('elt',$lastspendingdays1);
		}elseif($lastspendingdays2){
			$where['register.lastspendingtime'] = array('egt',$lastspendingdays2);
		}
		//月消费频次
		$spendingfrequency1 = $this->_request('spendingfrequency1');
		$spendingfrequency2 = $this->_request('spendingfrequency2');
		if($spendingfrequency1 && $spendingfrequency2 && $spendingfrequency1 <= $spendingfrequency2){
			$where['register.spendingfrequency'] = array('between',array($spendingfrequency1,$spendingfrequency2));
		}elseif($spendingfrequency1){
			$where['register.spendingfrequency'] = array('egt',$spendingfrequency1);
		}elseif($spendingfrequency2){
			$where['register.spendingfrequency'] = array('elt',$spendingfrequency2);
		}
		//月消费贡献
		$totalspending1 = $this->_request('totalspending1');
		$totalspending2 = $this->_request('totalspending2');
		if($totalspending1 && $totalspending2 && $totalspending1 <= $totalspending2){
			$where['register.totalspending'] = array('between',array($totalspending1,$totalspending2));
		}elseif($totalspending1){
			$where['register.totalspending'] = array('egt',$totalspending1);
		}elseif($totalspending2){
			$where['register.totalspending'] = array('elt',$totalspending2);
		}
		//官方客服
		$sendsystemmessage = $this->_request('sendsystemmessage');
		if($sendsystemmessage){
			$where['register.mobile'] = array('neq','');
		}
		//微信客服
		$sendwechat24hr = $this->_request('sendwechat24hr');
		if($sendwechat24hr){
			$where['wechat.wechatmessagetime'] = array('egt',strtotime('-2 day'));
		}
		//手机短信
		$sendsms = $this->_request('sendsms');
		if($sendsms){
			$where['register.mobile'] = array('neq','');
		}
		$memberList = M()->table('tp_member_register_info AS register')->join(array('LEFT JOIN tp_member_card_info AS card ON register.id = card.mid','LEFT JOIN tp_member_wechat_info AS wechat ON register.id = wechat.mid','LEFT JOIN tp_member_card_rank AS rank ON card.rankid = rank.id'))->where($where)->field('register.id AS mid')->select();
		$allmembertagsid = $this->_post('allmembertagsid');
		$allmembertags = explode(',', $allmembertagsid);
		$insternum = 0;
		if($memberList&&$allmembertags){
			$membertagsid = $insternum = $inster = $ishave = 0;
			foreach ($memberList as $mlKey=>$mlVal){
				foreach ($allmembertags as $aKey=>$aVal){
					if($aVal){
						$ishave = M('member_group_link')->where(array('mid'=>$mlVal['mid'],'groupid'=>$aVal))->count();
						if($ishave<1){
							$inster = M('member_group_link')->add(array('mid'=>$mlVal['mid'],'groupid'=>$aVal));
							if($inster){
								$insternum++;
							}
						}else{
							$insternum++;
						}
						$membertagsid = M('member_register_info')->where(array('id'=>$mlVal['mid'],'companyid'=>$this->companyid))->getField('membertagsid');
						if($membertagsid && strpos($membertagsid, ','.$aVal.',') === false){
							M('member_register_info')->where(array('id'=>$mlVal['mid'],'companyid'=>$this->companyid))->save(array('membertagsid'=>$membertagsid.$aVal.',','updatetime'=>time()));
						}
						$ishave = $inster = 0;
						$membertagsid = '';
					}
				}
			}
		}
		if($insternum){
			$this->success(L('AddedSucessful'));
		}else{
			$this->error(L('ServerBusyPrompt'));
		}
	}
	/**
	 * 会员中心查看更多交易记录
	 */
	public function memberOrderLists(){
		if(IS_POST){
			$page = $this->_post("page");
			$mid = $this->_post("mid");
			$type = $this->_post("type");
			if($type==1){
				$paylists = M("member_spending")->where(array('mid'=>$mid,'paytype'=>array("in","1,2,3,4,5")))
				->field("id,createtime,orderid,linkorderid,type,shopname,status,paytype,spendingamount,spendingamount2")
				->limit($page*$this->limit,$this->limit)->order('createtime desc')->select();
				if($paylists){
					$wheres['companyid'] = $this->companyid;
					foreach($paylists as $key=>$val){
						$action = $array = $dbname = $field = $wheres['orderid'] = '';
						if($val['type'] == '107'||$val['type'] == '111'){     //门店收银(风助手，拉卡拉)
							$paylists[$key]['url'] = U('ShopCashier/info',array('id'=>$val['id']));
						}elseif($val['type'] == '115'){   //手机点单
							$paylists[$key]['url'] = 'javascript:void(0);';
						}elseif($val['type'] == '112'&&$val['rechargetype'] == '2'){
							$paylists[$key]['url'] = U('StoredValue/orders',array('orderid'=>$val['linkorderid']));
						}else{
							if($val['type'] == '106'){     //闪惠
								$action = 'ShanHui/orderList';
								$dbname = 'shanhui_order';
								$wheres['orderid'] = $val['linkorderid'];
								$field = 'shopid,orderid';
							}elseif($val['type'] == '110'){      //eshop
								$dbname = 'mall_order_info';
								$wheres['orderid'] = $val['linkorderid'];
								$field = 'goodtype as ordertype,id';
							}elseif($val['type'] == '113'){      //手机预订
								$action = 'MobileBook/orderList';
								$dbname = 'mobile_book_order';
								$wheres['orderid'] = $val['linkorderid'];
								$field = 'bookshopid as sid,orderid';
							}elseif($val['type'] == '114'){      //风外卖
								$action = 'TakeOut/orderList';
								$dbname = 'takeout_order';
								$wheres['orderid'] = $val['linkorderid'];
								$field = 'setid,orderid,orderstate as type';
							}elseif($val['type'] == '118'){      //手机预订（SPA）
								$action = 'SpaMobileBookOrder/orderList';
								$dbname = 'spa_mobile_book_order';
								$wheres['orderid'] = $val['linkorderid'];
								$field = 'bookshopid as sid,orderid';
							}
							$array = M($dbname)->where($wheres)->field($field)->find();
							if($array && $val['type'] == '110'){
								$action = 'MallOrder/info';
							}
							$paylists[$key]['url'] = U($action,$array);
						}
						unset($val,$wheres,$array,$dbname,$field);
					}
				}
			}elseif($type==2){
				$paylists = M("member_spending")->where(array('mid'=>$mid,'rechargetype'=>array("in","1,2,3,4")))
				->field("id,createtime,orderid,linkorderid,type,shopname,status,username,rechargetype,spendingamount,spendingamount2")
				->limit($page*$this->limit,$this->limit)->order('createtime desc')->select();
				foreach($paylists as $key=>$val){
					$action = $array = $dbname = $field = $wheres['orderid'] = '';
					if($val['rechargetype'] == '2'){     //门店收银(风助手，拉卡拉)
						$paylists[$key]['url'] = U('StoredValue/orders',array('orderid'=>$val['linkorderid']));
					}else{
						$paylists[$key]['url'] = 'javascript:void(0);';
					}
					unset($val,$wheres,$array,$dbname,$field);
				}
			}elseif($type==3){
				$paylists = M("member_integral")->where(array('mid'=>$mid))
				->field("id,orderid,linkorderid,type,shopname,status,username,createtime,integralnum,note")
				->limit($page*$this->limit,$this->limit)->order('createtime desc')->select();
				if($paylists){
					$wheres['companyid'] = $this->companyid;
					foreach($paylists as $key=>$val){
						$action = $array = $dbname = $field = $wheres['orderid'] = '';
						if($val['type'] == '107'||$val['type'] == '111'){     //门店收银(风助手，拉卡拉)
							$paylists[$key]['url'] = U('ShopCashier/info',array('id'=>$val['id']));
						}elseif($val['type'] == '115'||$val['type'] == '101'||$val['type'] == '102'||$val['type'] == '103'||$val['type'] == '104'||$val['type'] == '105'||$val['type'] == '108'||$val['type'] == '109'||$val['type'] == '116'||$val['type'] == '117'||$val['type'] == '201'||$val['type'] == '202'||$val['type'] == '203'||$val['type'] == '204'||$val['type'] == '205'||$val['type'] == '301'){   //手机点单
							$paylists[$key]['url'] = 'javascript:void(0);';
						}else{
							if($val['type'] == '106'){     //闪惠
								$action = 'ShanHui/orderList';
								$dbname = 'shanhui_order';
								$wheres['orderid'] = $val['linkorderid'];
								$field = 'shopid,orderid';
							}elseif($val['type'] == '110'){      //eshop
								$dbname = 'mall_order_info';
								$wheres['orderid'] = $val['linkorderid'];
								$field = 'goodtype as ordertype,id';
							}elseif($val['type'] == '113'){      //手机预订
								$action = 'MobileBook/orderList';
								$dbname = 'mobile_book_order';
								$wheres['orderid'] = $val['linkorderid'];
								$field = 'bookshopid as sid,orderid';
							}elseif($val['type'] == '114'){      //风外卖
								$action = 'TakeOut/orderList';
								$dbname = 'takeout_order';
								$wheres['orderid'] = $val['linkorderid'];
								$field = 'setid,orderid,orderstate as type';
							}elseif($val['type'] == '118'){      //手机预订（SPA）
								$action = 'SpaMobileBookOrder/orderList';
								$dbname = 'spa_mobile_book_order';
								$wheres['orderid'] = $val['linkorderid'];
								$field = 'bookshopid as sid,orderid';
							}
							$array = M($dbname)->where($wheres)->field($field)->find();
							if($array && $val['type'] == '110'){
								$action = 'MallOrder/info';
							}
							$paylists[$key]['url'] = U($action,$array);
						}
						unset($val,$wheres,$array,$dbname,$field);
					}
				}
			}
			$spendingTypeAsa = $this->spendingTypeAsa();
			$spendingRechargeTypeAsa = $this->spendingRechargeTypeAsa();
			$integralTypeAsa = $this->integralTypeAsa();
			if($paylists){
				$str = '';
				foreach($paylists as $val){
					$str .= '<tr>';
					$str .= '<td>'.format_time($val['createtime'],'ymdhi').'</td>';
					$str .= '<td><a href="'.$val['url'].'" class="tips">'.($val['linkorderid']?$val['linkorderid']:'-').'</a></td>';
					if($type==1){
						$str .= '<td>'.$spendingTypeAsa[$val['type']].'</td>';
					}elseif($type==2){
						$str .= '<td>'.$val['orderid'].'</td>';
						$str .= '<td>'.$spendingRechargeTypeAsa[$val['rechargetype']].'</td>';
					}elseif($type==3){
						$str .= '<td>'.$val['orderid'].'</td>';
						$str .= '<td>'.$integralTypeAsa[$val['type']].'</td>';
					}
					if($type==1||$type==2){
						$str .= '<td>'.$val['spendingamount'].'</td>';
					}elseif($type==3){
						$str .='<td>';
						if($val['type']<=117){
                            $str .='<span class="text-red">+'.$val['integralnum'].'</span>';
                        }else{
                            $str .='<span class="text-green">-'.$val['integralnum'].'</span>';
                        }
                        $str .='</td>';
					}
					if($type==1){
						$str .= '<td>'.$val['shopname'].'</td>';
					}elseif($type==2){
						$str .= '<td>'.$val['username'].'</td>';
					}elseif($type==3){
						$str .= '<td>'.$val['note'].'</td>';
						$str .= '<td>'.$val['username'].'</td>';
					}
					$str .= '</tr>';
				}
				if(count($paylists)==15){
					$ajax['code']=300;
					$ajax['msg']=$str;
				}else{
					$ajax['code']=200;
					$ajax['msg']=$str;
				}
			}else{
				$ajax['code']=300;
				$ajax['msg']='';
			}
			$ajax['page'] = $page+1;
			echo json_encode($ajax);
		}
	}
}
?>