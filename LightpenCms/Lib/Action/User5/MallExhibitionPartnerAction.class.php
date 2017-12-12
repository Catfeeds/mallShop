<?php
/**
 * 代理
 */
class MallExhibitionPartnerAction extends UserAction{
	
	private $uid;
	
	private $companyid;
	
	private $shopsid;
	
	public function _initialize(){
		parent::_initialize();
		$this->uid = session('uid');
		$this->shopsid = session('shopsid');
		$this->companyid = session('cid');
	}
	/**
	 * 代理基础设置
	 */
	public function index(){
		if(IS_POST){
			$return['code'] = '300';
			$return['tips'] = '操作失败';
			$time = time();
			$id = $this->_post('id');
			$commissionrate = $this->_post('commissionrate');
			$invitationlink = $this->_post('invitationlink');
			$info2 = $this->_post('info2');
			$data['recruitplan'] = $info2;
			$data['invitationlink'] = $invitationlink;
			$data['commissionrate'] = $commissionrate;
			if($id){
				$where['id'] = $id;
				$where['companyid'] = $this->companyid;
				$data['updatetime'] = $time;
				$result = M('mall_exhibition_partner_base')->where($where)->save($data);
			}else{
				$data['id'] = guidNow();
				$data['companyid'] = $this->companyid;
				$data['updatetime'] = $data['createtime'] = $time;
				$result = M('mall_exhibition_partner_base')->add($data);
			}
			if($result){
				$return['code'] = '200';
				$return['tips'] = '操作成功';
			}
			echo json_encode($return);
		}else{
			$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'代理','url'=>'','rel'=>'','target'=>''),array('name'=>'基础设置','url'=>'','rel'=>'','target'=>'')));
			$this->check_url = 'MallExhibitionPartnerindex';
			$info = M('mall_exhibition_partner_base')->where(array('companyid'=>$this->companyid))->field('id,recruitplan,commissionrate,invitationlink')->find();
			$this->assign('info',$info);
			$this->display();
		}
	}
	/**
	 * 代理列表
	 * @author 徐建鹏<tomas@mobiwind.cn>
	 * @since  2017-2-21
	 */
	public function partnerList(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'代理','url'=>'','rel'=>'','target'=>''),array('name'=>'代理管理','url'=>'','rel'=>'','target'=>'')));
		$this->check_url = 'MallExhibitionPartnerpartnerList';
		$mobile = $this->_request("mobile");
		if($mobile){
			$where['info.mobile'] = array('like','%'.$mobile.'%');
			$this->assign('mobile',$mobile);
		}
		$limit = 15;
		$where['list.companyid'] = $this->companyid;
		$where['list.status'] = 2;
		$count = M()->table('tp_mall_exhibition_partner_list as list')->join(array("LEFT JOIN tp_member_register_info as info ON info.id = list.mid","LEFT JOIN tp_member_wechat_info as winfo ON winfo.mid = info.id"))->where($where)->count();
		$page = new NewPage($count,15);
		$list = M()->table('tp_mall_exhibition_partner_list as list')->join(array("LEFT JOIN tp_member_register_info as info ON info.id = list.mid","LEFT JOIN tp_member_wechat_info as winfo ON winfo.mid = info.id"))->where($where)->field('list.id,list.mid,list.totalorder,list.totalmoney,list.availablemoney,list.totalreward,list.isclear,list.status,info.name,info.mobile,winfo.nickname')->order('list.createtime DESC')->limit($limit)->select();
		foreach($list as $key=>$val){
			$wherew['billtype'] = 2;
			$wherew['companyid'] = $this->companyid;
			$wherew['mid'] = $val['mid'];
			$list[$key]['withcashing'] = M('mall_dms_bill')->where($wherew)->sum('wages');
		}
		$this->assign('list',$list);
		$this->assign('page',$page->show());
		$this->display();
	}
	/**
	 * 代理街道管理
	 */
	public function manage(){
		if(IS_POST){
			// 代理区域管理
			$time = time();
			$id = $this->_post('id');
			$dataInfo = $this->_post('citysinfo');
			$mid = $this->_post('mid');
			// 代理关联地址
			$citystring = explode(";", substr($dataInfo,0,-1));
			foreach($citystring as $key=>$val){
				$citystring[$key] = explode("/", $val);
				foreach($citystring[$key] as $key2=>$val2){
					$citystring[$key][$key2] = explode("|", $val2);
				}
			}
			$citysNum = count($citystring);
			if($id){
				//编辑关联地址
				M('mall_exhibition_partner_areamanage')->where(array('companyid'=>$this->companyid,'mid'=>$mid))->delete();
				for($i=0; $i<$citysNum; $i++){
					$dataImg['id'] = guidNow();
					$dataImg['companyid'] = $this->companyid;
					$dataImg['mid'] = $mid;
					$dataImg['province'] = $citystring[$i][1][1];
					$dataImg['city'] = $citystring[$i][2][1];
					$dataImg['area'] = $citystring[$i][3][1];
					$dataImg['town'] = $citystring[$i][4][1];
					$dataImg['updatetime'] = $dataImg['createtime'] = $time;
					$result = M('mall_exhibition_partner_areamanage')->add($dataImg);
				}
			}else{
				// 创建关联地址
				for($i=0; $i<$citysNum; $i++){
					$imgData['id'] = guidNow();
					$imgData['companyid'] = $this->companyid;
					$imgData['mid'] = $mid;
					$imgData['province'] = $citystring[$i][1][1];
					$imgData['city'] = $citystring[$i][2][1];
					$imgData['area'] = $citystring[$i][3][1];
					$imgData['town'] = $citystring[$i][4][1];
					$imgData['updatetime'] = $imgData['createtime'] = $time;
					$result = M('mall_exhibition_partner_areamanage')->add($imgData);
				}
			}
			if($result){
				$return['code'] = 200;
				$return['tips'] = '操作成功';
			}else{
				$return['code'] = 300;
				$return['tips'] = '操作失败';
			}
			echo json_encode($return);
		}else{
			$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'代理','url'=>'','rel'=>'','target'=>''),array('name'=>'代理管理','url'=>'','rel'=>'','target'=>'')));
			$this->check_url = 'MallExhibitionPartnerpartnerList';
			$mid = $this->_get('mid');
			$this->assign('mid',$mid);
			$list = M('mall_exhibition_partner_areamanage')->where(array('companyid'=>$this->companyid,'mid'=>$mid))->field('id,mid,province,city,area,town')->select();
			$this->assign('list',$list);
			$this->display();
		}
	}
	/**
	 * 删除
	 * @author 徐建鹏<tomas@mobiwind.cn>
	 * @since  2017-2-21
	 */
	public function ajaxDel(){
		$id = $this->_post('id');
		$result = M('mall_exhibition_partner_areamanage')->where(array('companyid'=>$this->companyid,'id'=>$id))->delete();
		if($result){
				$return['code'] = 200;
				$return['tips'] = '操作成功';
			}else{
				$return['code'] = 300;
				$return['tips'] = '操作失败';
			}
			echo json_encode($return);
	}
	/**
	 * 提现记录
	 */
	public function totalMoney(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'代理','url'=>'','rel'=>'','target'=>''),array('name'=>'提现申请','url'=>'','rel'=>'','target'=>'')));
		$this->check_url = 'MallExhibitionPartnerindex';
		$mid = $this->_get('mid');
		$where['withd.mid'] = $mid;
		// 筛选
		// 申请人姓名
		$name = $this->_request('name');
		if($name){
	    	$where['rinfo.name'] = array('LIKE','%'.$name.'%');
	    	$this->assign('name',$name);
		}
		// 提现金额
		$withdrawcash1 = $this->_request('withdrawcash1');
		if($withdrawcash1){
	    	$cashwithdrawalWhere[] = array('egt',$withdrawcash1);
	    	$this->assign('withdrawcash1',$withdrawcash1);
 	    }
		$withdrawcash2 = $this->_request('withdrawcash2');
		if($withdrawcash2){
	    	$cashwithdrawalWhere[] = array('elt',$withdrawcash2);
	    	$this->assign('withdrawcash2',$withdrawcash2);
		}
		if($withdrawcash1 || $withdrawcash2){
	    	$where['withd.withdrawcash'] = $cashwithdrawalWhere;
		}
		// 申请人手机
		$mobile = $this->_request('mobile');
		if($mobile){
	    	$where['rinfo.mobile'] = array('LIKE','%'.$mobile.'%');
	    	$this->assign('mobile',$mobile);
		}
		// 提现请求状态
		$state = $this->_request('state');
		if($state){
	    	$where['withd.state'] = $state;
	    	$this->assign('state',$state);
 	    }
 	    // 提现申请时间
 	    $applytime1 = $this->_request('applytime1');
 	    if($applytime1){
 	        $applytimeWhere[] = array('egt',strtotime($applytime1));
 	        $this->assign('applytime1',$applytime1);
 	    }
 	    $applytime2 = $this->_request('applytime2');
 	    if($applytime2){
 	        $applytimeWhere[] = array('elt',strtotime($applytime2));
 	        $this->assign('applytime2',$applytime2);
 	    }
		if($applytime1 || $applytime2){
			$where['withd.applytime'] = $applytimeWhere;
 	    }
 	    // 管理员备注
 	    $playmoneynote = $this->_request('playmoneynote');
 	    if($playmoneynote){
 	        $where['playmoneynote'] = array('LIKE','%'.$playmoneynote.'%'); 
 	        $this->assign('withd.playmoneynote',$playmoneynote);
 	    }
	    $where['withd.companyid'] = $this->companyid;
	    $sorttype = $this->_request('sorttype');
	    $sortclass = $this->_request('sortclass');
	    if($sorttype == 1){
	    	if($sortclass=='' || $sortclass==1){
	    		$order = 'withd.applytime';
	    		$this->assign('sortclass1','2');
	    	}elseif($sortclass == 2){
	    		$order = 'withd.applytime DESC';
	    		$this->assign('sortclass1','1');
	    	}
	    }elseif($sorttype == 2){
	    	if($sortclass=='' || $sortclass==1){
	    		$order = 'withd.withdrawcash';
	    		$this->assign('sortclass2','2');
	    	}elseif($sortclass == 2){
	    		$order = 'withd.withdrawcash DESC';
	    		$this->assign('sortclass2','1');
	    	}
	    }else{
	    	$order = 'withd.applytime DESC,withd.id DESC';
	    }
	    $this->assign('sorttype',$sorttype);
	    $count = M()->table('tp_mall_exhibition_partner_withdrawcash as withd')->join(array("LEFT JOIN tp_member_register_info as rinfo ON rinfo.id = withd.mid","LEFT JOIN tp_member_wechat_info as winfo ON winfo.mid = rinfo.id"))->where($where)->count();
	    $page = new NewPage($count,15);
	    $list = M()->table('tp_mall_exhibition_partner_withdrawcash as withd')->join(array("LEFT JOIN tp_member_register_info as rinfo ON rinfo.id = withd.mid","LEFT JOIN tp_member_wechat_info as winfo ON winfo.mid = rinfo.id"))->where($where)->limit($page->firstRow.','.$page->listRows)->field('withd.id,withd.orderid,withd.applytime,withd.withdrawcash,withd.state,withd.playmoneynote,rinfo.mobile,rinfo.name,winfo.nickname')->order($order)->select();
		$this->assign('list',$list);
	    $this->assign('page',$page->show());
	    $this->display();
	}
	/**
	 * ajax提现申请修改
	 */
	public function ajaxApplyEdit(){
	    $returnData['code'] = 300;
	    $returnData['tips'] = '抱歉，服务器繁忙，请稍后重试';
	    $id = $this->_post('id');
	    $orderid = $this->_post('orderid');
	    if($id && $orderid){
	        M()->startTrans();
	        $time = time();
	        $state = $this->_post('state');  // 审核状态
	        $withdrawcash = $this->_post('withdrawcash');  // 提现金额
	        $withdrawcashInfo = M('mall_exhibition_partner_withdrawcash')->where(array('companyid'=>$this->companyid,'id'=>$id))->field('id,mid,state,orderid')->find();
	        if($withdrawcashInfo){
				if($withdrawcash){
					$data['withdrawcash'] = $withdrawcash;
				}
				if($state){
					$data['state'] = $state;
				}
				$playmoneynote = $this->_post('playmoneynote');
				if($playmoneynote){
					$data['playmoneynote'] = $playmoneynote;
				}
				$data['updatetime'] = $time;
				// 修改提现信息
				$where['id'] = $id;
				$where['companyid'] = $this->companyid;
				$withdrawcashReturn = M('mall_exhibition_partner_withdrawcash')->where($where)->save($data);
				if($withdrawcashReturn){
					// 已打款 || 已取消
					// 修改流水账表中
					$whereb['companyid'] = $this->companyid;
					$whereb['orderid'] = $withdrawcashInfo['orderid'];
					if($state == '2'){
						$billData['billtype'] = '3';
					}else{
						// 取消提现 将可提现佣金加回
						$moneyData['availablemoney'] = array('exp', '`availablemoney`+'.$withdrawcash);
						$moneyData['updatetime'] = $time;
						$result1 = M('mall_exhibition_partner_list')->where(array('companyid'=>$this->companyid,'mid'=>$withdrawcashInfo['mid']))->save($moneyData);
						$billData['billtype'] = '4';
					}
					$billData['updatetime'] = $time;
					$billReturn = M('mall_exhibition_partner_bill')->where($whereb)->save($billData);
					if($billReturn){
						M()->commit();
						$returnData['code'] = 200;
						$returnData['tips'] = '恭喜，编辑成功';
					}else{
						M()->rollback();
					}
				}
	        }
	    }
	    echo json_encode($returnData);
	}
	/**
	 * 异步清退
	 * @author 徐建鹏<tomas@mobiwind.cn>
	 * @since  2017-2-21
	 */
	public function ajaxClear(){
		$mid = $this->_post("mid");
		$data['isclear'] = 1;
		$data['updatetime'] = time();
		$result = M('mall_exhibition_partner_list')->where(array('companyid'=>$this->companyid,'mid'=>$mid))->save($data);
		if($result){
			$return['code'] = 200;
			$rerturn['tips'] = '操作成功';
		}else{
			$return['code'] = 300;
			$return['tips'] = '操作失败';
		}
		echo json_encode($return);
	}
	/**
	 * 代理列表
	 */
	public function partnerApplyList(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'代理','url'=>'','rel'=>'','target'=>''),array('name'=>'代理申请管理','url'=>'','rel'=>'','target'=>'')));
		$this->check_url = 'MallExhibitionPartnerpartnerApplyList';
		// 手机号
		$mobile = $this->_request("mobile");
		if($mobile){
			$where['info.mobile'] = array('like','%'.$mobile.'%');
			$this->assign('mobile',$mobile);
		}
		// 提现金额
		$totalspendingfrequency1 = $this->_request('totalspendingfrequency1');
		$totalspendingfrequency2 = $this->_request('totalspendingfrequency2');
		if($totalspendingfrequency1 && $totalspendingfrequency2 && $totalspendingfrequency1 <= $totalspendingfrequency2){
			$where['info.totalspendingfrequency'] = array('between',array($totalspendingfrequency1,$totalspendingfrequency2));
		}elseif($totalspendingfrequency1){
			$where['info.totalspendingfrequency'] = array('egt',$totalspendingfrequency1);
		}elseif($totalspendingfrequency2){
			$where['info.totalspendingfrequency'] = array('elt',$totalspendingfrequency2);
		}
		$this->assign('totalspendingfrequency1',$this->_request('totalspendingfrequency1'));
		$this->assign('totalspendingfrequency2',$this->_request('totalspendingfrequency2'));
		// 审核状态
		$status = $this->_request("status");
		if($status){
			$where['list.status'] = array('like','%'.$status.'%');
			$this->assign('status',$status);
		}
		// 申请时间
		$createtime1 = strtotime($this->_request('createtime1'));
		$createtime2 = strtotime($this->_request('createtime2'));
		if($createtime1 && $createtime2 && $createtime1 <= $createtime2){
			$where['list.createtime'] = array('between',array($createtime1,$createtime2));
		}elseif ($createtime1){
			$where['list.createtime'] = array('gt',$createtime1);
		}elseif ($createtime2){
			$where['list.createtime'] = array('lt',$createtime2);
		}
		$this->assign('createtime1',$this->_request('createtime1'));
		$this->assign('createtime2',$this->_request('createtime2'));
		$limit = 15;
		$where['list.companyid'] = $this->companyid;
		$count = M()->table('tp_mall_exhibition_partner_list as list')->join(array("LEFT JOIN tp_member_register_info as info ON info.id = list.mid","LEFT JOIN tp_member_wechat_info as winfo ON winfo.mid = info.id"))->where($where)->count();
		$page = new NewPage($count,15);
		$list = M()->table('tp_mall_exhibition_partner_list as list')->join(array("LEFT JOIN tp_member_register_info as info ON info.id = list.mid","LEFT JOIN tp_member_wechat_info as winfo ON winfo.mid = info.id"))->where($where)->field('list.id,list.mid,list.totalorder,list.totalcustomer,list.totalmoney,list.availablemoney,list.totalreward,list.isclear,list.status,list.createtime,info.name,info.mobile,winfo.nickname')->order('list.createtime DESC')->limit($limit)->select();
		$this->assign('list',$list);
		$this->assign('page',$page->show());
		$this->display();
	}
	/**
	 * 异步审核
	 */
	public function ajaxChecked(){
		$id = $this->_post("id");
		$status = $this->_post("status");
		$data['status'] = $status;
		$data['updatetime'] = time();
		$info = M('mall_exhibition_partner_list')->where(array('companyid'=>$this->companyid,'id'=>$id))->field('id,mid,applytype')->find();
		if($info['applytype'] == 1){
			$memberData['salestype'] = 1;
		}elseif($info['applytype'] == 2){
			$memberData['salestype'] = 2;
		}
		$memberData['updatetime'] = time();
		$memberResult = M('member_register_info')->where(array('companyid'=>$this->companyid,'id'=>$info['mid']))->save($memberData);

		$result = M('mall_exhibition_partner_list')->where(array('companyid'=>$this->companyid,'id'=>$id))->save($data);
		
		if($result && $memberResult){
			$return['code'] = 200;
			$return['tips'] = '操作成功';
		}else{
			$return['code'] = 300;
			$return['tips'] = '操作失败';
		}
		echo json_encode($return);
	}
	/**
	 * 手机预览二维码
	 * @author 徐建鹏<tomas@mobiwind.cn>
	 * @since  2017-2-22
	 */
	public function erweima(){
		$url = base64_decode($this->_get('link'));
		$this->getQRcode($url);
	}
	/**
	 * 邀请海报
	 * @author 徐建鹏<tomas@mobiwind.cn>
	 * @since  2017-2-22
	 */
	public function invitationPoster(){
		if(IS_POST){
			$return['code'] = '300';
			$return['tips'] = '操作失败';
			$time = time();
			$id = $this->_post('id');
			$invitationposter = $this->_post('invitationposter');
			$data['invitationposter'] = $invitationposter;
			if($id){
				$where['id'] = $id;
				$where['companyid'] = $this->companyid;
				$data['updatetime'] = $time;
				$result = M('mall_exhibition_partner_base')->where($where)->save($data);
			}else{
				$data['id'] = guidNow();
				$data['companyid'] = $this->companyid;
				$data['updatetime'] = $data['createtime'] = $time;
				$result = M('mall_exhibition_partner_base')->add($data);
			}
			if($result){
				$return['code'] = '200';
				$return['tips'] = '操作成功';
			}
			echo json_encode($return);
		}else{
			$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'代理','url'=>'','rel'=>'','target'=>''),array('name'=>'邀请海报设置','url'=>'','rel'=>'','target'=>'')));
			$this->check_url = 'MallExhibitionPartnerinvitationPoster';
			$info = M('mall_exhibition_partner_base')->where(array('companyid'=>$this->companyid))->field('id,invitationposter')->find();
			$this->assign('info',$info);
			$this->ImagesAll($this->companyid);
			$this->display();
		}
	}
	/**
	 * 业绩统计
	 * @author 徐建鹏<tomas@mobiwind.cn>
	 * @since  2017-2-22
	 */
	public function achievement(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'代理','url'=>'','rel'=>'','target'=>''),array('name'=>'累计订单','url'=>'','rel'=>'','target'=>'')));
		$this->check_url = 'MallExhibitionPartnerindex';
		$mid = $this->_get('mid');
		$type = $this->_get('type');
		$startMonth = mktime(0,0,0,date('m'),1,date('Y'));   // 本月开始时间戳
		$orderWhere['companyid'] = $this->companyid;
		$orderWhere['mid'] = $mid;
		$orderWhere['orderstatus'] = array('IN','4,5');
		$list = M('mall_exhibition_partner_order')->where($orderWhere)->field('id,mid,buymid,companyid,orderprice,payprice,commission,orderid,orderstatus,createtime,type')->order('createtime DESC')->select();
		//dump($list);
		if($list){
			foreach($list as $key=>$val){
				$list[$key]['mall'] = M()->table('tp_mall_order_goods as goods')->join(array('tp_mall_goods_sku AS sku ON sku.id = goods.goodskuid'))->where(array('goods.companyid'=>$this->companyid,'goods.orderid'=>$val['orderid']))->field('goods.goodtype,goods.goodid,goods.goodname as chinagoodname,goods.goodpic,goods.goodprice,goods.goodnum,sku.name as skuname')->select();
				$where['register.companyid'] = $this->companyid;
				$where['register.id'] = $val['buymid'];
				$list[$key]['member'] = M()->table('tp_member_register_info AS register')->join(array('LEFT JOIN tp_member_wechat_info AS wechat ON register.id = wechat.mid'))->where($where)->field('name,mobile,nickname')->find();
				foreach($list[$key]['mall'] as $mKey=>$mVal){
					$list[$key]['goodtype'] = $mVal['goodtype'];
				}
			}
		}
		$this->assign('type', $type);
		$this->assign('list', $list);
		$this->display();
	}
	
	
	
	
	
	
	
	/**
	 * 提现申请管理
	 * @author 徐建鹏<tomas@mobiwind.cn>
	 * @since  2017-2-22
	 */
	public function withDrawCash(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'代理','url'=>'','rel'=>'','target'=>''),array('name'=>'提现申请管理','url'=>'','rel'=>'','target'=>'')));
		$where['apply.companyid'] = $this->companyid;
		// 手机号
		$mobile = $this->_request("mobile");
		if($mobile){
			$where['info.mobile'] = array('like','%'.$mobile.'%');
			$this->assign('mobile',$mobile);
		}
		// 申请状态
		$status = $this->_request("status");
		if($status){
			$where['apply.status'] = array('like','%'.$status.'%');
			$this->assign('status',$status);
		}
		// 申请时间
		$createtime1 = strtotime($this->_request('createtime1'));
		$createtime2 = strtotime($this->_request('createtime2'));
		if($createtime1 && $createtime2 && $createtime1 <= $createtime2){
			$where['apply.createtime'] = array('between',array($createtime1,$createtime2));
		}elseif ($createtime1){
			$where['apply.createtime'] = array('gt',$createtime1);
		}elseif ($createtime2){
			$where['apply.createtime'] = array('lt',$createtime2);
		}
		$this->assign('createtime1',$this->_request('createtime1'));
		$this->assign('createtime2',$this->_request('createtime2'));
		$limit = 15;
		$count = M()->table('tp_mall_exhibition_partner_withdrawcash as apply')->join(array("LEFT JOIN tp_member_register_info as info ON info.id = apply.mid","LEFT JOIN tp_mall_exhibition_partner_list as list ON apply.mid = list.mid"))->where($where)->count();
		$page = new NewPage($count,15);
		$list = M()->table('tp_mall_exhibition_partner_withdrawcash as apply')->join(array("LEFT JOIN tp_member_register_info as info ON info.id = apply.mid","LEFT JOIN tp_mall_exhibition_partner_list as list ON apply.mid = list.mid"))->where($where)->field('apply.id,apply.mid,apply.withdrawcash,info.name,info.mobile,list.alipayaccount,list.bankaccount,list.bankholder,list.bankbranch')->order('apply.createtime DESC')->limit($limit)->select();
		$this->assign('list',$list);
		$this->assign('page',$page->show());
		$this->display();
	}
	/**
	 * 异步管理提现
	 * @author 徐建鹏<tomas@mobiwind.cn>
	 * @since  2017-2-22
	 */
	public function ajaxManageWithDrawCash(){
		$time = time();
		$id = $this->_post('id');
		$status = $this->_post('status');
		$remark = $this->_post('remark');
		$pic1 = $this->_post('img1');
		$pic2 = $this->_post('img2');
		$pic3 = $this->_post('img3');
		$mid = $this->_post('mid');
		$withdrawcash = $this->_post('withdrawcash');
		// 获取当前数据库中的状态
		$thisStatus = M('mall_exhibition_partner_withdrawcash')->where(array('companyid'=>$this->companyid,'id'=>$id))->getField('status');
		if($id){
			$data['status'] = $status;
			if($status == 3 || $status == 4){
				if($thisStatus != $status){
					// 拒绝提现 以及 取消提现 将可提现佣金加回
					$moneyData['availablemoney'] = array('exp', '`availablemoney`+'.$withdrawcash);
					$moneyData['updatetime'] = $time;
					$result1 = M('mall_exhibition_partner_list')->where(array('companyid'=>$this->companyid,'mid'=>$mid))->save($moneyData);
					// 添加一条账单记录
					$billData['id'] = guidNow();
					$billData['companyid'] = $this->companyid;
					$billData['mid'] = $mid;
					$billData['wcashid'] = $id;
					$billData['billtype'] = 4;
					$billData['withdrawcash'] = $withdrawcash;
					$billData['createtime'] = $billData['updatetime'] = $time;
					$result2 = M('mall_exhibition_partner_bill')->add($billData);
				}else{
					$result1 = 1;
				}
			}elseif($status == 2){
				if($thisStatus != $status){
					// 添加一条账单记录
					$billData['id'] = guidNow();
					$billData['companyid'] = $this->companyid;
					$billData['mid'] = $mid;
					$billData['wcashid'] = $id;
					$billData['billtype'] = 3;
					$billData['withdrawcash'] = $withdrawcash;
					$billData['createtime'] = $billData['updatetime'] = $time;
					$result2 = M('mall_exhibition_partner_bill')->add($billData);
				}
				$result1 = 1;
			}elseif($status == 1){
				$result1 = 1;
			}
			$data['remark'] = $remark;
			$data['pic1'] = $pic1;
			$data['pic2'] = $pic2;
			$data['pic3'] = $pic3;
			$data['updatetime'] = $time;
			$result = M('mall_exhibition_partner_withdrawcash')->where(array('companyid'=>$this->companyid,'id'=>$id))->save($data);
		}
		if($result && $result1){
			$return['code'] = 200;
			$return['tips'] = '操作成功';
		}else{
			$return['code'] = 300;
			$return['tips'] = '操作失败';
		}
		echo json_encode($return);
	}
}
?>