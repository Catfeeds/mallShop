<?php
/**
 * 收货地址
 * Enter description here ...
 * @author yaochengkai
 */
class MemberShopAddressAction extends WapBaseAction{
	
	private $mid;
	
	public function __construct(){
		parent::__construct();
		$this->mid = session('mid'.session('wapcid'));
		$this->assign('isshowsystemdiymen','2'); //系统自定义菜单 1：显示；2：不显示；
	}
	/**
	 * 收货地址
	 */
	public function index(){
		$this->setPageTitle(array('title'=>'我的收货地址'));
		$list = M('member_shop_address')->where(array('mid'=>$this->mid))->field('id,name,mobile,province,city,district,address,isdefault')->order('id DESC')->select();
		if($list){
			foreach($list as $key=>$val){
				$isdefault = $val['isdefault']==1 ? '【默认地址】' : '';
				$list[$key]['address'] = $isdefault.$val['province'].$val['city'].$val['district'].$val['address'];
			}
		}
		$this->assign('list',$list);
		$this->display();
	}
	/**
	 * 创建/编辑收货地址
	 */
	public function set(){
		if(IS_POST){
			$time = time();
			$id = $this->_post('id');
			M()->startTrans();
			// 获得经纬度
			// if($_POST['city'] && $_POST['district'] && $_POST['address']){
			    // $address = $_POST['city'].$_POST['district'].$_POST['address'];
				// dump($address).'1';
			    // $url = 'http://apis.map.qq.com/ws/geocoder/v1/?address='.$address.'&key=J3IBZ-AIYHQ-C4I5S-GOYMW-BRGQQ-NZFP7';
			    // $addJson = http_get($url);
			    // $addData = json_decode($addJson, true);
				// dump($addData).'2';
			    // if($addData['status'] == '0'){
			        // $_POST['lng'] = $addData['result']['location']['lng'];
			        // $_POST['lat'] = $addData['result']['location']['lat'];
			        // $isEdit = 1;
			    // }else{
			        // $isEdit = 2;
			    // }
			// }
			$isEdit = 1;
			$_POST['lng'] = 1;
			$_POST['lat'] = 1;
			if($isEdit == 1){
				$_POST['isdefault'] = 1;
    			if($id){
    				$add = M('Member_shop_address')->save($_POST);
    				$resultid = $id;
    			}else{
    				$_POST['mid'] = $this->mid;
    				$_POST['createtime'] = $time;
    				$add = M('Member_shop_address')->add($_POST);
    				$resultid = $add;
    			}
    			// 设置为默认收货地址
    			if($_POST['isdefault'] == 1){
    				M('member_shop_address')->where(array('mid'=>$this->mid,'id'=>array('neq', $resultid)))->setField('isdefault', 2);
    				M('member_register_info')->where(array('id'=>$this->mid))->setField('address', $_POST['province'].$_POST['city'].$_POST['district'].$_POST['address']);
    			}
				$registerReturn = 1;
    			// 个人收货地址（用于地址筛选）
    			// $list = M('member_shop_address')->where(array('mid'=>$this->mid))->field('name,mobile,province,city,district,address')->select();
    			// if($list){ 
    				// foreach($list as $key=>$val){
    					// $allshopaddress .= $val['name'].' '.$val['mobile'].' '.$val['province'].$val['city'].$val['district'].$val['address'].';';
    				// }
    				// $registerReturn = M('member_register_info')->where(array('id'=>$this->mid))->save(array('allshopaddress'=>$allshopaddress,'updatetime'=>time()));
    			// }
			}
			if($add && $registerReturn){
				M()->commit();
				$message['code'] = 200;
				$message['tips'] = '操作成功';
			}else{
				M()->rollback();
				$message['code'] = 300;
				if($isEdit == 2){
				    $message['tips'] = '暂时无法识别您的地址，请稍后再试！';
				}else{
				    $message['tips'] = '操作失败';
				}
			}
			echo json_encode($message);
		}else{
			$this->setPageTitle(array('title'=>'我的收货地址'));
			$id = $this->_get('addressid');
			if($id){
				$addressInfo = M('member_shop_address')->where(array('mid'=>$this->mid,'id'=>$id))->field('id,name,mobile,province,city,district,address,areacode,isdefault')->find();
				$addressInfo['area'] = $addressInfo['province'].' '.$addressInfo['city'].' '.$addressInfo['district'];
				//$addressInfo['areacode'] = explode(",", $addressInfo['areacode']);
				$this->assign('addressInfo',$addressInfo);
			}
			//dump($addressInfo);
			$this->display();
		}
	}
	/**
	 * 删除收货地址
	 */
	public function del(){
		$where['mid'] = $this->mid;
		$where['id'] = $this->_post('id','intval');
		$defaultReturn = M('member_shop_address')->where(array('id'=>$where['id'],'isdefault'=>1))->field('id')->find();
		if($defaultReturn){
			$data['address'] = '';
			M('member_register_info')->where(array('id'=>$this->mid))->save($data);
			$info = M('member_register_info')->where(array('id'=>$this->mid))->field('name,gender,moblie,email,address,birthday')->find();
			if($info['name']){
				$num += '1';
			}
			if($info['gender']){
				$num += '1';
			}
			if($info['moblie']){
				$num += '1';
			}
			if($info['email']){
				$num += '1';
			}
			if($info['birthday']){
				$num += '1';
			}
			$data['percent'] = number_format(($num/6),2)*100;
			M('member_register_info')->where(array('id'=>$this->mid))->save($data);
		}
		$delReturn = M('member_shop_address')->where($where)->delete();
		if($delReturn){
			$list = D('Member_shop_address')->getMemberShopAddressList(array('mid'=>$this->mid));
			if($list){
				foreach($list as $key=>$val){
					$allshopaddress .= $val['name'].' '.$val['mobile'].' '.$val['country'].$val['province'].$val['city'].$val['address'].';';
				}
				M('member_register_info')->where(array('id'=>$this->mid))->save(array('allshopaddress'=>$allshopaddress));
			}
			$message['code'] = 200;
			$message['tips'] = '已成功删除';
		}else{
			$message['code'] = 300;
			$message['tips'] = '服务器繁忙';
		}
		echo json_encode($message);
	}
	/**
	 * ajax 设置为默认地址
	 */
	public function	isdefault(){
		M()->startTrans();
		$id = $this->_post('id');
		$saves = M('member_shop_address')->where(array('mid'=>$this->mid))->save(array('isdefault'=>2,'updatetime'=>time()));
		$save = M('member_shop_address')->where(array('mid'=>$this->mid,'id'=>$id))->save(array('isdefault'=>1,'updatetime'=>time()));
		$info = M('member_register_info')->where(array('id'=>$this->mid))->field('id,name,gender,moblie,email,address,birthday,issend100expint')->find();
		if($info['name']){
			$num += '1';
		}
		if($info['gender']){
			$num += '1';
		}
		if($info['moblie']){
			$num += '1';
		}
		if($info['birthday']){
			$num += '1';
		}
		$registerdata['percent'] = number_format(($num/4),2)*100;
		$registerdata['address'] = $this->_post('address');
		$registerdata['updatetime'] = time();
		if($registerdata['percent']==100 && $info['issend100expint'] == 2){
			//获取开卡积分
			$memberIntegralSet = D('Member_integral_set')->getMemberIntegralSetInfo();
			if ($memberIntegralSet['perfectreginfoisopen'] == '1'){
				$borderid = get_order_id();
				$data['adduid'] = $data['edituid'] = 0;
				$data['shopsid'] = 0;
				$data['mid'] = $this->mid;
				$data['status'] = '1';
				$data['updatetime'] = $data['createtime'] = time();
				$types = ',';
				$expNum = $memberIntegralSet['perfectreginfoexp'];
				if($expNum>0){
					$experienceData = $data;
					$experienceData['borderid'] =  $borderid;
					$experienceData['orderid'] =  get_order_id();
					$experienceData['type'] =  '1';
					$types .= 'e1,';
					$experienceData['experiencevalue'] =  $expNum;
					$perfectreginfoexperienceChange = M('member_experiencevalue')->add($experienceData);
					$perfectreginforegisterExperienceChange = M('member_register_info')->where(array('id'=>$this->mid))->setInc('totalexperiencevalue',$expNum);
				}else{
					$perfectreginfoexperienceChange = $perfectreginforegisterExperienceChange = 1;
				}
				$intNum = $memberIntegralSet['perfectreginfoint'];
				if($intNum>0){
					$integralData = $data;
					$integralData['borderid'] =  $borderid;
					$integralData['orderid'] =  get_order_id();
					$integralData['type'] =  '1';
					$types .= 'i1,';
					$integralData['integralnum'] =  $intNum;
					$perfectreginfointegralChange = M('member_integral')->add($integralData);
					$perfectreginforegisterIntegralChange = M('member_register_info')->where(array('id'=>$this->mid ))->setInc('totalintegration',$intNum);
				}else{
					$perfectreginfointegralChange = $perfectreginforegisterIntegralChange = 1;
				}
				$bdata=$data;
				$bdata['orderid'] = $borderid;
				$bdata['businesstype'] = '4';
				$bdata['types'] = $types;
				$perfectreginfobusinessReturn = M('member_business')->add($bdata);
				$registerdata['issend100expint'] = 1;
			}else{
				$perfectreginfobusinessReturn = $perfectreginfoexperienceChange = $perfectreginforegisterExperienceChange = $perfectreginfointegralChange = $perfectreginforegisterIntegralChange = 1;
			}
		}else{
			$perfectreginfobusinessReturn = $perfectreginfoexperienceChange = $perfectreginforegisterExperienceChange = $perfectreginfointegralChange = $perfectreginforegisterIntegralChange = 1;
		}
		$success = M('member_register_info')->where(array( 'id'=>$this->mid))->save($registerdata);
		if($saves && $save&&$success && $perfectreginfobusinessReturn && $perfectreginfoexperienceChange && $perfectreginforegisterExperienceChange && $perfectreginfointegralChange && $perfectreginforegisterIntegralChange){
			M()->commit();
			if($memberIntegralSet['perfectreginfoisopen'] == '1' && $intNum>0 && $perfectreginforegisterIntegralChange && $registerdata['issend100expint'] == 1){
			    $openid = M('member_wechat_info')->where(array( 'mid'=>$this->mid))->getField('openid');
			    $totalintegration1 = M('member_register_info')->where(array('id'=>$this->mid ))->getField('totalintegration');
			    $this->WeChatTemplateMessageSend('3',$openid,'','','',array(format_time(time(),'ymdhis'),$intNum,'完善100%会员资料',$totalintegration1),'');
			}
			$message['code'] = 200;
			$message['tips'] = "设置成功";
		}else{
			M()->rollback();
			$message['code'] = 300;
			$message['tips'] = "设置失败";
		}
		echo json_encode($message);
	}
	/**
	 * ajax 获得省份
	 * Enter description here ...
	 */
	public function	getProvince(){
		$country = $this->_post('country');
		$id = M('area')->where(array('name'=>$country))->getField('id');
		$provinces = M('area')->where(array('isshow'=>1,'parentid'=>$id))->select();
		if($provinces){
			$message['code'] = 200;
			$message['html'] = '<option value="">请选择省份</option>';
			foreach ($provinces as $pKey=>$pVal ){
				$message['html'].="<option value='".$pVal['name']."'>".$pVal['name']."</option>";
			}
		}else{
			$message['code'] = 300;
			$message['html'] = '<option value="">请选择省份</option>';
		}
		echo json_encode($message);
	}
	/**
	 * ajax 获得城市
	 * Enter description here ...
	 */
	public function	getCity(){
		$province = $this->_post('province');
		/* if($province == '北京' || $province == '上海' || $province == '天津' || $province == '重庆'){
			if($province){
				$message['code'] = 200;
				$message['html'] ="<option value='".$province."'>".$province."</option>";
			}else{
				$message['code'] = 300;
				$message['html'] = '<option value="">请选择城市</option>';
			}
		}else{
			$id = M('area')->where(array('name'=>$province))->getField('id');
			$citys = M('area')->where(array('isshow'=>1,'parentid'=>$id))->select();
			if($citys){
				$message['code'] = 200;
				$message['html'] = '<option value="">请选择城市</option>';
				foreach ($citys as $cKey=>$cVal){
					$message['html'].="<option value='".$cVal['name']."'>".$cVal['name']."</option>";
				}
			}else{
				$message['code'] = 300;
				$message['html'] = '<option value="">请选择城市</option>';
			}
		} */
		$id = M('area')->where(array('name'=>$province))->getField('id');
		$citys = M('area')->where(array('isshow'=>1,'parentid'=>$id))->select();
		if($citys){
			$message['code'] = 200;
			$message['html'] = '<option value="">请选择城市</option>';
			foreach ($citys as $cKey=>$cVal){
				$message['html'].="<option value='".$cVal['name']."'>".$cVal['name']."</option>";
			}
		}else{
			$message['code'] = 300;
			$message['html'] = '<option value="">请选择城市</option>';
		}
		echo json_encode($message);
	}
	/**
	 * 下单时更改收货地址
	 */
	public function orderindex(){
		$this->setPageTitle(array('title'=>'我的收货地址'));
        $goodsid = $this->_get('goodsid');
		$this->assign('goodsid',$goodsid);
		$goodsskuid = $this->_get('goodsskuid');
		$this->assign('goodsskuid',$goodsskuid);
		$goodsnum = $this->_get('goodsnum');
		$this->assign('goodsnum',$goodsnum);
		$addressid = $this->_get('addressid');
		$this->assign('addressid',$addressid);
		$ordertype = $this->_get('ordertype');
		$this->assign('ordertype',$ordertype);
		$goodtype = $this->_get('goodtype');
		$this->assign('goodtype',$goodtype);
		$vouchersid = $this->_get('vouchersid');
		$this->assign('vouchersid', $vouchersid);
		$groupid = $this->_get('groupid');
		$this->assign('groupid',$groupid);
		$groupinfoid = $this->_get('groupinfoid');
		$this->assign('groupinfoid',$groupinfoid);
		$list = M('member_shop_address')->where(array( 'mid'=>$this->mid))->field('id,name,mobile,province,city,district,address,isdefault')->order('id DESC')->select();
		if($list){
			$opentionList = ''; //可选择地址
			$notOpentionList = ''; //不可选择地址
			// 整理商品id
			$goodsidArr = explode(',', $goodsid);
			foreach($goodsidArr as $arrKey=>$arrVal){
				$goodsidArrId = explode('|', $arrVal);
				if($goodsidArrId[0]){
					$goodsidArr2[] = $goodsidArrId[0];
				}
			}
			// 查询商品配送区域
			$tplArr = M('mall_goods')->where(array( 'id'=>array('in', array_unique($goodsidArr2)),'freighttype'=>3))->field('freighttplid')->select();
			if($tplArr){
				foreach($tplArr as $tplKey=>$tplVal){
					$tplArr[$tplKey] = M('mall_freight_tpl_child')->where(array( 'tplid'=>$tplVal['freighttplid']))->field('areanames')->select();
					foreach($tplArr[$tplKey] as $tplval2){
						$tplArrString[$tplKey] .= $tplval2['areanames'];
					}
				}
			}
			foreach($list as $key=>$val){
				$isdefault = $val['isdefault']==1 ? '【默认地址】' : '';
				$fuheNum = 0;
				foreach($tplArrString as $tkey=>$tval){
					// 判断地址是否符合运费模板要求
					if(strstr($tval, $val['city'])){
						$fuheNum++;
						continue;
					}
				}
				if($fuheNum == count($tplArrString)){
					$opentionList[$key]['id'] = $val['id'];
					$opentionList[$key]['name'] = $val['name'];
					$opentionList[$key]['mobile'] = $val['mobile'];
					$opentionList[$key]['address'] = $isdefault.$val['province'].$val['city'].$val['district'].$val['address'];
				}else{
					$notOpentionList[$key]['id'] = $val['id'];
					$notOpentionList[$key]['name'] = $val['name'];
					$notOpentionList[$key]['mobile'] = $val['mobile'];
					$notOpentionList[$key]['address'] = $isdefault.$val['province'].$val['city'].$val['district'].$val['address'];
				}
				unset($fuheNum);
			}
		}
		$this->assign('opentionList',$opentionList);
		$this->assign('notOpentionList',$notOpentionList);
		$this->display();
	}
	/**
	 * 创建/编辑收货地址
	 */
	public function orderaddressset(){
		if(IS_POST){
			$time = time();
			$id = $this->_post('id');
			
			M()->startTrans();
			$addre = explode(" ", $_POST['addre']);
			$_POST['province'] = $addre[0];
			$_POST['city'] = $addre[1];
			$_POST['district'] = $addre[2];
			$_POST['updatetime'] = $time;
			if($id){
				$add = M('Member_shop_address')->save($_POST);
				$resultid = $id;
			}else{
				$_POST['mid'] = $this->mid;
				$_POST['createtime'] = $time;
				$add = M('Member_shop_address')->add($_POST);
				$resultid = $add;
			}
			// 设置为默认收货地址
			if($_POST['isdefault'] == 1){
				M('member_shop_address')->where(array( 'mid'=>$this->mid,'id'=>array('neq', $resultid)))->setField('isdefault', 2);
			}
			// 个人收货地址（用于地址筛选）
			$list = M('member_shop_address')->where(array( 'mid'=>$this->mid))->field('name,mobile,province,city,district,address')->select();
			if($list){
				foreach($list as $key=>$val){
					$allshopaddress .= $val['name'].' '.$val['mobile'].' '.$val['province'].$val['city'].$val['district'].$val['address'].';';
				}
				$registerReturn = M('member_register_info')->where(array( 'id'=>$this->mid))->save(array('allshopaddress'=>$allshopaddress,'updatetime'=>time()));
			}
			if($add && $registerReturn){
				M()->commit();
				$message['code'] = 200;
				$message['id'] = $resultid;
				$message['tips'] = '创建成功';
			}else{
				M()->rollback();
				$message['code'] = 300;
				$message['tips'] = '创建失败';
			}
			echo json_encode($message);
		}else{
			$this->setPageTitle(array('title'=>'我的收货地址'));
			$ordertype = $this->_get('ordertype');
			$this->assign('ordertype',$ordertype);
			$goodsid = $this->_get('goodsid');
			$this->assign('goodsid',$goodsid);
			$goodsskuid = $this->_get('goodsskuid');
			$this->assign('goodsskuid',$goodsskuid);
			$goodsnum = $this->_get('goodsnum');
			$this->assign('goodsnum',$goodsnum);
			$goodtype = $this->_get('goodtype');
			$this->assign('goodtype',$goodtype);
			$groupid = $this->_get('groupid');
			$this->assign('groupid',$groupid);
			$groupinfoid = $this->_get('groupinfoid');
			$this->assign('groupinfoid',$groupinfoid);
			$info['countrys'] = D('Area')->getAreaList(array('parentid'=>0,'isshow'=>1));
			$this->assign('info',$info);
			$id = $this->_get('addressid');
			if($id){
				$addressInfo = M('member_shop_address')->where(array( 'mid'=>$this->mid,'id'=>$id))->field('id,name,mobile,province,city,district,address,areacode,isdefault')->find();
				$addressInfo['area'] = $addressInfo['province'].' '.$addressInfo['city'].' '.$addressInfo['district'];
				$addressInfo['areacode'] = explode(",", $addressInfo['areacode']);
				$this->assign('addressInfo',$addressInfo);
			}
			$this->display();
		}
	}
}
?>