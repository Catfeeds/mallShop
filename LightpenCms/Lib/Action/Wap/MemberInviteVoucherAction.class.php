<?php
/**
 * 邀请赠礼的会员中心页面
 * Enter description here ...
 * @author asa
 */
class MemberInviteVoucherAction extends WapBaseAction{
	
	public function __construct(){
		parent::__construct();
		$this->mid = session('mid'.session('wapcid'));
		$this->companyid = session('wapcid');
		//检查公司配置  
	}
	/**
	 * 邀请赠礼的会员中心页面
	 * @author Asa<asa@renlaifeng.cn>
	 * @since  2016-12-7
	 */
	public function index(){
		$this->setPageTitle(array('title'=>'邀请送礼'));
		if($this->mid){
			if($this->_get("iswang")==2){
				$where['endtime'] = array("elt",time());
				$where['companyid'] = $this->companyid;
				$where['type'] = 10;
				$info = M("member_marketing_activities_scrm")->where($where)->order("createtime desc")->find();
				$this->iswang = 2;
			}else{
				$where['starttime'] = array("elt",time());
				$where['endtime'] = array("egt",time());
				$where['companyid'] = $this->companyid;
				$where['type'] = 10;
				$voucherAct = M("member_marketing_activities_scrm")->where($where)->count();
				if($voucherAct<=0){
					$this->iswang = 1;
				}else{
					$info = M("member_marketing_activities_scrm")->where($where)->order("createtime desc")->find();
				}
			}
			if($info){
				$info['voucherinfo'] = M("member_marketing_activities_scrm_link_voucher")->where(array("parentid"=>$info['id'],'isdel'=>array("neq",1)))->order("createtime desc")->select();
				if($info['voucherinfo']){
					foreach ($info['voucherinfo'] as $key => $val){
							
						if($val['isdel']!=1){
							$val['vouchername'] = M("member_marketing_activities_voucher_info")->where(array("companyid"=>$this->companyid,'id'=>$val['voucherid']))->getField("title");
						}
							
						$infowhere['companyid'] = $this->companyid;
						if($val['vouchertype'] == '1'||$val['vouchertype'] == '2'||$val['vouchertype'] == '3'||$val['vouchertype'] == '4'){
							$infowhere['id'] = $val['voucherid'];
							$otherinfo = M('member_marketing_activities_voucher_info')->where($infowhere)->field('israndom,parvalue,maxparvalue,title,usestarttime,useendtime,usetimedeferred,usetimetype,usetimelimittype,usetimelimitset')->find();
							if($val['vouchertype']==4){
								if($otherinfo['israndom']==1) $otherinfo['money'] = $otherinfo['minparvalue'];
								else  $otherinfo['money'] = $otherinfo['parvalue'];
								$val['money1'] = floor($otherinfo['money']);
								$val['money2'] = substr(strval(number_format($otherinfo['money']-$val['money1'],2)),2);
							}
						}elseif($val['vouchertype'] == '5'||$val['vouchertype'] == '6'||$val['vouchertype'] == '7'||$val['vouchertype'] == '8'){
							$infowhere['id'] = $val['voucherid'];
							$otherinfo = M('mall_goods')->where($infowhere)->field('title,usetimelimittype,usetimelimitset')->find();
						}else{
							$otherinfo = '';
						}
						$timeinfo['title']=$otherinfo['title'];
						if($otherinfo['usetimelimittype']==1){
							$timeinfo['usetimetype'] = 1;
							$timeinfo['usetimedeferred'] = $otherinfo['usetimelimitset'];
						}elseif($otherinfo['usetimelimittype']==3){
							$timeinfo['usetimetype'] = 3;
							$timeinfo['usestarttime'] = strtotime($otherinfo['usetimelimitset']);
						}elseif($otherinfo['usetimelimittype']==2){
							$timeinfo['usetimetype'] = 2;
							$testtime = json_decode($otherinfo['usetimelimitset'],true);
							$timeinfo['usestarttime'] = strtotime($testtime['usebegintime']);
							$timeinfo['useendtime'] = strtotime($testtime['useendtime']);
						}
						$info['voucherinfo'][$key] = array_merge($timeinfo,$val);
						unset($timeinfo);
					}
				}
			}
			$info['voucherinfo2'] = json_decode($info['inviteraward'],'true');
			$this->vouchertype = $this->getvouchertype();
			//dump($info['voucherinfo2']);
			//$this->gxhyze = M()->table("tp_member_marketing_activities_scrm_link_voucher_list mmas")
			//->join("tp_member_register_info mri on mri.id = mmas.mid")->where(array("mmas.companyid"=>$this->companyid,"mmas.actid"=>$val['id'],"mri.isregister"=>1))->count();
			$this->yixiaofei = $yixiaofei = count(M()->table("tp_member_marketing_activities_scrm_link_voucher_list mmas")
			->join("tp_member_register_info mri on mri.id = mmas.mid")
			->join("tp_member_spending ms on ms.mid = mri.id")
			->where(array("mmas.companyid"=>$this->companyid,"mmas.parentmid"=>$this->mid,"mmas.actid"=>$info['id'],"mri.isregister"=>1,"mmas.ismoney"=>1))
			->group("ms.mid")->field("mmas.id,mri.moblie,ms.spendingamount")->select());
			
			$info['yixiaofeilist'] = M()->table("tp_member_marketing_activities_scrm_link_voucher_list mmas")
			->join("tp_member_register_info mri on mri.id = mmas.mid")
			->join("tp_member_spending ms on ms.mid = mri.id")
			->where(array("mmas.type"=>1,"mmas.companyid"=>$this->companyid,"mmas.parentmid"=>$this->mid,"mmas.actid"=>$info['id'],"mri.isregister"=>1))
			->group("ms.mid")->field("mmas.id,mri.moblie,ms.spendingamount,mmas.ismoney")->select();
			$asanum = 0;
			$asamin2 = 100000;
			foreach ($info['voucherinfo2'] as $value){
				if($value['cansendmaxnum']<=$yixiaofei){
					$asanum++;
				}else{
					$asamin1 = $value['cansendmaxnum'] - $yixiaofei;
					if($asamin2>$asamin1){
						$asamin2 = $asamin1;
						$info['voucherinfochae2']= $value['vouchername'];
						$info['voucherinfochae3']= $value['vouchertype'];
						$info['voucherinfochae4']= (int)($yixiaofei/$value['cansendmaxnum']*100);
					}
				}
			}
			$info['voucherinfojifen'] = $asanum;
			$info['voucherinfochae'] = $asamin2;
			$this->info = $info;
		}else{
		    session('historyUrl','http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]);// 用于登陆后跳回本页面
		    $this->checkMemberLoginBox(); // 检测是否登录弹框
		    $memberInfo = $memberCardInfoSetInfo = array();
		}
		$this->display();
	}
	/**
	 * 邀请赠礼的分享详情页面
	 */
	public function info(){
		$this->setPageTitle(array('title'=>'邀请好友'));
		if($this->mid){
			$where['starttime'] = array("elt",time());
			$where['endtime'] = array("egt",time());
			$where['companyid'] = $this->companyid;
			$where['type'] = 10;
			$voucherAct = M("member_marketing_activities_scrm")->where($where)->count();
			if($voucherAct<=0){
				U("MemberInviteVoucher/index",array("companyid"=>$this->companyid),'',true);
			}else{
				$info = M("member_marketing_activities_scrm")->where($where)->order("createtime desc")->find();
			}
			if($info){
				$info['voucherinfo'] = M("member_marketing_activities_scrm_link_voucher")->where(array("parentid"=>$info['id'],'isdel'=>array("neq",1)))->order("createtime desc")->select();
				if($info['voucherinfo']){
					foreach ($info['voucherinfo'] as $key => $val){
						//实时读取券名称
						$val['vouchername'] = M("member_marketing_activities_voucher_info")->where(array("companyid"=>$this->companyid,'id'=>$val['voucherid']))->getField("title");
						$infowhere['companyid'] = $this->companyid;
						if($val['vouchertype'] == '1'||$val['vouchertype'] == '2'||$val['vouchertype'] == '3'||$val['vouchertype'] == '4'){
							$infowhere['id'] = $val['voucherid'];
							$otherinfo = M('member_marketing_activities_voucher_info')->where($infowhere)->field('israndom,parvalue,maxparvalue,title,usestarttime,useendtime,usetimedeferred,usetimetype,usetimelimittype,usetimelimitset')->find();
							if($val['vouchertype']==4){
								if($otherinfo['israndom']==1) $otherinfo['money'] = $otherinfo['minparvalue'];
								else  $otherinfo['money'] = $otherinfo['parvalue'];
								$val['money1'] = floor($otherinfo['money']);
								$val['money2'] = substr(strval(number_format($otherinfo['money']-$val['money1'],2)),2);
							}
						}elseif($val['vouchertype'] == '5'||$val['vouchertype'] == '6'||$val['vouchertype'] == '7'||$val['vouchertype'] == '8'){
							$infowhere['id'] = $val['voucherid'];
							$otherinfo = M('mall_goods')->where($infowhere)->field('title,usetimelimittype,usetimelimitset')->find();
						}else{
							$otherinfo = '';
						}
						$timeinfo['title']=$otherinfo['title'];
						if($otherinfo['usetimelimittype']==1){
							$timeinfo['usetimetype'] = 1;
							$timeinfo['usetimedeferred'] = $otherinfo['usetimelimitset'];
						}elseif($otherinfo['usetimelimittype']==3){
							$timeinfo['usetimetype'] = 3;
							$timeinfo['usestarttime'] = strtotime($otherinfo['usetimelimitset']);
						}elseif($otherinfo['usetimelimittype']==2){
							$timeinfo['usetimetype'] = 2;
							$testtime = json_decode($otherinfo['usetimelimitset'],true);
							$timeinfo['usestarttime'] = strtotime($testtime['usebegintime']);
							$timeinfo['useendtime'] = strtotime($testtime['useendtime']);
						}
						$info['voucherinfo'][$key] = array_merge($timeinfo,$val);
						unset($timeinfo);
					}
				}
			}
			$info['headimgurl'] = M("member_wechat_info")->where(array("companyid"=>$this->companyid,"mid"=>$this->mid))->getField("headimgurl");
			$info['headimgurl'] = $this->headimgurlAsa($info['headimgurl'],$this->mid);
			$info['name'] = M("member_register_info")->where(array("companyid"=>$this->companyid,"id"=>$this->mid))->getField("name");
			$info['nickname'] = M("member_wechat_info")->where(array("companyid"=>$this->companyid,"mid"=>$this->mid))->getField("nickname");
			$info['shareurl'] = 'http://' . $_SERVER['SERVER_NAME'] . U('MemberGiveawayVoucher/yIndex',array("companyid"=>$this->companyid,"parentmid"=>$this->mid,"id"=>$info['id']));
			$info['codeurl'] = $this->codeurlAsa($this->getCodeUrl(),$this->mid);
			$this->defaultWechatShare($info,$info['title']);
			$this->vouchertype = $this->getvouchertype();
			//$this->info = $info;
		}else{
			session('historyUrl','http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]);// 用于登陆后跳回本页面
			$this->checkMemberLoginBox(); // 检测是否登录弹框
			$memberInfo = $memberCardInfoSetInfo = array();
		}
		//dump($_SESSION);
		$this->display();
	}
	/**
	 * 类型
	 */
	public function getvouchertype(){
		return array(
				1=>"优惠券", 2=>"优惠券", 3=>"兑换券",
				4=>"红包", 5=>"计次卡", 7=>"门票",
				6=>"团购券", 8=>"权益卡"
		);
	}
	//把微信头像转为本地的头像
	public function headimgurlAsa($url,$mid){
		//$url = 'http://wx.qlogo.cn/mmopen/XnICxk436dCo8VN8AETJWDfp0iaIpxDR31mDgQYJ03V7b4q7wdU6fqLbiclgHCEOicNpPiciaCn9uTgH3L0mV4XMhR8XrmJlNdmzx/0';
		$path = './Uploads/'.$this->companyid.'/headimgurl/';
		if(!is_dir($path)) mkdir($path);
		$path .= date('Y').date('m').date('d').'/';
		//$mid = 1017;
		$saveName = 'headimgurl_'.$mid.'.png';
		unlink($path.$saveName);
		if(!is_file($path.$saveName)) $filepath = put_file_from_url_content($url, $saveName, $path);
		else $filepath = $path.$saveName;
		return $filepath;
	}
	//把微信关注二维码转为本地的存储
	public function codeurlAsa($url,$mid){
		//$url = 'http://wx.qlogo.cn/mmopen/XnICxk436dCo8VN8AETJWDfp0iaIpxDR31mDgQYJ03V7b4q7wdU6fqLbiclgHCEOicNpPiciaCn9uTgH3L0mV4XMhR8XrmJlNdmzx/0';
		$path = './Uploads/'.$this->companyid.'/codeurl/';
		if(!is_dir($path)) mkdir($path);
		$path .= date('Y').date('m').date('d').'/';
		//$mid = 1017;
		$saveName = 'codeurl'.$mid.'.png';
		unlink($path.$saveName);
		if(!is_file($path.$saveName)) $filepath = put_file_from_url_content($url, $saveName, $path);
		else $filepath = $path.$saveName;
		return $filepath;
	}
	/**
	 * 生成会员关注二维码的
	 */
	public function getCodeUrl(){
		$info = M("member_marketing_activities_scrm_member_code")->where(array("companyid"=>$this->companyid,"mid"=>$this->mid))->find();
		if($info){
			return $info['content'];
		}else{
			$wechat = M('wechats')->where(array('companyid'=>$this->companyid))->field('token,appid,appsecret,wxname')->find();
			if($wechat){
				$date['id'] = guidNow();
				$weixin = new Wechat(array('token'=>$wechat['token'],'appid'=>$wechat['appid'],'appsecret'=>$wechat['appsecret']));
				$QRCodeInfo = $weixin->getQRCode($date['id'],2);
				if($QRCodeInfo){
					$QRCodeInfoSrc = $weixin->getQRUrl($QRCodeInfo['ticket']);
					$date['content'] = $QRCodeInfoSrc;
					$date['companyid'] = $this->companyid;
					$date['wechatName'] = $wechat['wxname'];
					$date['mid'] = $this->mid;
					$date['createtime'] = time();
					$res = M('member_marketing_activities_scrm_member_code')->add($date);
					if($res){
						return $date['content'];
					}else{
						return false;
					}
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
	}
	/**
	 * 
	 */
	public function test(){
		$this->getCodeUrl();
	}
}
?>
