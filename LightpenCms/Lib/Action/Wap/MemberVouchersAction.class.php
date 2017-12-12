<?php
/**
 * 卡券包
 * @author    Thomas<416369046@qq.com>
 * @since     2016-11-21
 * @version   1.0
 */
class MemberVouchersAction extends WapBaseAction{
	
	private $mid;
	
	private $companyid;
	
	public function __construct(){
		parent::__construct();
		$this->mid = session('mid'.session('wapcid'));
		$this->companyid = session('wapcid');
	}
	/**
	 * 卡券包
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-10-24
	 */
	public function myVouchers(){
		$this->setPageTitle(array('title'=>'卡券包'));
		if($this->mid){
			$id=$this->_get('id');
			$tabName = $this->_get('tabName');
			if($tabName){
				$this->CountScannum($id,$tabName); //执行扫描次数+1
			}
			//每次进入这页面计算pv数；
			/* $group = GROUP_NAME;
			$method = MODULE_NAME;
			$action = ACTION_NAME;
			$this->countPv($group,$method,$action); */
			$where['companyid'] = $this->companyid;
			$where['useendtime'] = array('gt',time());
			$where['mid'] = $this->mid;
			$where['isused'] = '2';
			$where['issend'] = '2';
			$limit = '15';
			$count = M('member_vouchers')->where($where)->count();
			$list = M('member_vouchers')->where($where)->field('id,getvouchertype,voucherinfoid,orderid,sn,parvalue,usestarttime,useendtime,isused,issend,usetimelimittype,useshopslimitset,backorderpolicyset,iscansend,useissite,useinfo,usenumberlimit,usednumber,vouchername,voucherskuname,vouchertype,handleruserid,handlerusername,handlershopid,handlershopname,createtime,updatetime,discounttype,minus,discount,fullminus,fulldiscount,eachfullminus')->order('createtime DESC')->limit($limit)->select();
		}else {
			session('historyUrl','http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]);// 用于登陆后跳回本页面
			$this->checkMemberLoginBox();// 检测是否登录弹框
			$count = 0;
			$list = array();
		}
		$this->assign('count',$count);
		$this->assign('list',$list);
		$this->display();
	}
	/**
	 * 我的历史卡券
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-10-24
	 */
	public function myHistoryVouchers(){
		$this->setPageTitle(array('title'=>'历史卡券'));
		if($this->mid){
			$id=$this->_get('id');
			$tabName = $this->_get('tabName');
			if($tabName){
				$this->CountScannum($id,$tabName); //执行扫描次数+1
			}
			//每次进入这页面计算pv数；
			/* $group = GROUP_NAME;
			$method = MODULE_NAME;
			$action = ACTION_NAME;
			$this->countPv($group,$method,$action); */
			$where['companyid'] = $this->companyid;
			$where['mid'] = $this->mid;
			$where['issend'] = '2';
			$where['_string'] = '(useendtime < '.time().') OR (isused = 1)';
			$limit = '15';
			$count = M('member_vouchers')->where($where)->count();
			$list = M('member_vouchers')->where($where)->field('id,getvouchertype,voucherinfoid,orderid,sn,parvalue,iscansend,useissite,usestarttime,useendtime,isused,issend,usetimelimittype,useshopslimitset,backorderpolicyset,useinfo,usenumberlimit,usednumber,vouchername,voucherskuname,vouchertype,handleruserid,handlerusername,handlershopid,handlershopname,createtime,updatetime')->order('usetime DESC,useendtime DESC')->limit($limit)->select();
		}else{
		    session('historyUrl','http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]);// 用于登陆后跳回本页面
			$this->checkMemberLoginBox();// 检测是否登录弹框
			$count = 0;
			$list = array();
		}
		$this->assign('count',$count);
		$this->assign('list',$list);
		$this->display();
	}
	/**
	 * 券详情页
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-10-25
	 */
	public function vouchersInfo(){
		$this->checkMemberLogin();
		$id = $this->_get('id','intval');
		if($this->mid){
		    $where['companyid'] = $this->companyid;
		    $where['mid'] = $this->mid;
		    $where['id'] = $id;
		    $this->assign('id',$id);
		    $vouchersInfo = M('member_vouchers')->where($where)->field('id,getvouchertype,voucherinfoid,orderid,sn,parvalue,usestarttime,useendtime,isused,issend,usetimelimittype,useshopslimitset,backorderpolicyset,useinfo,usenumberlimit,usednumber,vouchername,voucherskuname,vouchertype,handleruserid,handlerusername,handlershopid,handlershopname,createtime,updatetime')->find();
		    /* if($vouchersInfo['vouchertype'] == 2){
		     $vouchersInfo['info'] = M('member_marketing_activities_voucher_info')->where(array('companyid'=>$this->companyid,'id'=>$vouchersInfo['voucherinfoid']))->field('vouchertype,shopcanuserspend,voucherdesc,useshops,title,parvalue,israndom,useissite,useisrestrict,shopcanuserspend,voucherdesc,iscansend,useissite,useisrestrict,userestrictvalue')->find();
		    $this->setPageTitle(array('title'=>$vouchersInfo['info']['title']));
		    $shoparr = explode(',', trim($vouchersInfo['info']['useshops'],','));
		    $vouchersInfo['useinfo'] = $vouchersInfo['info']['voucherdesc'];
		    }else{
		    $this->setPageTitle(array('title'=>$vouchersInfo['vouchername']));
		    $shoparr = explode(',', trim($vouchersInfo['useshopslimitset'],','));
		    } */
		    $this->setPageTitle(array('title'=>$vouchersInfo['vouchername']));
		    $shoparr = explode(',', trim($vouchersInfo['useshopslimitset'],','));
		    $_GET['lat']='31.288038';
		    $_GET['lng']='121.475296';
		    $shopSort = array();
		    foreach($shoparr as $key=>$val){
		        if($val){
		            $shopInfo= M('company_shops')->where(array('companyid'=>$this->companyid,'id'=>$val))->field('id,name,shopname,tel,address,latitude,longitude')->find();
		            $shopInfo['distancemi'] = get_distance($shopInfo['latitude'],$shopInfo['longitude'],$_GET['lat'],$_GET['lng']);
		            $shopInfo['distanceshow'] = distance($shopInfo['distancemi']);
		            $shopSort[$key] = $shopInfo;
		        }
		    }
		    $useShops = arraySort($shopSort, 'distanceshow');
		    $vouchersInfo['nowTime'] =  time(); //当前时间
		}else{
		    session('historyUrl','http://' . $_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]);// 用于登陆后跳回本页面
		    $this->checkMemberLoginBox();// 检测是否登录弹框
		    $useShops = $vouchersInfo = array();
		}
		$this->assign('useShops',$useShops);
		$this->assign('info',$vouchersInfo);
		$this->assign('isshowsystemdiymen','2');//2：不显示；
		$this->display();
	}
	/**
	 * ajax获取地址列表信息
	 */
	public function ajaxShopInfo(){
		$id = $this->_post('id');
		$where['companyid'] = $this->companyid;
		$where['mid'] = $this->mid;
		$where['id'] = $id;
		$vouchersInfo = M('member_vouchers')->where($where)->field('id,getvouchertype,voucherinfoid,orderid,sn,parvalue,usestarttime,useendtime,isused,issend,usetimelimittype,useshopslimitset,backorderpolicyset,useinfo,usenumberlimit,usednumber,vouchername,voucherskuname,vouchertype,handleruserid,handlerusername,handlershopid,handlershopname,createtime,updatetime')->find();
		if($vouchersInfo['vouchertype'] == 2){
			$vouchersInfo['info'] = M('member_marketing_activities_voucher_info')->where(array('companyid'=>$this->companyid,'id'=>$vouchersInfo['voucherinfoid']))->field('vouchertype,voucherdesc,useshops,title,parvalue,israndom,useissite,shopcanuserspend,voucherdesc,iscansend,useissite,useisrestrict,userestrictvalue')->find();
			$shoparr = explode(',', trim($vouchersInfo['info']['useshops'],','));
			$vouchersInfo['useinfo'] = $vouchersInfo['info']['voucherdesc'];
		}else{
			$shoparr = explode(',', trim($vouchersInfo['useshopslimitset'],','));
		}
		$shopSort = array();
		foreach($shoparr as $key=>$val){
			if($val){
				$shopInfo= M('company_shops')->where(array('companyid'=>$this->companyid,'id'=>$val))->field('id,name,shopname,tel,address,latitude,longitude')->find();
				$shopInfo['distancemi'] = get_distance($shopInfo['latitude'],$shopInfo['longitude'],$_POST['lat'],$_POST['lng']);
				$shopInfo['distanceshow'] = distance($shopInfo['distancemi']);
				$shopSort[$key] = $shopInfo;
			}
		}
		$useShops = arraySort($shopSort, 'distanceshow');
		$str = '';
		foreach($useShops as $iKey=>$iVal){
			if($iKey <= 2){
				$str .= '<div class="meter_card_expcont btn_border_img"><div class="meter_card_eleft"><h3>'.$iVal['shopname'].'</h3><p class="meter_card_elep">'.$iVal['address'].'</p><p class="meter_card_eletell"><a href="tel:'.$iVal['tel'].'">'.$iVal['tel'].'</a></p></div><div class="meter_card_eright"><a href="javascript:void(0);"><b class="meter_card_ericn"></b><p>'.$iVal['distanceshow'].'</p></a></div></div>';
			}
		}
		if($str){
			$ajax['code'] = 200;
			$ajax['msg'] = $str;
		}else{
			$ajax['code'] = 300;
			$ajax['msg'] = '搜索不到门店信息';
		}
		echo json_encode($ajax);
	}
	/**
	 * 二维码
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-10-25
	 */
	public function erweima(){
		$url = base64_decode($this->_request('url'));
		$this->getQRcode($url,'L','7');
	}
	/**
	 * 查询核销状态
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-10-25
	 */
	public function QueryArrangedStatus(){
		$returnData['code'] = 300;
		$returnData['tips'] = '核销中';
		$id = $this->_post('id');
		if($id){
			$where['companyid'] = $this->companyid;
			$where['id'] = $id;
			$orderInfo = M('member_vouchers')->where($where)->field('id,isused')->find();
			if($orderInfo['isused'] == '1'){
				$returnData['code'] = 200;
				$returnData['tips'] = '核销成功';
			}
		}
		echo json_encode($returnData);
	}
	/**
	 * 查询计次卡核销状态
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-10-25
	 */
	public function QueryJiArrangedStatus(){
		$returnData['code'] = 300;
		$returnData['tips'] = '核销中';
		$id = $this->_post('id');
		$usednumber = $this->_post("usednumber");
		if($id){
			$where['companyid'] = $this->companyid;
			$where['id'] = $id;
			$orderInfo = M('member_vouchers')->where($where)->field('id,usednumber,usenumberlimit')->find();
			if($orderInfo['usednumber'] == ($usednumber+1)){
				$returnData['usednumber'] = $orderInfo['usednumber'];
				$returnData['usenumberlimit'] = $orderInfo['usenumberlimit'];
				$returnData['iscanusenumber'] = $orderInfo['usenumberlimit'] - $orderInfo['usednumber'];
				$returnData['code'] = 200;
				$returnData['tips'] = '核销成功';
			}
		}
		echo json_encode($returnData);
	}
	/**
	 * 下拉加载更多我的卡券
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-10-27
	 */
	public function ajaxMyVouchers(){
		$data['code'] = 300;
		$where['companyid'] = $this->companyid;
		$where['useendtime'] = array('gt',time());
		$where['mid'] = $this->mid;
		$where['isused'] = '2';
		$where['issend'] = '2';
		$limit = 15;
		$startNumber = $this->_post('startNumber'); // 本次查询的开始条数
		$startNumber = $startNumber ? $startNumber : 0;
		$list = M('member_vouchers')->where($where)->field('id,getvouchertype,voucherinfoid,orderid,sn,parvalue,usestarttime,useendtime,isused,issend,usetimelimittype,useshopslimitset,backorderpolicyset,useinfo,usenumberlimit,usednumber,vouchername,voucherskuname,vouchertype,handleruserid,handlerusername,handlershopid,handlershopname,createtime,updatetime')->order('createtime DESC')->limit($startNumber,$limit)->select();
		$data['string'] = '';
		foreach($list as $key=>$val){
			$data['string'] .= '<li><a href="'.U('MemberVouchers/vouchersInfo',array('companyid'=>$this->companyid,'id'=>$val['id'])).'"><img src="';
			if($val['vouchertype'] == 7 || $val['vouchertype'] == 8 || $val['vouchertype'] == 40){
				$data['string'] .= './Tpl/Wap/default/common/activity_wap/img/quan_bg5.png';
			}elseif($val['vouchertype'] == 9){
				$data['string'] .= './Tpl/Wap/default/common/activity_wap/img/quan_bg4.png';
			}elseif($val['vouchertype'] == 3){
				$data['string'] .= './Tpl/Wap/default/common/activity_wap/img/quan_bg2.png';
			}elseif($val['vouchertype'] == 4){
				$data['string'] .= './Tpl/Wap/default/common/activity_wap/img/quan_bg6.png';
			}elseif($val['vouchertype'] == 5){
				$data['string'] .= './Tpl/Wap/default/common/activity_wap/img/quan_bg1.png';
			}elseif($val['vouchertype'] == 6){
				$data['string'] .= './Tpl/Wap/default/common/activity_wap/img/quan_bg3.png';
			}
			$data['string'] .= '" /><div class="voucher_package_cont"><div class="voucher_package_conts">';
			if($val['vouchertype'] == 3){
				$data['string'] .= '<p class="meter_card_c0p">计次卡<span class="meter_card_cspd2">门店使用</span></p>';
			}elseif($val['vouchertype'] == 4){
				$data['string'] .= '<p class="meter_card_c0p">团购<span class="meter_card_cspd5">门店使用</span></p>';
			}elseif($val['vouchertype'] == 5){
				$data['string'] .= '<p class="meter_card_c0p">门票<span class="meter_card_cspd1">门店使用</span></p>';
			}elseif($val['vouchertype'] == 6){
				$data['string'] .= '<p class="meter_card_c0p">权益卡<span class="meter_card_cspd4">门店使用</span></p>';
			}elseif($val['vouchertype'] == 7){
				$data['string'] .= '<p class="meter_card_c0p">优惠券<span class="meter_card_cspd6">eshop使用</span></p>';
			}elseif($val['vouchertype'] == 8){
				$data['string'] .= '<p class="meter_card_c0p">优惠券<span class="meter_card_cspd6">门店使用</span></p>';
			}elseif($val['vouchertype'] == 9){
				$data['string'] .= '<p class="meter_card_c0p">兑换券<span class="meter_card_cspd3">门店使用</span></p>';
			}elseif($val['vouchertype'] == 40){
				$data['string'] .= '<p class="meter_card_c0p">优惠券<span class="meter_card_cspd6">通用券</span></p>';
			}
			$data['string'] .= '<h3 class="masterY_middle">'.$val['vouchername'].'</h3>';
			$data['string'] .= '<p class="meter_card_c1p">'.format_time($val['usestarttime'],'ymdhi').' 至 '.format_time($val['useendtime'],'ymdhi').'</p></div></div></a></li>';
		}
		if($data['string']){
			$data['code'] = 200;
		}
		echo json_encode($data);
	}
	/**
	 * 下拉加载更多我的历史卡券
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-10-27
	 */
	public function ajaxMyHistoryVouchers(){
		$data['code'] = 300;
		$where['companyid'] = $this->companyid;
		$where['mid'] = $this->mid;
		$where['issend'] = '2';
		$where['_string'] = '(useendtime < '.time().') OR (isused = 1)';
		$limit = 15;
		$startNumber = $this->_post('startNumber'); // 本次查询的开始条数
		$startNumber = $startNumber ? $startNumber : 0;
		$data['string'] = '';
		$list = M('member_vouchers')->where($where)->field('id,getvouchertype,voucherinfoid,orderid,sn,parvalue,usestarttime,useendtime,isused,issend,usetimelimittype,useshopslimitset,backorderpolicyset,useinfo,usenumberlimit,usednumber,vouchername,voucherskuname,vouchertype,handleruserid,handlerusername,handlershopid,handlershopname,createtime,updatetime')->order('useendtime DESC,usestarttime DESC')->limit($startNumber,$limit)->select();
		foreach($list as $key=>$val){
			$data['string'] .= '<li><a href="';
			if($val['vouchertype'] == 10){
				$data['string'] .= 'javascript:void(0);';
			}else{
				$data['string'] .= U('MemberVouchers/vouchersInfo',array('companyid'=>$this->companyid,'id'=>$val['id']));
			}
			$data['string'] .= '"><img src="./Tpl/Wap/default/common/activity_wap/img/part_bg.png" /><div class="voucher_package_cont"><div class="voucher_package_conts">';
			if($val['vouchertype'] == 3){
				$data['string'] .= '<p class="meter_card_c0p">计次卡<span class="">门店使用</span></p>';
			}elseif($val['vouchertype'] == 4){
				$data['string'] .= '<p class="meter_card_c0p">团购<span class="">门店使用</span></p>';
			}elseif($val['vouchertype'] == 5){
				$data['string'] .= '<p class="meter_card_c0p">门票<span class="">门店使用</span></p>';
			}elseif($val['vouchertype'] == 6){
				$data['string'] .= '<p class="meter_card_c0p">权益卡<span class="">门店使用</span></p>';
			}elseif($val['vouchertype'] == 7){
				$data['string'] .= '<p class="meter_card_c0p">优惠券<span class="">eshop使用</span></p>';
			}elseif($val['vouchertype'] == 8){
				$data['string'] .= '<p class="meter_card_c0p">优惠券<span class="">门店使用</span></p>';
			}elseif($val['vouchertype'] == 9){
				$data['string'] .= '<p class="meter_card_c0p">兑换券<span class="">门店使用</span></p>';
			}elseif($val['vouchertype'] == 10){
				$data['string'] .= '<p class="meter_card_c0p">红包<span class=""></span></p>';
			}elseif($val['vouchertype'] == 40){
				$data['string'] .= '<p class="meter_card_c0p">优惠券<span class="">通用券</span></p>';
			}
			if($val['vouchertype'] == 10){
				$data['string'] .= '<h3 class="masterY_middle">'.$val['parvalue'].' 元</h3><p class="meter_card_c1p">'.$val['vouchername'].'</p>';
			}else{
				$data['string'] .= '<h3 class="masterY_middle">'.$val['vouchername'].'</h3><p class="meter_card_c1p">'.format_time($val['usestarttime'],'ymdhi').' 至 '.format_time($val['useendtime'],'ymdhi').'</p>';
			}
			$data['string'] .= '</div></div></a></li>';
		}
		if($data['string']){
			$data['code'] = 200;
		}
		echo json_encode($data);
	}
	/**
	 * 计次卡消费确认
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-10-27
	 */
	public function meterCardConsumConfirm(){
		$this->setPageTitle(array('title'=>'计次卡消费扣次确认'));
		$this->checkMemberLogin();
		$sn = $this->_request('sn');
		$staffOpenid = $this->_request('staffOpenid');
		if($staffOpenid && $sn){
			//获取核销人以及核销门店的信息
			$openWhere['companyid'] = $this->companyid;
			$openWhere['id'] = $staffOpenid;
			$openInfo = M('users')->where($openWhere)->field('id,truename,username,helpershopid,isboss,helperopenid')->find();
			//获取核销门店的名称
			$shopInfo = M('company_shops')->where(array('companyid'=>$this->companyid,'id'=>$openInfo['helpershopid']))->field('id,shopname,tel')->find();
			$snInfo = M('member_vouchers')->where(array('companyid'=>$this->companyid,'sn'=>$sn))->field('id,vouchername,usednumber,usenumberlimit,usestarttime,useendtime,sn')->find();
			$logourl = M('company')->where(array('id'=>$this->companyid))->getField('logourl');
			$logData['status'] = 2;
			$logData['updatetime'] = time();
			M('member_vouchers_metercard_usetime_log')->where(array('companyid'=>$this->companyid, 'sn'=>$snInfo['sn']))->save($logData);
			$this->assign('openInfo',$openInfo);
			$this->assign('shopInfo',$shopInfo);
			$this->assign('snInfo',$snInfo);
			$this->assign('logourl',$logourl);
			$this->display();
		}else{
			$this->redirect(U('System/notFound',array('companyid'=>$this->companyid)));
		}
	}
	/**
	 * 是否同意消费确认
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-10-27
	 */
	public function ajaxConsum(){
		$time = time();
		$id = $this->_post('id');
		$type = $this->_post('type');
		$staffid = $this->_post('staffid');
		$snInfo = M('member_vouchers')->where(array('companyid'=>$this->companyid,'id'=>$id))->field('id,mid,voucherid,voucherinfoid,sn,parvalue,usestarttime,useendtime,isused,vouchertype,voucherskuname,useinfo,useshopslimitset,usenumberlimit,usednumber,vouchername,saleprice,originalprice')->find();
		if($type == 2){
			M()->startTrans();
			if(($snInfo['usednumber']+1) == $snInfo['usenumberlimit']){
				$voucherData['isused'] = '1';
				// SCRM5 活动 的核销 +1
				$SCRM5ActivityData['updatetime'] = $time;
				$SCRM5ActivityData['usenum'] = array('exp', '`usenum`+1');
				$SCRM5ActivityReturn = M('member_marketing_activities_scrm_link_voucher')->where(array('parentid'=>$snInfo['voucherid'],'voucherid'=>$snInfo['voucherinfoid']))->save($SCRM5ActivityData);
			}
			$voucherData['usetime'] = time();
			$voucherWhere['companyid'] = $this->companyid;
			$voucherWhere['id'] = $id;
			$vouchersReturn = M('member_vouchers')->where($voucherWhere)->save($voucherData);
			//计次卡核销使用次数+1
			$consumResult = M('member_vouchers')->where($voucherWhere)->setInc('usednumber');
			//添加一条新的核销记录
			$data['vouchername'] = $snInfo['vouchername']; // 券名称
			$data['value'] = ($snInfo['usednumber']+1).'/'.$snInfo['usenumberlimit'];
			$data['utility'] = $data['value'] . '次';  // 效用
			$data['remarks'] = htmlspecialchars_decode(htmlspecialchars_decode($snInfo['useinfo'])); // 使用说明
			$snTitle = $snInfo['vouchername']; //发送券标题
			$data['vouchernumber'] = $snInfo['sn'];
			$data['vouchertype'] = $snInfo['vouchertype'];
			$data['voucherid'] = $snInfo['id'];
			$data['voucherstarttime'] = $snInfo['usestarttime'];
			$data['voucherendtime'] = $snInfo['useendtime'];
			if($snInfo['originalprice'] && $snInfo['saleprice']){
				$data['singleprice'] = format_number($snInfo['saleprice']/$snInfo['usenumberlimit']);
			}
			// 会员信息
			if($snInfo['mid']){
				$data['mid'] = $snInfo['mid'];
				$registerWhere['companyid'] = $this->companyid;
				$registerWhere['id'] = $snInfo['mid'];
				$registerInfo = M('member_register_info')->where($registerWhere)->field('id,moblie')->find();
				if($registerInfo){
					$data['mobile'] = $registerInfo['moblie'];
				}
			}
			if($staffid){
				$data['staffopenid'] = $staffid;   // 核销人openid
				$openWhere['companyid'] = $this->companyid;
				$openWhere['id'] = $staffid;
				$openInfo = M('users')->where($openWhere)->field('id,truename,username,helpershopid,isboss')->find();
				if($openInfo){
					$data['staffname'] = $openInfo['truename'];   // 核销人姓名
					$isboss = $openInfo['isboss'];
					$helpershopid = $openInfo['helpershopid'];
					if($isboss == '1'){
						$data['shopid'] = '-1';  // 核销门店
						$data['shopname'] = '总部';  // 核销门店名称
					}else{
						if($helpershopid == '-1'){
							$data['shopid'] = '-1';
							$data['shopname'] = '总部';
						}else{
							$shopInfo = M('company_shops')->where(array('companyid'=>$this->companyid, 'id'=>$helpershopid))->field('id,shopname,name')->find();
							$data['shopid'] = $helpershopid;
							$data['shopname'] = $shopInfo['shopname']?$shopInfo['shopname']:$shopInfo['name'];
						}
					}
					$data['id'] = guidNow();
					$data['companyid'] = $this->companyid;
					$data['isconsume'] = '1';
					$data['type'] = '1';
					$data['usetime'] = $time;
					$data['createtime'] = $data['updatetime'] = $time;
					$return = M('use_vouchers')->add($data);
				}
			}
			if($vouchersReturn && $consumResult && $return){
				M()->commit();
				$openid = M('member_wechat_info')->where(array('companyid'=>$this->companyid,'mid'=>$snInfo['mid']))->getField('openid');
				if($openid){
					$this->WeChatTemplateMessageSend('29',$openid,$this->companyid,'','',array('使用计次卡','卡券通知'),array(htmlspecialchars_decode($snTitle),($snInfo['usenumberlimit']-($snInfo['usednumber']+1))));
				}
				$logData['status'] = 3;
				$logData['updatetime'] = time();
				M('member_vouchers_metercard_usetime_log')->where(array('companyid'=>$this->companyid, 'sn'=>$snInfo['sn']))->save($logData);
				$returnData['code'] = 200;
				$returnData['tips'] = '扣次成功';
			}else{
				M()->rollback();
				$logData['status'] = 4;
				$logData['updatetime'] = time();
				M('member_vouchers_metercard_usetime_log')->where(array('companyid'=>$this->companyid, 'sn'=>$snInfo['sn']))->save($logData);
				$returnData['code'] = 300;
				$returnData['tips'] = '扣次失败';
			}
		}elseif($type == 1){
			$logData['status'] = 4;
			$logData['updatetime'] = time();
			M('member_vouchers_metercard_usetime_log')->where(array('companyid'=>$this->companyid, 'sn'=>$snInfo['sn']))->save($logData);
			$returnData['code'] = 300;
			$returnData['tips'] = '扣次失败';
		}
		echo json_encode($returnData);
	}
	/**
	 * 计次卡核销结果
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-10-27
	 */
	public function consum(){
		$this->checkMemberLogin();
		$type = $this->_get('type');
		$this->assign('type',$type);
		if($type == 2){
			$this->setPageTitle(array('title'=>'扣次成功'));
		}else{
			$this->setPageTitle(array('title'=>'扣次失败'));
		}
		$this->display();
	}
	/**
	 * 电子券使用
	 */
	public function vouchersUse(){
		$this->checkMemberLogin();
		$id=$this->_get('id');
		$tabName = $this->_get('tabName');
		if($tabName){
			$this->CountScannum($id,$tabName); //执行扫描次数+1
		}
		//每次进入这页面计算pv数；
		$group = GROUP_NAME;
		$method = MODULE_NAME;
		$action = ACTION_NAME;
		$this->countPv($group,$method,$action);
		$vouchersInfo['vouchertype'] = $this->_get('vouchertype','intval');
		if($vouchersInfo['vouchertype'] == 3){
			$this->setPageTitle(array('title'=>'会员卡充值'));
		}else{
			$this->setPageTitle(array('title'=>'使用我的电子券')); 
		}
		$vouchersInfo['accountbalance'] = M('member_register_info')->where(array('companyid'=>$this->companyid,'id'=>$this->mid))->getField('accountbalance');
		$id = $this->_get('id','intval');
		if($id){
			if($vouchersInfo['vouchertype']==3){
				$vouchersInfo['password'] = $this->_get('password');
			}else{
				$vouchersInfo = M()->table('tp_member_vouchers AS vouchers')->join(array('LEFT JOIN tp_member_marketing_activities_voucher_info AS info on info.id = vouchers.voucherinfoid'))->where(array('vouchers.companyid'=>$this->companyid,'vouchers.mid'=>$this->mid,'vouchers.id'=>$id,'vouchers.issend'=>'2'))
					->field('vouchers.id,vouchers.sn,vouchers.parvalue as vparvalue,vouchers.isused,info.id as infoId,info.vouchertype,info.title,info.parvalue,info.israndom,info.url,vouchers.usestarttime,vouchers.useendtime,info.voucherdesc,info.iscansend,info.useissite')->find();
				$vouchersInfo['shops'] = M('company_shops')->where(array('companyid'=>$this->companyid))->field('id,shopname,name')->select();
			}
		}
		$this->assign('info',$vouchersInfo);
		$this->assign('isshowsystemdiymen','2');//2：不显示；
		$this->display();
	}
	/**
	 * 电子券使用-->ajax 1：优惠券；2：电子券；
	 */
	public function ajaxVouchersUse(){
		$id = $this->_get('id','intval');
		$infoId = $this->_get('infoId','intval');
		$password = $this->_post('password');
		$shopid = $this->_post('shopid');
		$vouchersInfo = D('Member_marketing_activities_voucher_info')->getMemberMarketingActivitiesVoucherInfoInfo(array('companyid'=>$this->companyid,'id'=>$infoId));
		if($vouchersInfo['usepassword'] == $password){
			if($id){
				$memberVouchersWhere['companyid'] = $this->companyid;
				$memberVouchersWhere['id'] = $id;
				$memberVouchersData['isused'] = 1;
				$memberVouchersData['shopid'] = $shopid;
				$memberVouchersData['updatetime'] = $memberVouchersData['usetime'] = time();
				$saveVouchersReturn = M('member_vouchers')->where($memberVouchersWhere)->save($memberVouchersData);
				if($saveVouchersReturn){
					$noticInfo = D('Company_system_notic_set')->getCompanySystemNoticSetInfo(array('companyid'=>$this->companyid));
					$noticType = '';
					$noticContent = '';
					$vouchertype = '';
					$companyInfo = D('Company')->getComapnyInfo($this->companyid);
					if($noticInfo['voucherused'] && $noticInfo['voucherusedisopen'] == 1){
						if($vouchersInfo['issystemtips'] == 1){
							$noticType .='1,';
						}
						if($vouchersInfo['issmstips'] == 1){
							$noticType .='2,';
						}
						if($vouchersInfo['iswechattips'] == 1){
							$noticType .='3,';
						}
						if($noticType){
							//准备通知内容
							$searchArr = array('<-姓名->','<-会员卡号后4位->','<-公司名称->','<-会员等级名称->','<-优惠类型->','<-优惠标题->','<-生效日期->','<-有效期->');
							if($vouchersInfo['vouchertype'] == '1'){
								$vouchertype = '优惠券';
							}elseif ($vouchersInfo['vouchertype'] == '2'){
								$vouchertype = '赠品券';
							}
							$registerInfo = D('Member_register_info')->getMemberRegisterLinkRankInfo(array('register.companyid'=>$this->companyid,'register.id'=>$this->mid));
							$replaceArr = array($registerInfo['name'],substr($registerInfo['cardnum'], -4),$companyInfo['name'],$registerInfo['rankname'],$vouchertype,$vouchersInfo['title'],format_time($vouchersInfo['usestarttime']+60,'ymd'),format_time($vouchersInfo['useendtime'],'ymd'));
							$noticContent = str_replace($searchArr, $replaceArr, htmlspecialchars_decode($noticInfo['voucherused']));
							if($noticContent){
								$this->sendSystemNoticBase($this->companyid, $this->mid, $noticContent,$noticType);
							}
						}
					}
					$openid = M('member_wechat_info')->where(array('companyid'=>$this->companyid,'mid'=>$this->mid))->getField('openid');
					if($openid){
						$this->WeChatTemplateMessageSend('10',$openid,$this->companyid,'','',array('核销电子券','券通知'),array($vouchersInfo['title']));
					}
					$data['code'] = 200;
					$data['tips'] = '电子券使用成功';
				}else{
					$data['code'] = 300;
					$data['tips'] = '电子券使用失败';
				}
			}else{
				$data['code'] = 300;
				$data['tips'] = '电子券使用失败';
			}
		}else{
			$data['code'] = 300;
			$data['tips'] = '密码错误';
		}
		echo json_encode($data);
	}
	/**
	 * 电子券使用-->ajax 3：充值卡；
	 */
	public function ajaxVouchersUse2(){
		$password = $this->_post('password');
		$voucher = M()->table('tp_member_vouchers as voucher')->join(array('LEFT JOIN tp_member_marketing_activities_voucher_info as info on info.id=voucher.voucherinfoid'))->where(array('voucher.companyid'=>$this->companyid,'voucher.mid'=>$this->mid,'voucher.isused'=>2,'info.vouchertype'=>3,'voucher.prepaidcardpassword'=>$password))->field('voucher.id,voucher.voucherinfoid,info.parvalue')->find();
		if($voucher){
			$vouchersInfo = D('Member_marketing_activities_voucher_info')->getMemberMarketingActivitiesVoucherInfoInfo(array('companyid'=>$this->companyid,'id'=>$voucher['voucherinfoid']));
			if($voucher['id']){
				$memberVouchersWhere['companyid'] = $this->companyid;
				$memberVouchersWhere['id'] = $voucher['id'];
				$memberVouchersData['isused'] = 1;
				$memberVouchersData['updatetime'] = $memberVouchersData['usetime'] = time();
				$saveVouchersReturn = M('member_vouchers')->where($memberVouchersWhere)->save($memberVouchersData);
				
				//充值卡 消费类型：9：线上自动充值；
				$mid = $this->mid;
				$companyid= $this->companyid;
				$spendingReturn = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('accountbalance',$vouchersInfo['parvalue']);
				$memberIntegralSet = D('Member_integral_set')->getMemberIntegralSetInfo(array('companyid'=>$companyid));
					$borderid = get_order_id();
					$data['adduid'] = $data['edituid'] = session('uid')? session('uid') : 0 ;
					$data['companyid'] = $companyid;
					$data['shopsid'] = session('shopsid') ? session('shopsid') : 0 ;
					$data['mid'] = $mid;
					$data['status'] = '1';
					$data['updatetime'] = $data['createtime'] = time();
					$spendingData = $data;
					$spendingData['shopid'] =  session('shopsid') ? session('shopsid') : 0 ;
					$spendingData['borderid'] =  $borderid;
					$spendingData['orderid'] =  get_order_id();
					$spendingData['type'] =  '9';
					$spendingData['spendingamount'] =  $vouchersInfo['parvalue'];
					$types = ',s9,';//用于列表页记录搜索
				$spendingChange = M('member_spending')->add($spendingData);
				if($memberIntegralSet['onlinechongzhiisopen'] == '1' &&$vouchersInfo['parvalue']>0){
					$expNum = $memberIntegralSet['onlinechongzhiexp']*$vouchersInfo['parvalue'];
					if($expNum>0){
						$experienceData = $data;
						$experienceData['borderid'] =  $borderid;
						$experienceData['orderid'] =  get_order_id();
						$experienceData['type'] =  '13';
						$types .= 'e13,';
						$experienceData['experiencevalue'] =  $expNum;
						$experienceChange = M('member_experiencevalue')->add($experienceData);
						$registerExperienceChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalexperiencevalue',$expNum);
					}else{
						$experienceChange = $registerExperienceChange = 1;
					}
					$intNum = $memberIntegralSet['onlinechongzhiint']*$vouchersInfo['parvalue'];
					if($intNum>0){
						$integralData = $data;
						$integralData['borderid'] =  $borderid;
						$integralData['orderid'] =  get_order_id();
						$integralData['type'] =  '13';
						$types .= 'i13,';
						$integralData['integralnum'] =  $intNum;
						$integralChange = M('member_integral')->add($integralData);
						$registerIntegralChange = M('member_register_info')->where(array('id'=>$mid,'companyid'=>$companyid))->setInc('totalintegration',$intNum);
					}else{
						$integralChange = $registerIntegralChange = 1;
					}
				}else{
					$experienceChange = $registerExperienceChange = $integralChange = $registerIntegralChange = 1;
				}
				$bdata = $data;
				$bdata['orderid'] = $borderid;
				$bdata['businesstype'] = '3';
				$bdata['types'] = $types;
				$businessReturn = M('member_business')->add($bdata);
				if($saveVouchersReturn && $spendingReturn && $spendingChange && $businessReturn && $experienceChange && $registerExperienceChange && $integralChange && $registerIntegralChange){
					$noticInfo = D('Company_system_notic_set')->getCompanySystemNoticSetInfo(array('companyid'=>$this->companyid));
					$noticType = '';
					$noticContent = '';
					$vouchertype = '';
					$companyInfo = D('Company')->getComapnyInfo($this->companyid);
					if($noticInfo['voucherused'] && $noticInfo['voucherusedisopen'] == 1){
						if($vouchersInfo['issystemtips'] == 1){
							$noticType .='1,';
						}
						if($vouchersInfo['issmstips'] == 1){
							$noticType .='2,';
						}
						if($vouchersInfo['iswechattips'] == 1){
							$noticType .='3,';
						}
						if($noticType){
							//准备通知内容
							$searchArr = array('<-姓名->','<-会员卡号后4位->','<-公司名称->','<-会员等级名称->','<-优惠类型->','<-优惠标题->','<-生效日期->','<-有效期->');
							$vouchertype = '充值卡';
							$registerInfo = D('Member_register_info')->getMemberRegisterLinkRankInfo(array('register.companyid'=>$this->companyid,'register.id'=>$this->mid));
							$replaceArr = array($registerInfo['name'],substr($registerInfo['cardnum'], -4),$companyInfo['name'],$registerInfo['rankname'],$vouchertype,$vouchersInfo['title'],format_time($vouchersInfo['usestarttime']+60,'ymd'),format_time($vouchersInfo['useendtime'],'ymd'));
							$noticContent = str_replace($searchArr, $replaceArr, htmlspecialchars_decode($noticInfo['voucherused']));
							if($noticContent){
								$this->sendSystemNoticBase($this->companyid, $this->mid, $noticContent,$noticType);
							}
						}
					}
					$data['code'] = 200;
					$data['tips'] = '充值卡使用成功';
					$data['infoid'] = $voucher['voucherinfoid'];
				}else{
					$data['code'] = 300;
					$data['tips'] = '充值卡使用失败';
				}
			}else{
				$data['code'] = 300;
				$data['tips'] = '充值卡使用失败';
			}
		}else{
			$data['code'] = 300;
			$data['tips'] = '密码错误';
		}
		echo json_encode($data);
	}
	/**
	 * 电子券使用成功
	 */
	 public function vouchersUseSuccess(){
	 	$this->checkMemberLogin();
	 	$this->setPageTitle(array('title'=>'电子券使用成功'));
	 	$infoid = $this->_get('infoid');
	 	if($infoid){
		 	$info['accountbalance'] = M('member_register_info')->where(array('companyid'=>$this->companyid,'id'=>$this->mid))->getField('accountbalance');
		 	$info['parvalue'] = M('member_marketing_activities_voucher_info')->where(array('companyid'=>$this->companyid,'id'=>$infoid))->getField('parvalue');
		 	$this->assign('info',$info);
	 	}
	 	$this->display();
	 }
	 /**
	  * 转增朋友-->电子券
	  */
	 public function ajaxSendVoucherToFriend(){
	 	$data['code'] = 300;
	 	$data['tips'] = '';
	 	$moblie = $this->_post('moblie');
	 	$sendid = $this->_get('id');
	 	$memberVouchersSend = M('member_vouchers_send')->where(array('companyid'=>$this->companyid,'frommid'=>$this->mid,'mobile'=>$moblie,'sendid'=>$sendid))->find();
	 	if(empty($memberVouchersSend)){
	 		$registerId = M('member_register_info')->where(array('companyid'=>$this->companyid,'moblie'=>$moblie))->getField('id');
	 		if($sendid){
	 			$senddata['companyid'] = $this->companyid;
	 			$senddata['frommid'] = $this->mid;
	 			$senddata['tomid'] = $registerId ? $registerId : '0';
	 			$senddata['mobile'] = $moblie;
	 			$senddata['sendid'] = $sendid;
	 			$senddata['updatetime'] = $senddata['createtime'] = time();
	 			$addReturn = M('member_vouchers_send')->add($senddata);
	 			if($addReturn){
	 				$data['code'] = 200;
	 				if($registerId){
	 					$infoId = $this->_get('infoId');
	 					$fromName = M('member_register_info')->where(array('companyid'=>$this->companyid,'id'=>$this->mid))->getField('name');
	 					$voucherTitle = M('member_marketing_activities_voucher_info')->where(array('id'=>$infoId))->getField('title');
	 					$voucherUseEndTime = M('member_vouchers')->where(array('id'=>$sendid))->getField('useendtime');
	 					$voucherUseStartTime = M('member_vouchers')->where(array('id'=>$sendid))->getField('usestarttime');
	 					$voucherUseEndTime = $voucherUseEndTime ? $voucherUseEndTime : time();
	 					$option['companyid'] = $this->companyid;
	 					$option['mid'] = $senddata['tomid'];
	 					$option['info'] = '您的好友'.$fromName.'赠送给您一张电子券：'.$voucherTitle.'，将于'.format_time($voucherUseEndTime,"ymdhi").'过期<a href="javascript:void(0);" class="getVoucher red" vSendid="'.$sendid.'" vFrommid="'.$senddata["frommid"].'">请立即领取</a>';
	 					$this->sendSystemNotice($option);
	 					$openid = M('member_wechat_info')->where(array('companyid'=>$this->companyid,'mid'=>$option['mid']))->getField('openid');
	 					if($openid){
	 						$this->WeChatTemplateMessageSend('9',$openid,$this->companyid,'','',array('获得电子券','券通知'),array($voucherTitle,format_time($voucherUseStartTime+60,'ymd').'-'.format_time($voucherUseEndTime,'ymd')));
	 					}
	 				}
	 			}else{
	 				$data['code'] = 300;
	 				$data['tips'] = '抱歉，您的操作有误！请重新操作。';
	 			}
	 		}else{
	 			$data['code'] = 300;
	 			$data['tips'] = '抱歉，您的操作有误！请重新操作。';
	 		}
	 	}else{
	 		$data['code'] = 300;
	 		$data['tips'] = '您已向该好友发过此电子券！';
	 	}
	 	echo json_encode($data);
	 }
}
?>