<?php
/**
 * 会员制规则
 * 
 * @author    Mark<1311013341@qq.com>
 * @since     2016-11-8
 * @version   1.0
 */
class MemberCardRankAction extends UserAction{
	
	private $uid;
	
	private $memberCardRankModel;
	
	private $memberRankPrivilegeModel;
	
	private $memberRankPrivilegeLinkModel;
	
	private $companyid;

	private $shopsid;

	private $companyname;
	
	public function __construct(){
		parent::__construct();
		//检查公司配置
		$this->checkCompanyScrm5Permissions(34,TRUE);
		$this->memberCardRankModel 	        = D('Member_card_rank');
		$this->memberRankPrivilegeModel     = M('member_rank_privilege');
		$this->memberRankPrivilegeLinkModel = M('member_rank_privilege_link');
		$this->uid 		 = session('uid');
		$this->companyid = session('cid');
		$this->shopsid   = session('shopsid');
		$this->companyname = session('cname');
	}
	/**
	 * 会员等级列表
	 */
	public function index(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->CRM_WELCOME,array('name'=>'卡券中心','url'=>U('Vouchers/center'),'rel'=>'','target'=>''),array('name'=>'会员卡','url'=>'','rel'=>'','target'=>''),array('name'=>'会员制规则','url'=>'','rel'=>'','target'=>'')));
		//会员等级说明
		//$this->ImagesAll($this->companyid);
		$where['companyid'] = $this->companyid;
		$time = time();
		$cardRank = M('member_card_rank')->where($where)->field('id,name,number,beginscore,endscore,reportnumber')->order('number asc')->select();
		if(!$cardRank){
			$cname = session('cname');
			$cardRank = array(
					array('id'=>guidNow(),'companyid'=>$this->companyid,'number'=>'1','updatetime'=>$time,'createtime'=>$time,'cardname'=>$cname,'cardvicename'=>$cname),
					array('id'=>guidNow(),'companyid'=>$this->companyid,'number'=>'2','updatetime'=>$time,'createtime'=>$time,'cardname'=>$cname,'cardvicename'=>$cname),
					array('id'=>guidNow(),'companyid'=>$this->companyid,'number'=>'3','updatetime'=>$time,'createtime'=>$time,'cardname'=>$cname,'cardvicename'=>$cname),
					array('id'=>guidNow(),'companyid'=>$this->companyid,'number'=>'4','updatetime'=>$time,'createtime'=>$time,'cardname'=>$cname,'cardvicename'=>$cname),
			);
			M()->startTrans();
			$result = M('member_card_rank')->addAll($cardRank);
			if($result){
				M()->commit();
			}else{
				M()->rollback();
			}
		}
		$this->assign('list',$cardRank);
		//累计积分获取规则
		$integralInfo = M('member_integral_set')->where($where)
		->field('id,rechargequickpayversion,rechargequickpayisopen,integralmultipleisopen,integralmultiple,birthdayisopen,birthdaymultiple,storedvalueisopen,storedvalueconversion,integralshoppic,integralconvertmoneypic,integralisautoclear,integralconvertmoney,integralconvertmoneyconversion,integralshop,wechatsubscribeisopen,wechatsubscribeint,createcardisopen,createcardint,perfectreginfoisopen,perfectreginfoint,yaoqiandaoisopen,yaoqiandaoint,dianpingisopen,dianpingint,firstconsumptionisopen,firstconsumptionint,eshoppayisopen,eshoppayisopen,eshoppayconversion,shanhuipayisopen,shanhuipayconversion,lakalapayisopen,lakalapayconversion,windhelperpayisopen,windhelperpayconversion,rechargepayisopen,rechargepayconversion,mobilebookisopen,mobilebookint,takeoutisopen,takeoutconversion,mobilephoneorderisopen,mobilephoneorderconversion,mobilebookpayisopen,mobilebookpayconversion,paidcontentshopversion,paidcontentshopisopen')
		->find();
		if(!$integralInfo){
			$integralInfo['id'] = guidNow();
			$integralInfo['companyid'] = $this->companyid;
			$integralInfo['createtime'] = $integralInfo['updatetime'] = $time;
			M('member_integral_set')->add($integralInfo);
		}
		$this->assign('integralInfo',$integralInfo);
		$Cardset = M('member_card_rank_set')->where($where)->field('id,info')->find();
		if(!$Cardset){
			$Cardset['id'] = guidNow();
			$Cardset['companyid'] = $this->companyid;
			$Cardset['createtime'] = $Cardset['updatetime'] = $time;
			M('member_card_rank_set')->add($Cardset);
		}
		$this->assign('Cardset',$Cardset);
		$this->display();
	}
	/**
	 * 
	 * ajax修改等级积分
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-11-8
	 */
	public function ajaxSaveRank(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍候重试';
		if(IS_POST){
			M()->startTrans();
			$date = json_decode(htmlspecialchars_decode($_POST['data']),true);
			$where['companyid'] = $this->companyid;
			$a = 0;
			foreach($date as $key=>$val){
				$where['id'] = $val['id'];
				$data['updatetime'] = time();
				$data['name'] = $val['rankname'];
				if($val['beginscore']){
					$data['beginscore'] = $val['beginscore'];
				}
				if($val['endscore']){
					$data['endscore'] = $val['endscore'];
				}
				$result = M('member_card_rank')->where($where)->save($data);
				if($result){
					$a+=1;
				}
			}
			if($a == count($date)){
				$rankList = M('member_card_rank')->where(array('companyid'=>$this->companyid))->field('id,number,beginscore,endscore')->select();
				$num = 0;
				if($rankList){
					foreach ($rankList as $rkey=>$rval){
						$wheres['companyid'] = $this->companyid;
						$wheres['isregister'] = '1';
						if($rval['number'] == '1'){
							$wheres['totalexperiencevalue'] = array('elt',$rval['endscore']);
						}elseif($rval['number'] == '4'){
							$wheres['totalexperiencevalue'] = array('egt',$rval['beginscore']);
						}else{
							$wheres['totalexperiencevalue'] = array('between',array($rval['beginscore'],$rval['endscore']+1));
						}
						$array = M('member_register_info')->where($wheres)->getField('id',true);
						if($array){
							$mid = '';
							foreach ($array as $val){
								$mid .=$val.',';
							}
							$mid = substr($mid, 0,-1);
							M('member_card_info')->where(array('companyid'=>$this->companyid,'mid'=>array('in',$mid)))->save(array('rankid'=>$rval['id'],'updatetime'=>time()));
							$count = count($array);
						}else{
							$count = '0';
						}
						$num = $count+$num;
						M('member_card_rank')->where(array('companyid'=>$this->companyid,'id'=>$rval['id']))->save(array('reportnumber'=>$count,'updatetime'=>time()));
						unset($rval,$wheres,$array,$mid);
					}
				}
				M('company')->where(array('id'=>$this->companyid))->save(array('registermembernum'=>$num,'updatetime'=>time()));
				M()->commit();
				$ajax['code'] = '200';
				$ajax['msg'] = '保存成功';
			}else{
				M()->rollback();
				$ajax['msg'] = '保存失败';
			}
		}
		echo json_encode($ajax);
	}
	/**
	 *
	 * ajax累计积分获取规则
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-11-8
	 */
	public function ajaxSaveIntegral(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍候重试';
		if(IS_POST){
			M()->startTrans();
			if($this->_post('type') == '1'){
				$data['integralisautoclear'] = $this->_post('integralisautoclear');
				$data['integralconvertmoney'] = $this->_post('integralconvertmoney');
				$data['integralshop'] = $this->_post('integralshop');
			}elseif($this->_post('type') == '2'){
				$data['integralconvertmoneyconversion'] = $this->_post('integralconvertmoneyconversion');
			}elseif($this->_post('type') == '3'){
				if($this->_post('integralshoppic') == './Tpl/User5/default/common/img/WeiPage-temp-img.png'){
					$data['integralshoppic'] = '';
				}else{
					$data['integralshoppic'] = $this->_post('integralshoppic');
				}
				if($this->_post('integralconvertmoneypic') == './Tpl/User5/default/common/img/WeiPage-temp-img.png'){
					$data['integralconvertmoneypic'] = '';
				}else{
					$data['integralconvertmoneypic'] = $this->_post('integralconvertmoneypic');
				}
			}else{
				$data['wechatsubscribeisopen'] = $this->_post('wechatsubscribeisopen');
				if($this->_post('wechatsubscribeint')){
					$data['wechatsubscribeint'] = $this->_post('wechatsubscribeint');
				}
				$data['createcardisopen'] = $this->_post('createcardisopen');
				if($this->_post('createcardint')){
					$data['createcardint'] = $this->_post('createcardint');
				}
				$data['perfectreginfoisopen'] = $this->_post('perfectreginfoisopen');
				if($this->_post('perfectreginfoint')){
					$data['perfectreginfoint'] = $this->_post('perfectreginfoint');
				}
				$data['yaoqiandaoisopen'] = $this->_post('yaoqiandaoisopen');
				if($this->_post('yaoqiandaoint')){
					$data['yaoqiandaoint'] = $this->_post('yaoqiandaoint');
				}
				$data['dianpingisopen'] = $this->_post('dianpingisopen');
				if($this->_post('dianpingint')){
					$data['dianpingint'] = $this->_post('dianpingint');
				}
				$data['firstconsumptionisopen'] = $this->_post('firstconsumptionisopen');
				if($this->_post('firstconsumptionint')){
					$data['firstconsumptionint'] = $this->_post('firstconsumptionint');
				}
				$data['eshoppayisopen'] = $this->_post('eshoppayisopen');
				if($this->_post('eshoppayconversion')){
					$data['eshoppayconversion'] = $this->_post('eshoppayconversion');
				}
				$data['shanhuipayisopen'] = $this->_post('shanhuipayisopen');
				if($this->_post('shanhuipayconversion')){
					$data['shanhuipayconversion'] = $this->_post('shanhuipayconversion');
				}
				$data['takeoutisopen'] = $this->_post('takeoutisopen');
				if($this->_post('takeoutconversion')){
					$data['takeoutconversion'] = $this->_post('takeoutconversion');
				}
				$data['mobilephoneorderisopen'] = $this->_post('mobilephoneorderisopen');
				if($this->_post('mobilephoneorderconversion')){
					$data['mobilephoneorderconversion'] = $this->_post('mobilephoneorderconversion');
				}
				$data['lakalapayisopen'] = $this->_post('lakalapayisopen');
				if($this->_post('lakalapayconversion')){
					$data['lakalapayconversion'] = $this->_post('lakalapayconversion');
				}
				$data['windhelperpayisopen'] = $this->_post('windhelperpayisopen');
				if($this->_post('windhelperpayconversion')){
					$data['windhelperpayconversion'] = $this->_post('windhelperpayconversion');
				}
				$data['storedvalueisopen'] = $this->_post('storedvalueisopen');
				if($this->_post('storedvalueconversion')){
					$data['storedvalueconversion'] = $this->_post('storedvalueconversion');
				}
				$data['mobilebookisopen'] = $this->_post('mobilebookisopen');
				if($this->_post('mobilebookint')){
					$data['mobilebookint'] = $this->_post('mobilebookint');
				}
				$data['mobilebookpayisopen'] = $this->_post('mobilebookpayisopen');
				if($this->_post('mobilebookpayconversion')){
					$data['mobilebookpayconversion'] = $this->_post('mobilebookpayconversion');
				}
				$data['rechargequickpayisopen'] = $this->_post('rechargequickpayisopen');
				if($this->_post('rechargequickpayversion')){
					$data['rechargequickpayversion'] = $this->_post('rechargequickpayversion');
				}
				$data['birthdayisopen'] = $this->_post('birthdayisopen');
				if($this->_post('birthdaymultiple')){
					$data['birthdaymultiple'] = $this->_post('birthdaymultiple');
				}
				$data['paidcontentshopisopen'] = $this->_post('paidcontentshopisopen');
				if($this->_post('paidcontentshopversion')){
					$data['paidcontentshopversion'] = $this->_post('paidcontentshopversion');
				}
				$data['integralmultipleisopen'] = $this->_post('integralmultipleisopen');
			}
			$data['updatetime'] = time();
			$where['companyid'] = $this->companyid;
			$result = M('member_integral_set')->where($where)->save($data);
			if($result){
				M()->commit();
				$ajax['code'] = '200';
				$ajax['msg'] = '保存成功';
			}else{
				M()->rollback();
				$ajax['msg'] = '保存失败';
			}
		}
		echo json_encode($ajax);
	}
	/**
	 * 
	 * ajax设置等级积分倍数
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2017-2-17
	 */
	public function ajaxSaveintegralmultiple(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍候重试';
		if(IS_POST){
			$date = htmlspecialchars_decode($this->_post('date'));
			if($date){
				$data['integralmultiple'] = $date;
				$data['updatetime'] = time();
				$where['companyid'] = $this->companyid;
				$result = M('member_integral_set')->where($where)->save($data);
				if($result){
					$ajax['code'] = '200';
					$ajax['msg'] = '保存成功';
				}else{
					$ajax['msg'] = '保存失败';
				}
			}else{
				$ajax['msg'] = '保存失败';
			}
		}
		echo json_encode($ajax);
	}
	/**
	 * 
	 * ajax修改会员制说明
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-11-9
	 */
	public function ajaxSaveInfo(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍候重试';
		if(IS_POST){
			$data['info'] = $this->_post('info');
			$data['updatetime'] = time();
			$where['companyid'] = $this->companyid;
			$result = M('member_card_rank_set')->where($where)->save($data);
			if($result){
				$ajax['code'] = '200';
				$ajax['msg'] = '保存成功';
			}else{
				$ajax['msg'] = '保存失败';
			}
		}
		echo json_encode($ajax);
	}
	/**
	 * 
	 * 卡面设计
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-11-10
	 */
	public function set(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->CRM_WELCOME,array('name'=>'卡券中心','url'=>U('Vouchers/center'),'rel'=>'','target'=>''),array('name'=>'会员卡','url'=>U('MemberCardRank/index'),'rel'=>'','target'=>''),array('name'=>'会员制规则','url'=>U('MemberCardRank/index'),'rel'=>'','target'=>''),array('name'=>'会员卡面设计','url'=>'','rel'=>'','target'=>'')));
		$this->ImagesAll($this->companyid);
		$where['companyid'] = $this->companyid;
		$where['id'] = $this->_get('id');
		if($where['id']){
			$Info = M('member_card_rank')->where($where)->field('id,cardname,cardvicename,cardlogo,cardbacktype,cardbackcolor,cardbackground,cardtextcolor,cardbodybackground,cardmaincolor')->find();
			$this->assign('Info',$Info);
			$this->display();
		}else{
			$this->redirect(U('Welcome/index'));
		}
	}
	/**
	 * 
	 * ajax卡面设计
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-11-10
	 */
	public function ajaxSaveCardset(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍候重试';
		if(IS_POST){
			$_POST['updatetime'] = time();
			$where['companyid'] = $this->companyid;
			$where['id'] = $this->_get('id');
			$result = M('member_card_rank')->where($where)->save($_POST);
			if($result){
				$ajax['code'] = '200';
				$ajax['msg'] = '保存成功';
			}else{
				$ajax['msg'] = '保存失败';
			}
		}
		echo json_encode($ajax);
	}
	/**
	 *
	 * 等级权益
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-11-10
	 */
	public function equity(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->CRM_WELCOME,array('name'=>'卡券中心','url'=>U('Vouchers/center'),'rel'=>'','target'=>''),array('name'=>'会员卡','url'=>U('MemberCardRank/index'),'rel'=>'','target'=>''),array('name'=>'会员制规则','url'=>U('MemberCardRank/index'),'rel'=>'','target'=>''),array('name'=>'等级权益','url'=>'','rel'=>'','target'=>'')));
		$lwhere['companyid'] = $where['companyid'] = $this->companyid;
		$lwhere['rankid'] = $where['id'] = $this->_get('id');
		if($where['id']){
			$Info = M('member_card_rank')->where($where)->field('id,number,desc')->find();
			$this->assign('Info',$Info);
			$List = M('member_cardrank_voucher')->where($lwhere)->field('id,voucherid,type,sku,num')->order('createtime desc')->select();
			if($List){
				foreach ($List as $key=>$val){
					if($val['type'] == '1'||$val['type'] == '2'||$val['type'] == '3'||$val['type'] == '4'||$val['type'] == '40'){
						$dbname = 'member_marketing_activities_voucher_info';
					}else{
						$dbname = 'mall_goods';
						if($val['type'] == '5'||$val['type'] == '6'||$val['type'] == '7'){
							$List[$key]['skuname'] = M('mall_goods_sku')->where(array('companyid'=>$this->companyid,'goodid'=>$val['voucherid'],'id'=>$val['sku']))->getField('name');
						}
					}
					$List[$key]['vouchername'] = M($dbname)->where(array('companyid'=>$this->companyid,'id'=>$val['voucherid']))->getField('title');
				}
			}
			$this->assign('List',$List);
			$this->display();
		}else{
			$this->redirect(U('Welcome/index'));
		}
	}
	/**
	 *
	 * ajax修改会等级权益
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-11-9
	 */
	public function ajaxSaveDesc(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍候重试';
		if(IS_POST){
			$data['desc'] = $this->_post('desc');
			$data['updatetime'] = time();
			$where['companyid'] = $this->companyid;
			$where['id'] = $this->_get('id');
			$result = M('member_card_rank')->where($where)->save($data);
			if($result){
				$ajax['code'] = '200';
				$ajax['msg'] = '保存成功';
			}else{
				$ajax['msg'] = '保存失败';
			}
		}
		echo json_encode($ajax);
	}
	/**
	 * 
	 * ajax选择券名称
	 * 
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-11-11
	 */
	public function ajaxVoucherName(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍候重试';
		if(IS_POST){
			$where['companyid'] = $this->companyid;
			$voucherid = $this->_post('vouchertype');
			if($voucherid == '1'||$voucherid == '2'||$voucherid == '3'||$voucherid == '4'||$voucherid == '40'){
				$where['vouchertype'] = $voucherid;
				$list = M('member_marketing_activities_voucher_info')->where($where)->field('id,title')->order('updatetime desc')->select();
			}elseif($voucherid == '5'||$voucherid == '6'||$voucherid == '7'||$voucherid == '8'){
				$where['goodtype'] = $voucherid-2;
				$list = M('mall_goods')->where($where)->field('id,title')->order('updatetime desc')->select();
			}else{
				$list = '';
			}
			if($list){
				$ajax['code'] = '200';
				$ajax['html'] = '<option value="0">卡券名称</option>';
				foreach ($list as $key=>$val){
					$ajax['html'] .= '<option value="'.$val['id'].'">'.$val['title'].'</option>';
				}
			}else{
				$ajax['code'] = '200';
				$ajax['html'] = '<option value="0">暂无</option>';
			}
		}
		echo json_encode($ajax);
	}
	/**
	 *
	 * ajax选择券规格
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-11-11
	 */
	public function ajaxVoucherSku(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍候重试';
		if(IS_POST){
			$where['companyid'] = $this->companyid;
			$where['goodid'] = $this->_post('voucherid');
			$list = M('mall_goods_sku')->where($where)->field('id,name')->order('sort asc')->select();
			if($list){
				$ajax['code'] = '200';
				$ajax['html'] = '<option value="0">规格名</option>';
				foreach ($list as $key=>$val){
					$ajax['html'] .= '<option value="'.$val['id'].'">'.$val['name'].'</option>';
				}
			}else{
				$ajax['code'] = '200';
				$ajax['html'] = '<option value="no">暂无</option>';
			}
		}
		echo json_encode($ajax);
	}
	/**
	 *
	 * ajax关联券
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-11-11
	 */
	public function ajaxAddVoucher(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍候重试';
		if(IS_POST){
			$data['rankid'] = $this->_get('id');
			if($data['rankid']){
				$data['id'] = guidNow();
				$data['companyid'] = $this->companyid;
				$data['type'] = $this->_post('type');
				$data['voucherid'] = $this->_post('voucherid');
				if($this->_post('sku') && $this->_post('sku') != 'no'){
					$data['sku'] = $this->_post('sku');
				}
				$data['num'] = $this->_post('num');
				$data['updatetime'] = $data['createtime'] = time();
				$result = M('member_cardrank_voucher')->add($data);
				if($result){
					$ajax['code'] = '200';
					$ajax['id'] = $data['id'];
				}else{
					$ajax['msg'] = '添加失败';
				}
			}
		}
		echo json_encode($ajax);
	}
	/**
	 *
	 * ajax删除关联券
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-11-11
	 */
	public function ajaxDelVoucher(){
		$ajax['code'] = '300';
		$ajax['msg'] = '网络繁忙，请稍候重试';
		if(IS_POST){
			$where['id'] = $this->_post('id');
			$where['companyid'] = $this->companyid;
			$result = M('member_cardrank_voucher')->where($where)->delete();
			if($result){
				$ajax['code'] = '200';
			}else{
				$ajax['msg'] = '删除失败';
			}
		}
		echo json_encode($ajax);
	}
	/**
	 *
	 * 手机预览二维码
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-9-12
	 */
	public function mcode(){
		$url = base64_decode($this->_get('link'));
		$this->getQRcode($url);
	}
	/**
	 *
	 * test
	 *
	 * @author Mark<1311013341@qq.com>
	 * @since  2016-11-17
	 */
	public function test(){
		$option['uid'] = '';
		$option['cid'] = $this->companyid;
		$option['mid'] = '1085';
		$option['type'] = '101';
		/* $option['num'];
		 $option['linkorderid'];
		$option['paytype'];
		$option['note'];
		$option['rechargetype'];*/
		$this->changeMemberBusinessSCRM5($option);
		//$this->NewchangMemberCardRank($this->companyid,'1085');
	}
}
?>