<?php
/**
 * 公众号管理
 * Enter description here ...
 * @author yongfei.zhao
 *
 */
class WechatAction extends UserAction{
	
	public  $wechatsModel;
	private $companyModel;
	private $companyShopsModel;
	public  $companyGroupModel;
	public  $functionModel;
	public  $uid;
	public  $gid;
	private $companyid;
	public function __construct(){
		parent::__construct();
		$this->wechatsModel 	= D('wechats');
		$this->companyModel = M('company');
		$this->companyShopsModel = M('company_shops');
		$this->companyGroupModel = M('company_group');
		$this->uid = session('uid');
		$this->gid = session('gid');
		$this->companyid = session('cid');
	}
	/**
	 * 公众号列表(non-PHPdoc)
	 * @see UserAction::index()
	 */
	public function lists(){
		$this->assign('Permissionsid',5);
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'我的API接入','url'=>'','rel'=>'','target'=>'')));
		$wechats = D('Wechats')->getCompanyWechatss(array('companyid'=>$this->companyid));
		$this->assign('list',$wechats);
		$this->display();
	}
	/**
	 * 
	 * ajax获取联系人
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-6-28
	 */
	public function ajaxAE(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍候重试';
		if(IS_POST){
			$companyid = M('company')->where(array('id'=>$this->companyid))->getField('companyid');
			$aename= M('check_customer_info')->where(array('id'=>$companyid))->getField('aeuser');
			$aemobile = M('sell_staffs')->where(array('name'=>$aename))->getField('mobile');
			if($aemobile && $aename){
				$ajax['code'] = '200';
				$ajax['aemobile'] = $aemobile;
				$ajax['aename'] = $aename;
			}else{
				$ajax['msg'] = '未分配，请致电'.C('servicenumber').'催促分配';
			}
		}
		echo json_encode($ajax);
	}
	/**
	 * 导出关注具体时间
	 */
	public function exportSubscribeLink(){
		$list = M()->table('tp_wechats as wechats')->join(array('tp_wechat_member_subscribe_link as link on link.token = wechats.token'))->where(array('wechats.companyid'=>$this->companyid,'link.type'=>1))->field('wechats.wxname,link.subscribetime')->order('link.subscribetime DESC')->select();
		$newList = array('0'=>array('0'=>'','1'=>'','2'=>''));
		$nowDay = '';
		$i = 0;
		$num = 1;
		$tempDay = '';
		if($list){
			foreach ($list as $lKey=>$lVal){
				$nowDay = format_time($lVal['subscribetime'],'ymd');
				if($tempDay == $nowDay){
					$newList[$i][2] = $num++;
				}else{
					$i++;
					$newList[$i][0] = $lVal['wxname'];
					$newList[$i][1] = $nowDay;
					$newList[$i][2] = $num;
					$num = 1;
					$tempDay = $nowDay;
				}
			}
		}
		$headArr=array('公众号','日期','获取关注数');
		$this->getExcel('公众号每日关注统计',$headArr,$newList);
	}
	
	/**
	 * 
	 * 绑定wechat 平台
	 * 
	 * @author Lando<806728685@qq.com>
	 * @since  2015-8-13
	 */
	public function bindWechat(){
	    $step1Url = 'https://api.weixin.qq.com/cgi-bin/component/api_component_token';
	    $step1Data = array('component_appid'=>'wx3377298b1b790dd4','component_appsecret'=>'0c79e1fa963cd80cc0be99b20a18faeb','component_verify_ticket'=>'');
	    $step1Return = http_post($step1Url, $step1Data);
	}
	/**
	 * 微信公众号管理
	 * @author   Tomas<416369046@qq.com>
	 * @since  2016-1-4
	 */
	public function manage(){
		//$this->checkCompanyScrm5Permissions(6,TRUE,5,'&wechatid='.$this->_request('wechatid'));
		$this->check_url = 'wechatmanage';
		$this->wechatManage();
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'微信会员','url'=>'','rel'=>'','target'=>'')));
		$where['wechat.companyid'] = $this->companyid;
		$openid = $this->_request('openid');
		if($openid){
			$where['wechat.openid'] = $openid;
		}
		//微信昵称
		$wechatNickname = $this->_request('nickname');
		if($wechatNickname){
			$where['wechat.nickname'] = array('like','%'.$wechatNickname.'%');
		}
		$this->assign('wechatNickname',$wechatNickname);
		//微信性别
		$gender = $this->_request('gender');
		if($gender){
			$where['wechat.gender'] = array('like','%'.$gender.'%');
		}
		$this->assign('gender',$gender);
		//自动标签搜索是否关注
		$subscribetype = $this->_request('subscribetype');
		 if($subscribetype == '1'){
			$where['register.subscribetype'] = '1';
		}elseif ($subscribetype == '2'){
			$where['register.subscribetype'] = '2';
		}elseif ($subscribetype == '0'){
			$where['register.subscribetype'] = '0';
		}
		$this->assign('subscribetype',$subscribetype);
		//是否注册
		$isregister = $this->_request('isregister');
		if($isregister == 1){
			$where['register.isregister'] = 1;
		}elseif ($isregister == 2){
			$where['register.isregister'] = array('in','0,2');
		}
		$this->assign('isregister',$isregister);
		//注册会员手机
		$mobile = $this->_request('mobile');
		if($mobile){
			$where['register.mobile'] = array('like','%'.$mobile.'%');
		}
		$this->assign('mobile',$mobile);
		//会员备注
		$remark = $this->_request('remark');
		if($remark){
			$where['register.note'] = array('like','%'.$remark.'%');
		}
		$this->assign('remark',$remark);
		$memberCount = M()->table('tp_member_wechat_info AS wechat')->join(array('LEFT JOIN tp_member_register_info AS register ON register.id = wechat.mid','LEFT JOIN tp_quick_response_code AS quick ON quick.content = wechat.scene_id'))->where($where)->count();
		$Page=new NewPage($memberCount,15);
		$memberList = M()->table('tp_member_wechat_info AS wechat')
            ->join(array('LEFT JOIN tp_member_register_info AS register ON register.id = wechat.mid','LEFT JOIN tp_quick_response_code AS quick ON quick.content = wechat.scene_id'))->where($where)
		->field('wechat.mid,register.mobile,wechat.subscribe,register.subscribetype,register.note,register.isregister,wechat.nickname,wechat.headimgurl,wechat.gender,wechat.openid,quick.name as qname')
		->order('register.id DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('page',$Page->show());
		$this->assign('count',$memberCount);
		$this->assign('memberList',$memberList);
		$this->display();
	}
	/**
	 * 
	 * ajax获取会员信息
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-5-23
	 */
	public function ajaxMemberInfo(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍候重试';
		$ajax['html'] = '';
		if(IS_POST){
			$where['openid'] = $this->_post('openid');
			$where['companyid'] = $this->companyid;
			$memberInfo = M('member_wechat_info')->where($where)->field('nickname,headimgurl,openid,language,city,country,province,updatetime')->find();
			if($memberInfo){
				$ajax['code'] = '200';
				$ajax['msg'] = '';
				$ajax['html'] .= '<div class="mod-header clearfix"><h4 class="fl">微信资料</h4>';
				$ajax['html'] .= '<h5 class="fr">上次微信资料更新时间：<span>'.format_time($memberInfo['updatetime'],'ymdhi').'</span></h5>';
				$ajax['html'] .= '</div><div class="mod-body"><div class="content"><div class="vip-details clearfix mb-15">';
				if($memberInfo['headimgurl']){
					$ajax['html'] .= '<dt><img class="vip-details-img fl hidden" src="'.$memberInfo['headimgurl'].'"></dt>';
				}else{
					$ajax['html'] .= '<img class="vip-details-img fl hidden" src="./Tpl/User5/default/common/img/user-logo.jpg">';
				}
				$ajax['html'] .= '<div class="details-box fl"><div class="group">';
				$ajax['html'] .= '<h6 class="inline span1 text-left group-Q">微信昵称：</h6><h6 class="inline span3 ">'.$memberInfo['nickname'].'</h6></div>';
				$ajax['html'] .= '<div class="group"><h6 class="inline span1 text-left group-Q">OPENID：</h6><h6 class="inline span3 ">'.$memberInfo['openid'].'</h6></div>';
				$ajax['html'] .= '<div class="group"><h6 class="inline span1 text-left group-Q">语言：</h6>';
				if($memberInfo['language'] == 'zh_TW'){
					$ajax['html'] .= '<h6 class="inline span3 ">繁体</h6></div>';
				}elseif($memberInfo['language'] == 'en'){
					$ajax['html'] .= '<h6 class="inline span3 ">英语</h6></div>';
				}else{
					$ajax['html'] .= '<h6 class="inline span3 ">简体</h6></div>';
				}
				$areaModel = M('area');
				$country = $areaModel->where(array('id'=>$memberInfo['country']))->getField('name');
				$province = $areaModel->where(array('id'=>$memberInfo['province']))->getField('name');
				$city = $areaModel->where(array('id'=>$memberInfo['city']))->getField('name');
				$ajax['html'] .= '<div class="group"><h6 class="inline span1 text-left group-Q">地区：</h6><h6 class="inline span3 ">'.$country.'  '.$province.'  '.$city.'</h6></div></div></div>';
				$memberInfo['subInfo'] = M('history_wechat_request')->where(array('FromUserName'=>$memberInfo['openid'],'Event'=>array('in','subscribe,unsubscribe')))->order('CreateTime desc')->field('EventKey,Event,CreateTime')->select();
				if($memberInfo['subInfo']){
					$ajax['html'] .= '<div class="vip-details-table mb-15"><table class="table type-1 w-auto mb-15">';
					$ajax['html'] .= '<thead><tr><th>关注状态</th><th>状态更新时间</th><th>关注渠道</th></tr></thead><tbody>';
					foreach($memberInfo['subInfo'] as $key=>$val){
						$id = ltrim($val['EventKey'],'qrscene_');
						$subname = M('quick_response_code')->where(array('content'=>$id,'type'=>'1'))->getField('name');
						if($val['Event'] == 'subscribe'){
							$ajax['html'] .= '<tr><td>关注</td>';
						}elseif($val['Event'] == 'unsubscribe'){
							$ajax['html'] .= '<tr><td>取关</td>';
						}else{
							$ajax['html'] .= '<tr><td>-</td>';
						}
						if($val['CreateTime']){
							$ajax['html'] .= '<td>'.format_time($val['CreateTime'],'ymdhis').'</td>';
						}else{
							$ajax['html'] .= '<td>-</td>';
						}
						if($subname){
							$ajax['html'] .= '<td>'.$subname.'</td></tr>';
						}else{
							$ajax['html'] .= '<td>-</td></tr>';
						}
					}
					$ajax['html'] .= '</tbody></table></div>';
				}
				$ajax['html'] .= '<div class="group text-center"><a class="btn-big btn-white js-ok">关 闭</a></div></div></div>';
			}
		}
		echo json_encode($ajax);
	}
	public function test(){
		//echo microtime();exit;
		$a = M('diymen_class')->where(array('num'=>''))->field('id')->select();
		foreach ($a as $key=>$val){
			$data['num'] = substr(time(),-7).substr(microtime(),3,3).rand(100,999);
			M('diymen_class')->where(array('id'=>$val['id']))->save($data);
		}
	}
	
}
?>