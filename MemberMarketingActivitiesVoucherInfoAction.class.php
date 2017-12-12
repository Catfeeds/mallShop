<?php
/**
 * 卡券管理
 * @author    Tomas<416369046@qq.com>
 * @since     2016-11-7
 * @version   1.0
 */
class MemberMarketingActivitiesVoucherInfoAction extends UserAction{
	
	private $uid;
	
	private $companyid;
	
	private $shopsid;
	
	private $companyShopModel;
	
	private $memberMarketingActivitiesVoucherInfoModel;
	
	private $memberMarketingActivitiesVoucherModel;
	
	private $memberRegisterInfoModel;
	
	private $memberVouchersModel;
	
	public function __construct(){
		parent::__construct();
		$this->memberMarketingActivitiesVoucherInfoModel = D('Member_marketing_activities_voucher_info');
		$this->companyShopModel = D('Company_shops');
		$this->memberMarketingActivitiesVoucherModel = D('Member_marketing_activities_voucher');
		$this->memberRegisterInfoModel = M('member_register_info');
		$this->memberVouchersModel = M('member_vouchers');
		$this->uid 		 = session('uid');
		$this->companyid = session('cid');
		$this->shopsid   = session('shopsid');
	}
	/**
	 * 优惠管理
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-11-7
	 */
	public function index(){
		$vouchertype = $this->_request('vouchertype'); // 接收优惠类型
		if($vouchertype == 1){
			$this->checkCompanyScrm5Permissions(36,TRUE);
			$this->makeTopUrl = $this->makeTopUrl_User(array($this->CRM_WELCOME,array('name'=>'卡券中心','url'=>U('Vouchers/center'),'rel'=>'','target'=>''),array('name'=>'eshop优惠券','url'=>'','rel'=>'','target'=>'')));
		}elseif($vouchertype == 2){
			$this->checkCompanyScrm5Permissions(37,TRUE);
			$this->makeTopUrl = $this->makeTopUrl_User(array($this->CRM_WELCOME,array('name'=>'卡券中心','url'=>U('Vouchers/center'),'rel'=>'','target'=>''),array('name'=>'门店使用优惠券','url'=>'','rel'=>'','target'=>'')));
		}elseif($vouchertype == 3){
			$this->checkCompanyScrm5Permissions(38,TRUE);
			$this->makeTopUrl = $this->makeTopUrl_User(array($this->CRM_WELCOME,array('name'=>'卡券中心','url'=>U('Vouchers/center'),'rel'=>'','target'=>''),array('name'=>'兑换券','url'=>'','rel'=>'','target'=>'')));
		}elseif($vouchertype == 4){
			$this->checkCompanyScrm5Permissions(39,TRUE);
			$this->makeTopUrl = $this->makeTopUrl_User(array($this->CRM_WELCOME,array('name'=>'卡券中心','url'=>U('Vouchers/center'),'rel'=>'','target'=>''),array('name'=>'红包','url'=>'','rel'=>'','target'=>'')));
		}elseif($vouchertype == 40){
			$this->checkCompanyScrm5Permissions(129,TRUE);
			$this->makeTopUrl = $this->makeTopUrl_User(array($this->CRM_WELCOME,array('name'=>'卡券中心','url'=>U('Vouchers/center'),'rel'=>'','target'=>''),array('name'=>'通用券','url'=>'','rel'=>'','target'=>'')));
		}
		if($vouchertype){
			$where['vouchertype'] = $vouchertype;
		}
		$where['companyid'] = $this->companyid;
		$this->assign('vouchertype',$vouchertype);
		$voucherInfoCount = M('member_marketing_activities_voucher_info')->where($where)->count();
		$page = new NewPage($voucherInfoCount,15);
		$voucherInfoList = M('member_marketing_activities_voucher_info')->where($where)->field('id,title,vouchertype,useshops,voucherdesc,useissite,discounttype,minus,discount,fullminus,fulldiscount,eachfullminus,usetimelimittype,usetimelimitset,parvalue,minparvalue,maxparvalue,israndom,deliverynum,verificationnum')->order('updatetime desc')->limit($page->firstRow.','.$page->listRows)->select();
		foreach($voucherInfoList as $key=>$val){
			if($val['usetimelimittype'] == 1){
    			$voucherInfoList[$key]['usestarttime'] = time();
    			$voucherInfoList[$key]['useendtime'] = strtotime('+'.$val['usetimelimitset'].' day');
    		}elseif($val['usetimelimittype'] == 2){
    			$usetimelimitset = json_decode($val['usetimelimitset'],true);
    			$voucherInfoList[$key]['usestarttime'] = strtotime($usetimelimitset['usebegintime'].'00:00');
    			$voucherInfoList[$key]['useendtime'] = strtotime($usetimelimitset['useendtime'].'23:59');
    		}elseif($val['usetimelimittype'] == 3){
    			$voucherInfoList[$key]['usestarttime'] = strtotime($val['usetimelimitset'].'00:00');
    			$voucherInfoList[$key]['useendtime'] = strtotime($val['usetimelimitset'].'23:59');
    		}
			//投放量
			$voucherInfoList[$key]['allNum'] = $val['deliverynum'];
			//核销量
			$voucherInfoList[$key]['useNum'] = $val['verificationnum'];
			//核销率
			$voucherInfoList[$key]['useProbability'] = format_number(($voucherInfoList[$key]['useNum']/$voucherInfoList[$key]['allNum'])*100).'%';
		}
		$this->assign('voucherInfoList',$voucherInfoList);
		$this->assign('page',$page->show());
		$this->display();
	}
	/**
	 * 创建/编辑优惠券
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-11-7
	 */
	public function set(){
		if(IS_POST){
			$time = time();
			$id = $this->_post('id');
			$vouchertype = $this->_post('vouchertype');
			$title = $_POST['title'];
			$discounttype = $this->_post('discounttype');
			$minus = $this->_post('minus');
			$discount = $this->_post('discount');
			$fullminus = $this->_post('fullminus');
			$fulldiscount = $this->_post('fulldiscount');
			$eachfullminus = $this->_post('eachfullminus');
			$usetimelimittype = $this->_post('usetimelimittype');
			$voucherdesc = $this->_post('voucherdesc');
			$useshops = $this->_post('useshops');
			$alluseshops = $this->_post('alluseshops');
			$usescene = $this->_post('usescene');
			$allusescene = $this->_post('allusescene');
			$israndom = $this->_post('israndom');
			$parvalue = $this->_post('parvalue');
			$minparvalue = $this->_post('minparvalue');
			$maxparvalue = $this->_post('maxparvalue');
			$data['vouchertype'] = $vouchertype;
			$data['title'] = $title;
			$data['vouchercreatetype'] = 1;
			if($vouchertype == 1 || $vouchertype == 2 || $vouchertype == 3 || $vouchertype == 40){
				$data['discounttype'] = $discounttype;
				$data['minus'] = $minus;
				$data['discount'] = $discount;
				$data['fullminus'] = $fullminus;
				$data['fulldiscount'] = $fulldiscount;
				$data['eachfullminus'] = $eachfullminus;
				$data['voucherdesc'] = $voucherdesc;
				$data['useshops'] = $useshops;
				$data['alluseshops'] = $alluseshops;
				$data['usescene'] = $usescene;
				$data['allusescene'] = $allusescene;
				$data['usetimelimittype'] = $usetimelimittype;
				$data['usetimetype'] = $usetimelimittype; //将新数据存入老数据字段
				if($usetimelimittype == 1){
					$data['usetimelimitset']  = $this->_post('usedays');
					$data['usetimedeferred']  = $this->_post('usedays'); //将新数据存入老数据字段
				}else if($usetimelimittype == 2){
					$usebegintime = $this->_post('usebegintime');
					$useendtime = $this->_post('useendtime');
					$data['usetimelimitset'] = json_encode(array('usebegintime'=>$usebegintime,'useendtime'=>$useendtime));
					$data['usestarttime']  = strtotime($usebegintime.'00:00'); //将新数据存入老数据字段
					$data['useendtime']  = strtotime($useendtime.'23:59'); //将新数据存入老数据字段
				}else if($usetimelimittype == 3){
					$data['usetimelimitset'] = $this->_post('usetime');
					$data['usestarttime'] = strtotime($data['usetimelimitset'].'00:00'); //将新数据存入老数据字段
					$data['useendtime'] = strtotime($data['usetimelimitset'].'23:59'); //将新数据存入老数据字段
				}
			}else{
				$data['israndom'] = $israndom;
				$data['parvalue'] = $parvalue;
				$data['minparvalue'] = $minparvalue;
				$data['maxparvalue'] = $maxparvalue;
			}
			if($id){
				$data['updatetime'] = $time;
				$voucherSave = M('member_marketing_activities_voucher_info')->where(array('companyid'=>$this->companyid,'id'=>$id))->save($data);
			}else{
				$data['id'] = guidNow();
				$data['companyid'] = $this->companyid;
				$data['createtime'] = $data['updatetime'] = $time;
				$voucherSave = M('member_marketing_activities_voucher_info')->add($data);
			}
			if($voucherSave){
				$info['code'] = 200;
				$info['tips'] = '操作成功';
			}else{
				$info['code'] = 300;
				$info['tips'] = '操作失败';
			}
			echo json_encode($info);
		}else{
			$vouchertype = $this->_get('vouchertype');
			$info = '';
			$id = $this->_get('id');
			if($id){
				$info = M('member_marketing_activities_voucher_info')->where(array('companyid'=>$this->companyid,'id'=>$id))->field('id,title,vouchertype,useshops,alluseshops,usescene,allusescene,voucherdesc,useissite,discounttype,minus,discount,fullminus,fulldiscount,eachfullminus,usetimelimittype,usetimelimitset,parvalue,minparvalue,maxparvalue,israndom')->find();
				if($info['usetimelimittype'] == 1){
					$info['usedays'] = $info['usetimelimitset'];
					$info['usestarttime'] = time();
					$info['useendtime2'] = strtotime('+'.$info['usetimelimitset'].' day');
				}elseif($info['usetimelimittype'] == 2){
					$usetimelimitset = json_decode($info['usetimelimitset'],true);
					$info['usebegintime'] = $usetimelimitset['usebegintime'];
					$info['useendtime'] = $usetimelimitset['useendtime'];
					$info['usestarttime'] = strtotime($usetimelimitset['usebegintime'].'00:00');
					$info['useendtime2'] = strtotime($usetimelimitset['useendtime'].'23:59');
				}elseif($info['usetimelimittype'] == 3){
					$info['usetime'] = $info['usetimelimitset'];
					$info['usestarttime'] = strtotime($usetimelimitset['usetimelimitset'].'00:00');
					$info['useendtime2'] = strtotime($usetimelimitset['usetimelimitset'].'23:59');
				}
				if($info['discounttype'] == 3){
					$disList = explode(',',$info['fullminus']);
					$info['fullminus1'] = $disList[0];
					$info['fullminus2'] = $disList[1];
				}elseif($info['discounttype'] == 4){
					$disList = explode(',',$info['fulldiscount']);
					$info['fulldiscount1'] = $disList[0];
					$info['fulldiscount2'] = $disList[1];
				}elseif($info['discounttype'] == 5){
					$disList = explode(',',$info['eachfullminus']);
					$info['eachfullminus1'] = $disList[0];
					$info['eachfullminus2'] = $disList[1];
				}
				if($info['vouchertype'] == 1){
					$this->checkCompanyScrm5Permissions(36,TRUE);
					$this->makeTopUrl = $this->makeTopUrl_User(array($this->CRM_WELCOME,array('name'=>'卡券中心','url'=>U('Vouchers/center'),'rel'=>'','target'=>''),array('name'=>'eshop优惠券','url'=>U('MemberMarketingActivitiesVoucherInfo/index',array('vouchertype'=>1)),'rel'=>'','target'=>''),array('name'=>'编辑eshop优惠券','url'=>'','rel'=>'','target'=>'')));
				}elseif($info['vouchertype'] == 2){
					$this->checkCompanyScrm5Permissions(37,TRUE);
					$this->makeTopUrl = $this->makeTopUrl_User(array($this->CRM_WELCOME,array('name'=>'卡券中心','url'=>U('Vouchers/center'),'rel'=>'','target'=>''),array('name'=>'门店使用优惠券','url'=>U('MemberMarketingActivitiesVoucherInfo/index',array('vouchertype'=>2)),'rel'=>'','target'=>''),array('name'=>'编辑门店使用优惠券','url'=>'','rel'=>'','target'=>'')));
				}elseif($info['vouchertype'] == 3){
					$this->checkCompanyScrm5Permissions(38,TRUE);
					$this->makeTopUrl = $this->makeTopUrl_User(array($this->CRM_WELCOME,array('name'=>'卡券中心','url'=>U('Vouchers/center'),'rel'=>'','target'=>''),array('name'=>'兑换券','url'=>U('MemberMarketingActivitiesVoucherInfo/index',array('vouchertype'=>3)),'rel'=>'','target'=>''),array('name'=>'编辑兑换券','url'=>'','rel'=>'','target'=>'')));
				}elseif($info['vouchertype'] == 4){
					$this->checkCompanyScrm5Permissions(39,TRUE);
					$this->makeTopUrl = $this->makeTopUrl_User(array($this->CRM_WELCOME,array('name'=>'卡券中心','url'=>U('Vouchers/center'),'rel'=>'','target'=>''),array('name'=>'红包','url'=>U('MemberMarketingActivitiesVoucherInfo/index',array('vouchertype'=>4)),'rel'=>'','target'=>''),array('name'=>'编辑红包','url'=>'','rel'=>'','target'=>'')));
				}elseif($info['vouchertype'] == 40){
					$this->checkCompanyScrm5Permissions(129,TRUE);
					$this->makeTopUrl = $this->makeTopUrl_User(array($this->CRM_WELCOME,array('name'=>'卡券中心','url'=>U('Vouchers/center'),'rel'=>'','target'=>''),array('name'=>'通用券','url'=>U('MemberMarketingActivitiesVoucherInfo/index',array('vouchertype'=>40)),'rel'=>'','target'=>''),array('name'=>'编辑通用券','url'=>'','rel'=>'','target'=>'')));
				}
			}else{
				$info['vouchertype'] = $vouchertype;
				$info['title'] = $title;
				$info['discounttype'] = '1';
				$info['usetimelimittype'] = '1';
				if($info['vouchertype'] == 1){
					$this->checkCompanyScrm5Permissions(36,TRUE);
					$this->makeTopUrl = $this->makeTopUrl_User(array($this->CRM_WELCOME,array('name'=>'卡券中心','url'=>U('Vouchers/center'),'rel'=>'','target'=>''),array('name'=>'eshop优惠券','url'=>U('MemberMarketingActivitiesVoucherInfo/index',array('vouchertype'=>1)),'rel'=>'','target'=>''),array('name'=>'新建eshop优惠券','url'=>'','rel'=>'','target'=>'')));
				}elseif($info['vouchertype'] == 2){
					$this->checkCompanyScrm5Permissions(37,TRUE);
					$this->makeTopUrl = $this->makeTopUrl_User(array($this->CRM_WELCOME,array('name'=>'卡券中心','url'=>U('Vouchers/center'),'rel'=>'','target'=>''),array('name'=>'门店使用优惠券','url'=>U('MemberMarketingActivitiesVoucherInfo/index',array('vouchertype'=>2)),'rel'=>'','target'=>''),array('name'=>'新建门店使用优惠券','url'=>'','rel'=>'','target'=>'')));
				}elseif($info['vouchertype'] == 3){
					$this->checkCompanyScrm5Permissions(38,TRUE);
					$this->makeTopUrl = $this->makeTopUrl_User(array($this->CRM_WELCOME,array('name'=>'卡券中心','url'=>U('Vouchers/center'),'rel'=>'','target'=>''),array('name'=>'兑换券','url'=>U('MemberMarketingActivitiesVoucherInfo/index',array('vouchertype'=>3)),'rel'=>'','target'=>''),array('name'=>'新建兑换券','url'=>'','rel'=>'','target'=>'')));
				}elseif($info['vouchertype'] == 4){
					$this->checkCompanyScrm5Permissions(39,TRUE);
					$this->makeTopUrl = $this->makeTopUrl_User(array($this->CRM_WELCOME,array('name'=>'卡券中心','url'=>U('Vouchers/center'),'rel'=>'','target'=>''),array('name'=>'红包','url'=>U('MemberMarketingActivitiesVoucherInfo/index',array('vouchertype'=>4)),'rel'=>'','target'=>''),array('name'=>'新建红包','url'=>'','rel'=>'','target'=>'')));
				}elseif($info['vouchertype'] == 40){
					$this->checkCompanyScrm5Permissions(129,TRUE);
					$this->makeTopUrl = $this->makeTopUrl_User(array($this->CRM_WELCOME,array('name'=>'卡券中心','url'=>U('Vouchers/center'),'rel'=>'','target'=>''),array('name'=>'通用券','url'=>U('MemberMarketingActivitiesVoucherInfo/index',array('vouchertype'=>40)),'rel'=>'','target'=>''),array('name'=>'新建通用券','url'=>'','rel'=>'','target'=>'')));
				}
				$info['israndom'] = '0';
				$info['parvalue'] = '0.00';
				$info['minparvalue'] = '0.00';
				$info['maxparvalue'] = '0.00';
			}
			/* $info['shops'] = $this->companyShopModel->getWhereCompanyShopsList(array('companyid'=>$this->companyid));
			if($info['vouchertype'] ==1 || $info['vouchertype'] ==2){
				//列出券池类
				$voucherPoolCat = M('member_voucher_pool_cat')->where(array('companyid'=>$this->companyid))->field('id,name')->select();
				if($voucherPoolCat){
					$voucherPoolModel = M('member_voucher_pool');
					foreach ($voucherPoolCat as $pcKey=>$pcVal){
						$voucherPoolCat[$pcKey]['num'] = $voucherPoolModel->where(array('companyid'=>$this->companyid,'cid'=>$pcVal['id'],'issend'=>2))->count();
					}
				}
				$info['poolCatList'] = $voucherPoolCat;
			}  */
			$shops = M('company_shops')->where(array('companyid'=>$this->companyid))->field('id,name,shopname')->select();
			$this->assign('shops',$shops);
			$this->assign('info',$info);
			//$this->publicSelectUrl();
			$this->display();
		}
	}
	/**
	 * 删除优惠券
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-11-8
	 */
	public function ajaxDel(){
		$where['companyid'] = $this->companyid;
		$where['id'] = $this->_post('id');
		$vInfo = M('member_marketing_activities_voucher_info')->where($where)->field('id,vouchertype')->find();
		$option['companyid'] = $this->companyid;
		$option['vouchertype'] = $vInfo['vouchertype'];
		$option['voucherid'] = $vInfo['id'];
		$result = $this->vouchersIsLink($option);
		if($result['code'] == 200){
			$return['code'] = '400';
			if($result['scrmLinkVoucherCount']){
				$scrmtips = '"CRM活动-'.$result['scrmtitle'].'"';
			}
			if($result['memberCardRankVoucherCount']){
				$ranktips = '"会员等级权益"';
			}
			if($result['voucherBagCount']){
				$bagtips = '"卡券礼包-'.$result['goodtitle'].'"';
			}
			$return['tips'] = '该卡券已被关联到'.$scrmtips.$ranktips.$bagtips.'，不允许删除。';
			echo json_encode($return);exit();
		}else{
			$delInfoReturn = M('member_marketing_activities_voucher_info')->where($where)->delete();
			//将关联SCRM活动关联券设置isdel = 1
			$scrmLinkVoucherCount = M('member_marketing_activities_scrm_link_voucher')->where(array('companyid'=>$this->companyid,'voucherid'=>$vInfo['id'],'vouchertype'=>$vInfo['vouchertype']))->count();
			if($scrmLinkVoucherCount){
				$scrmData['isdel'] = 1;
				$scrmData['updatetime'] = time();
				$scrmLinkVoucherReturn = M('member_marketing_activities_scrm_link_voucher')->where(array('companyid'=>$this->companyid,'voucherid'=>$vInfo['id'],'vouchertype'=>$vInfo['vouchertype']))->save($scrmData);
			}else{
				$scrmLinkVoucherReturn = 1;
			}
			//将关联的券的商品下架
			$voucherCount = M('mall_goods')->where(array('companyid'=>$this->companyid,'vouchersid'=>$vInfo['id']))->count();
			if($voucherCount){
				$data['isoffshelves'] = '1';
				$data['updatetime'] = time();
				$voucherDelReturn = M('mall_goods')->where(array('companyid'=>$this->companyid,'vouchersid'=>$vInfo['id']))->save($data);
			}else{
				$voucherDelReturn = 1;
			}
			//卡券礼包商品
			$vouherBagCount = M('mall_goods_vouchers_bag')->where(array('companyid'=>$this->companyid,'voucherid'=>$vInfo['id']))->count();
			if($vouherBagCount){
				$vouherBagReturn = M('mall_goods_vouchers_bag')->where(array('companyid'=>$this->companyid,'voucherid'=>$vInfo['id'],'vouchertype'=>$vInfo['vouchertype']))->delete();
				//将关联的卡券礼包商品下架
				$goodid = M('mall_goods_vouchers_bag')->where(array('companyid'=>$this->companyid,'voucherid'=>$vInfo['id']))->getField('goodid');
				dump($goodid);
				$vouherBagData['isoffshelves'] = '1';
				$vouherBagData['updatetime'] = time();
				$goodReturn = M('mall_goods')->where(array('companyid'=>$this->companyid,'id'=>$goodid))->save(vouherBag);
			}else{
				$vouherBagReturn = 1;
			}
		}
		if($delInfoReturn && $scrmLinkVoucherReturn && $voucherDelReturn && $vouherBagReturn){
			$return['code'] = '200';
			$return['tips'] = '操作成功';
		}else{
			$return['code'] = '300';
			$return['tips'] = '操作失败';
		}
		echo json_encode($return);
	}
	/**
	 * 投放记录
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-11-8
	 */
	public function deliveryRecord(){
		$id = $this->_request('id');
		$this->assign('id',$id);
		//券号
		$sn = $this->_request('sn');
		if($sn){
			$where['vouchers.sn'] = array('like','%'.$sn.'%');
			$this->assign('sn',$sn);
		}
		//手机号
		$moblie = $this->_request('moblie');
		if($moblie){
			$where['info.moblie'] = array('like','%'.$moblie.'%');
			$this->assign('moblie',$moblie);
		}
		//核销状态
		$isusedStatus = $this->_request('isusedStatus');
		if($isusedStatus){
			if($isusedStatus == 1){
				$where['vouchers.isused'] = '2';
				$where['vouchers.useendtime'] = array('gt',time());
			}elseif($isusedStatus == 2){
				$where['vouchers.isused'] = '1';
			}elseif($isusedStatus == 3){
				$where['vouchers.useendtime'] = array('lt',time());
			}
			$this->assign('isusedStatus',$isusedStatus);
		}
		//核销人姓名
		$staffname = $this->_request('staffname');
		if($staffname){
			$where['uvouchers.staffname'] = array('like','%'.$staffname.'%');
			$this->assign('staffname',$staffname);
		}
		//核销门店
		$handleshopid = $this->_request('handleshopid');
		if($handleshopid){
			$where['uvouchers.shopid'] = $handleshopid;
			$this->assign('handleshopid',$handleshopid);
		}
		$voucherInfo = M('member_marketing_activities_voucher_info')->where(array('companyid'=>$this->companyid,'id'=>$id))->field('id,title,vouchertype,useshops,discounttype,minus,discount,fullminus,fulldiscount,eachfullminus')->find();
		if($voucherInfo['discounttype'] == 1){
        	$voucherInfo['useful'] = '立减'.$voucherInfo['minus'].'元';
       	}elseif($voucherInfo['discounttype'] == 2){
    		$voucherInfo['useful'] = '立折'.$voucherInfo['discount'].'%';
		}elseif($voucherInfo['discounttype'] == 3){
			$disList = explode(',',$voucherInfo['fullminus']);
			$voucherInfo['useful'] = '满'.$disList[0].'元立减'.$disList[1].'元';
		}elseif($voucherInfo['discounttype'] == 4){
			$disList = explode(',',$voucherInfo['fulldiscount']);
			$voucherInfo['useful'] = '满'.$disList[0].'元立折'.$disList[1].'%';
		}elseif($voucherInfo['discounttype'] == 5){
			$disList = explode(',',$voucherInfo['eachfullminus']);
			$voucherInfo['useful'] = '每满'.$disList[0].'元立减'.$disList[1].'元';
     	}
		if($voucherInfo['useshops']){
			$shopList = M('company_shops')->where(array('companyid'=>$this->companyid,'id'=>array('in',$voucherInfo['useshops'])))->field('id,shopname')->select();
			$this->assign('shopList',$shopList);
		}
		if($voucherInfo['vouchertype'] == 1){
			$this->checkCompanyScrm5Permissions(36,TRUE);
			$this->makeTopUrl = $this->makeTopUrl_User(array($this->CRM_WELCOME,array('name'=>'卡券中心','url'=>U('Vouchers/center'),'rel'=>'','target'=>''),array('name'=>'eshop优惠券','url'=>U('MemberMarketingActivitiesVoucherInfo/index',array('vouchertype'=>1)),'rel'=>'','target'=>''),array('name'=>'投放记录','url'=>'','rel'=>'','target'=>'')));
		}elseif($voucherInfo['vouchertype'] == 2){
			$this->checkCompanyScrm5Permissions(37,TRUE);
			$this->makeTopUrl = $this->makeTopUrl_User(array($this->CRM_WELCOME,array('name'=>'卡券中心','url'=>U('Vouchers/center'),'rel'=>'','target'=>''),array('name'=>'门店使用优惠券','url'=>U('MemberMarketingActivitiesVoucherInfo/index',array('vouchertype'=>2)),'rel'=>'','target'=>''),array('name'=>'投放记录','url'=>'','rel'=>'','target'=>'')));
		}elseif($voucherInfo['vouchertype'] == 3){
			$this->checkCompanyScrm5Permissions(38,TRUE);
			$this->makeTopUrl = $this->makeTopUrl_User(array($this->CRM_WELCOME,array('name'=>'卡券中心','url'=>U('Vouchers/center'),'rel'=>'','target'=>''),array('name'=>'兑换券','url'=>U('MemberMarketingActivitiesVoucherInfo/index',array('vouchertype'=>3)),'rel'=>'','target'=>''),array('name'=>'投放记录','url'=>'','rel'=>'','target'=>'')));
		}elseif($voucherInfo['vouchertype'] == 4){
			$this->checkCompanyScrm5Permissions(39,TRUE);
			$this->makeTopUrl = $this->makeTopUrl_User(array($this->CRM_WELCOME,array('name'=>'卡券中心','url'=>U('Vouchers/center'),'rel'=>'','target'=>''),array('name'=>'红包','url'=>U('MemberMarketingActivitiesVoucherInfo/index',array('vouchertype'=>4)),'rel'=>'','target'=>''),array('name'=>'投放记录','url'=>'','rel'=>'','target'=>'')));
		}
		$where['vouchers.companyid'] = $this->companyid;
		$where['vouchers.voucherinfoid'] = $id;
		$count = M()->table('tp_member_vouchers as vouchers')->join(array("LEFT JOIN tp_member_register_info as info ON info.id  = vouchers.mid"))->where($where)->count();
		$page = new NewPage($count,15);
		$list = M()->table('tp_member_vouchers as vouchers')->join(array("LEFT JOIN tp_member_register_info as info ON info.id  = vouchers.mid"))->where($where)->field('vouchers.id,vouchers.mid,vouchers.parvalue,vouchers.sn,vouchers.isused,vouchers.usestarttime,vouchers.useendtime,vouchers.createtime,info.moblie,info.name')->order('vouchers.createtime desc')->limit($page->firstRow.','.$page->listRows)->select();
		foreach($list as $key=>$val){
			$useVoucherInfo = M('use_vouchers')->where(array('companyid'=>$this->companyid,'vouchernumber'=>$val['sn']))->field('usetime,staffname,shopid')->find();
			$list[$key]['usetime'] = $useVoucherInfo['usetime'];
			$list[$key]['staffname'] = $useVoucherInfo['staffname'];
			if($useVoucherInfo['shopid'] == '-1'){
				$list[$key]['shopname'] = '总部';
			}else{
				$list[$key]['shopname'] = M('company_shops')->where(array('id'=>$useVoucherInfo['shopid']))->getField('shopname');
			}
		}
		$this->assign('list',$list);
		$this->assign('page',$page->show());
		$this->assign('voucherInfo',$voucherInfo);
		$this->display();
	}
	/**
	 * 冻结本券
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-11-9
	 */
	public function ajaxFreeze(){
		C('TOKEN_ON',false);
		$where['id'] = $this->_post('id');
		$data['isused'] = 3;
		$data['updatetime'] = time();
		$vouchersReturn = M('member_vouchers')->where($where)->save($data);
		if($vouchersReturn){
			$data['code'] = '200';
		}else {
			$data['code'] = '300';
		}
		echo json_encode($data);
	}
	/**
	 * 解冻本券
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-11-9
	 */
	public function ajaxThaw(){
		C('TOKEN_ON',false);
		$where['id'] = $this->_post('id');
		$data['isused'] = 2;
		$data['updatetime'] = time();
		$vouchersReturn = M('member_vouchers')->where($where)->save($data);
		if($vouchersReturn){
			$data['code'] = '200';
		}else {
			$data['code'] = '300';
		}
		echo json_encode($data);
	}
	/**
	 * 导出CSV
	 * @author Tomas<416369046@qq.com>
	 * @since  2016-11-9
	 */
	public function exportCsv(){
		$id = $this->_request('id');
		//券号
		$sn = $this->_request('sn');
		if($sn){
			$where['vouchers.sn'] = array('like','%'.$sn.'%');
		}
		//手机号
		$moblie = $this->_request('moblie');
		if($moblie){
			$where['info.moblie'] = array('like','%'.$moblie.'%');
		}
		//核销状态
		$isusedStatus = $this->_request('isusedStatus');
		if($isusedStatus){
			if($isusedStatus == 1){
				$where['vouchers.isused'] = '2';
				$where['vouchers.useendtime'] = array('gt',time());
			}elseif($isusedStatus == 2){
				$where['vouchers.isused'] = '1';
			}elseif($isusedStatus == 3){
				$where['vouchers.useendtime'] = array('lt',time());
			}
		}
		$where['vouchers.companyid'] = $this->companyid;
		$where['vouchers.voucherinfoid'] = $id;
		$voucherInfo = M('member_marketing_activities_voucher_info')->where(array('companyid'=>$this->companyid,'id'=>$id))->field('id,title,vouchertype')->find();
		$list = M()->table('tp_member_vouchers as vouchers')->join(array("LEFT JOIN tp_member_register_info as info ON info.id  = vouchers.mid","LEFT JOIN tp_use_vouchers as uvouchers ON uvouchers.vouchernumber = vouchers.sn"))->where($where)->field('vouchers.id,vouchers.mid,vouchers.sn,vouchers.isused,vouchers.usestarttime,vouchers.useendtime,vouchers.createtime,info.moblie,info.name,uvouchers.usetime')->order('uvouchers.usetime desc,vouchers.updatetime desc')->select();
		if($voucherInfo['vouchertype'] == 1){
			$csvVoucherName = 'eshop优惠券名称';
		}elseif($voucherInfo['vouchertype'] == 2){
			$csvVoucherName = '门店使用优惠券名称';
		}elseif($voucherInfo['vouchertype'] == 3){
			$csvVoucherName = '兑换券名称';
		}elseif($voucherInfo['vouchertype'] == 4){
			$csvVoucherName = '红包名称';
		}
		$content = "卡券号,".$csvVoucherName.",投放时间,会员姓名,会员手机,核销状态,核销时间\r\n";
		if($list){
			foreach($list as $key=>$val){
				$content .= $val['sn'].','.$voucherInfo['title'].',';
				$content .= format_time($val['createtime'],'ymdhi').',';
				//$content .= '"'.format_time($val['createtime'],'ymdhi').'",';
				$content .= $val['name'].','.$val['moblie'].',';
				if($val['isused'] == 1){
					$content .= '已使用,';
				}else{
					if($val['useendtime'] < time()){
						$content .= '已过期,';
					}else{
						if($val['isused'] == 2){
							$content .= '未使用,';
						}elseif($val['isused'] == 3){
							$content .= '已冻结,';
						}
					}
				}
				$content .= format_time($val['usetime'],'ymdhi')."\n";
			}
		}
		$date = date("YmdHis",time());
		$fileName .= $voucherInfo['title']."_{$date}.csv";
		$fileName = iconv("utf-8", "GBK", $fileName); //转化编码，否则会出现乱码
		$content = iconv("utf-8", "GBK//IGNORE", $content); //转化编码，否则会出现乱码
		header("Content-type:text/csv");
		header("Content-Disposition:attachment;filename=".$fileName);
		header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Expires:0');
		header('Pragma:public');
		echo $content;
	}
	/**
	 * 异步更新使用门店
	 * @author 徐建鹏<tomas@mobiwind.cn>
	 * @since  2017-3-7
	 */
	public function ajaxUpdateUseShops(){
		$time = time();
		$id = $this->_post('id');
		$vInfo = M('member_marketing_activities_voucher_info')->where(array('companyid'=>$this->companyid,'id'=>$id))->field('id,companyid,useshops')->find();
		$voucherData['useshopslimitset'] = $vInfo['useshops'];
		$voucherData['updatetime'] = $time;
		$where['companyid'] = $this->companyid;
		$where['voucherinfoid'] = $id;
		$where['isused'] = array('in','2,3');
		$where['useendtime'] = array('gt',$time);
		$count = M('member_vouchers')->where($where)->count();
		$result = M('member_vouchers')->where($where)->save($voucherData);
		if($count){
			if($result){
				$return['code'] = 200;
				$return['tips'] = '更新成功';
			}else{
				$return['code'] = 300;
				$return['tips'] = '更新失败';
			}
		}else{
			$return['code'] = 400;
			$return['tips'] = '该卡券暂未投放，无需更新';
		}
		echo json_encode($return);
	}
}
?>