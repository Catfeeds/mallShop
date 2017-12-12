<?php
/**
 * 经销
 */
class DmsAction extends UserAction{
	
	private $uid;
	
	private $companyid;
	
	private $shopsid;
	
	
	public function __construct(){
		parent::__construct();
		$this->uid = session('uid');
		$this->companyid = session('cid');
		$this->shopsid = session('shopsid');
	}
	
	/**
	 * 经销基础设置
	 */
	public function index(){
		if(IS_POST){
			$return['code'] = '300';
			$return['tips'] = '操作失败';
			$time = time();
			$id = $this->_post('id');
			$commissionrate = $this->_post('commissionrate');
			$invitationlink = $this->_post('invitationlink');
			$info3 = $this->_post('info3');
			$data['distriplan'] = $info3;
			$data['invitationlink'] = $invitationlink;
			$data['commissionrate'] = $commissionrate;
			if($id){
				$where['id'] = $id;
				$where['companyid'] = $this->companyid;
				$data['updatetime'] = $time;
				$result = M('mall_dms_base')->where($where)->save($data);
			}else{
				$data['id'] = guidNow();
				$data['companyid'] = $this->companyid;
				$data['updatetime'] = $data['createtime'] = $time;
				$result = M('mall_dms_base')->add($data);
			}
			if($result){
				$return['code'] = '200';
				$return['tips'] = '操作成功';
			}
			echo json_encode($return);
		}else{
			$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'经销','url'=>'','rel'=>'','target'=>''),array('name'=>'基础设置','url'=>'','rel'=>'','target'=>'')));
			$this->check_url = 'malldmsbase';
			$info = M('mall_dms_base')->where(array('companyid'=>$this->companyid))->field('id,distriplan,commissionrate,invitationlink')->find();
			$this->assign('info',$info);
			$this->display();
		}
	}
	/**
	 * 经销列表
	 * @author 徐建鹏<tomas@mobiwind.cn>
	 * @since  2017-2-21
	 */
	public function dmsList(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'经销','url'=>'','rel'=>'','target'=>''),array('name'=>'经销管理','url'=>'','rel'=>'','target'=>'')));
		$this->check_url = 'Dmsdmslist';
		$mobile = $this->_request("mobile");
		if($mobile){
			$where['info.mobile'] = array('like','%'.$mobile.'%');
			$this->assign('mobile',$mobile);
		}
		$limit = 15;
		$where['list.companyid'] = $this->companyid;
		$where['list.status'] = 2;
		$count = M()->table('tp_mall_dms_list as list')->join(array("LEFT JOIN tp_member_register_info as info ON info.id = list.mid","LEFT JOIN tp_member_wechat_info as winfo ON winfo.mid = info.id"))->where($where)->count();
		$page = new NewPage($count,15);
		$list = M()->table('tp_mall_dms_list as list')->join(array("LEFT JOIN tp_member_register_info as info ON info.id = list.mid","LEFT JOIN tp_member_wechat_info as winfo ON winfo.mid = info.id"))->where($where)->field('list.id,list.mid,list.totalorder,list.totalmoney,list.availablemoney,list.totalreward,list.isclear,list.status,info.name,info.mobile,winfo.nickname')->order('list.createtime DESC')->limit($limit)->select();
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
	 * 经销申请管理列表
	 */
	public function dmsApplyList(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'经销','url'=>'','rel'=>'','target'=>''),array('name'=>'经销用户申请管理','url'=>'','rel'=>'','target'=>'')));
		$this->check_url = 'DmsdmsApplyList';
		// 手机号
		$mobile = $this->_request("mobile");
		if($mobile){
			$where['info.mobile'] = array('like','%'.$mobile.'%');
			$this->assign('mobile',$mobile);
		}
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
		//$where['status'] = 1;
		$where['list.companyid'] = $this->companyid;
		$count = M()->table('tp_mall_dms_list as list')->join(array("LEFT JOIN tp_member_register_info as info ON info.id = list.mid","LEFT JOIN tp_member_wechat_info as winfo ON winfo.mid = info.id"))->where($where)->count();
		$page = new NewPage($count,15);
		$list = M()->table('tp_mall_dms_list as list')->join(array("LEFT JOIN tp_member_register_info as info ON info.id = list.mid","LEFT JOIN tp_member_wechat_info as winfo ON winfo.mid = info.id"))->where($where)->field('list.id,list.mid,list.totalorder,list.totalmoney,list.availablemoney,list.totalreward,list.isclear,list.status,list.createtime,info.name,info.mobile,winfo.nickname')->order('list.createtime DESC')->limit($limit)->select();
		$this->assign('list',$list);
		$this->assign('page',$page->show());
		$this->display();
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
				$result = M('mall_dms_base')->add($data);
			}
			if($result){
				$return['code'] = '200';
				$return['tips'] = '操作成功';
			}
			echo json_encode($return);
		}else{
			$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'经销','url'=>'','rel'=>'','target'=>''),array('name'=>'邀请海报设置','url'=>'','rel'=>'','target'=>'')));
			$this->check_url = 'DmsinvitationPoster';
			$info = M('mall_dms_base')->where(array('companyid'=>$this->companyid))->field('id,invitationposter')->find();
			$this->assign('info',$info);
			$this->ImagesAll($this->companyid);
			$this->display();
		}
	}
	/**
	 * 异步审核
	 */
	public function ajaxChecked(){
		$time = time();
		$id = $this->_post("id");
		$status = $this->_post("status");
		$data['status'] = $status;
		$data['updatetime'] = $time;
		$info = M('mall_dms_list')->where(array('companyid'=>$this->companyid,'id'=>$id))->field('id,mid,applytype')->find();
		if($info['applytype'] == 1){
			$memberData['salestype'] = 1;
		}elseif($info['applytype'] == 2){
			$memberData['salestype'] = 2;
		}
		$memberData['updatetime'] = $time;
		$memberResult = M('member_register_info')->where(array('companyid'=>$this->companyid,'id'=>$info['mid']))->save($memberData);
		$result = M('mall_dms_list')->where(array('companyid'=>$this->companyid,'id'=>$id))->save($data);
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
	 * 异步清退
	 * @author 徐建鹏<tomas@mobiwind.cn>
	 * @since  2017-2-21
	 */
	public function ajaxClear(){
		$mid = $this->_post("mid");
		$data['isclear'] = 1;
		$data['updatetime'] = time();
		$result = M('mall_dms_list')->where(array('companyid'=>$this->companyid,'mid'=>$mid))->save($data);
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
	 * 订单列表
	 */
	public function OrderList(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'经销','url'=>U('Dms/index'),'rel'=>'','target'=>''),array('name'=>'累计订单','url'=>'','rel'=>'','target'=>'')));
		$this->check_url = 'malldmsbase';
		$mid = $this->_get('mid');
		$type = $this->_get('type');
		$startMonth = mktime(0,0,0,date('m'),1,date('Y'));   // 本月开始时间戳
		$orderWhere['companyid'] = $this->companyid;
		$orderWhere['mid'] = $mid;
		$orderWhere['ordertype'] =  '1';
		$orderWhere['orderstatus'] = array('IN','4,5');
		$list = M('mall_dms_order')->where($orderWhere)->field('id,mid,buymid,companyid,wagesmoney,ordermoney,orderid,orderstatus,ordertype,confirmtime,createtime')->order('createtime DESC')->select();
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
	 * 经销提现申请
	 */
	public function Apply(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'经销提现申请','url'=>'','rel'=>'','target'=>'')));
		$this->check_url = 'malldmsbase';
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
	    $count = M()->table('tp_mall_dms_withdrawcash as withd')->join(array("LEFT JOIN tp_member_register_info as rinfo ON rinfo.id = withd.mid","LEFT JOIN tp_member_wechat_info as winfo ON winfo.mid = rinfo.id"))->where($where)->count();
	    $page = new NewPage($count,15);
	    $list = M()->table('tp_mall_dms_withdrawcash as withd')->join(array("LEFT JOIN tp_member_register_info as rinfo ON rinfo.id = withd.mid","LEFT JOIN tp_member_wechat_info as winfo ON winfo.mid = rinfo.id"))->where($where)->limit($page->firstRow.','.$page->listRows)->field('withd.id,withd.orderid,withd.applytime,withd.withdrawcash,withd.state,withd.playmoneynote,rinfo.mobile,rinfo.name,winfo.nickname')->order($order)->select();
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
	        $withdrawcashInfo = M('mall_dms_withdrawcash')->where(array('companyid'=>$this->companyid,'id'=>$id))->field('id,mid,state,orderid')->find();
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
				$withdrawcashReturn = M('mall_dms_withdrawcash')->where($where)->save($data);
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
						$result1 = M('mall_dms_list')->where(array('companyid'=>$this->companyid,'mid'=>$withdrawcashInfo['mid']))->save($moneyData);
						$billData['billtype'] = '4';
					}
					$billData['updatetime'] = $time;
					$billReturn = M('mall_dms_bill')->where($whereb)->save($billData);
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
	 * 
	 * 添加奖金
	 * 
	 * @author Leo<1251868177@qq.com>
	 * @since  2017-2-9
	 */
	public function ajaxBonus(){
	    $returnData['code'] = 300;
	    $returnData['tips'] = '抱歉，服务器繁忙，请稍后重试';
	    $keyid = $this->_post('keyid');
	    $wages = $this->_post('wages');
	    $note = $this->_post('note');
	    if($keyid && $wages){
	        $time = time();
	        $keyWhere['companyid'] = $this->companyid;
	        $keyWhere['id'] = $keyid;
	        $keyData['totalbonus'] = array('exp', 'totalbonus+'.$wages);
	        $keyData['totalmoney'] = array('exp', 'totalmoney+'.$wages);
	        $keyData['ktxmoney'] = array('exp', 'ktxmoney+'.$wages);
	        $keyData['updatetime'] = $time;
	        M()->startTrans();
	        $keyReturn = M('dms_discoukey')->where($keyWhere)->save($keyData);
	        if($keyReturn){
	            $uid = $this->uid;
	            if($uid){
	                $userInfo = M('users')->where(array('companyid'=>$this->companyid, 'id'=>$uid))->field('id,username,truename')->find();
	            }
	            $billData['id'] = guidNow();
	            $billData['companyid'] = $this->companyid;
	            $billData['keyid'] = $keyid;
	            $billData['billtype'] = 6;
	            $billData['borderid'] = get_order_id();
	            $billData['money'] = $billData['wages'] = $wages;
	            $billData['userid'] = $userInfo['id']?$userInfo['id']:'';
	            $billData['username'] = $userInfo['username']?$userInfo['username']:'';
	            $billData['note'] = $note?$note:'';
	            $billData['searchtime'] = mktime(0,0,0,date("m",$time),date("d",$time),date("Y",$time));
	            $billData['createtime'] = $time;
	            $return = M('dms_bill')->add($billData);
	        }
	        if($return){
	            M()->commit();
	            $returnData['code'] = 200;
	            $returnData['tips'] = '恭喜,奖金添加成功';
	        }else{
	            M()->rollback();
	        }
	    }
	    echo json_encode($returnData);
	}
	/**
	 * 
	 * 奖金列表
	 * 
	 * @author Leo<1251868177@qq.com>
	 * @since  2017-2-9
	 */
	public function bonusLists(){
	    $keyid = $this->_get('keyid');
	    if($keyid){
	        $keyInfo = M('dms_discoukey')->where(array('companyid'=>$this->companyid, 'id'=>$keyid))->field('id,name')->find();
	        if($keyInfo){
	            $title = $keyInfo['name'].'的奖金记录';    
    	        $this->makeTopUrl = $this->makeTopUrl_User(array($this->SCENE_WELCOME,array('name'=>'DMS优惠口令','url'=>'','rel'=>'','target'=>''),array('name'=>'优惠口令管理','url'=>U('Dms/Discou'),'rel'=>'','target'=>''),array('name'=>$title,'url'=>'','rel'=>'','target'=>'')));
    	        $where['companyid'] = $this->companyid;
    	        $where['keyid'] = $keyid;
    	        $where['billtype'] = 6;
    	        $count = M('dms_bill')->where($where)->count();
        	    $page = new NewPage($count,15);
        	    $list = M('dms_bill')->where($where)->limit($page->firstRow.','.$page->listRows)->field('id,keyid,money,wages,userid,username,note,createtime')->order('createtime DESC')->select();
        	    $this->assign('title',$title);
        	    $this->assign('list',$list);
        	    $this->assign('page',$page->show());
        	    $this->display();
	        }
	    }
	}
	/**
	 * 
	 * 账单流水
	 * 
	 * @author Leo<1251868177@qq.com>
	 * @since  2016-2-25
	 */
	public function ajaxBillLists(){
	    $returnData['code'] = 300;
	    $returnData['tips'] = '抱歉，服务器繁忙，请稍后重试';
	    $keyid = $this->_post('keyid');
	    $date = $this->_post('date');
	    if($keyid && $date){
    	    preg_match_all('/\d/',$date,$dateArr);
    	    $year = $dateArr['0']['0'].$dateArr['0']['1'].$dateArr['0']['2'].$dateArr['0']['3'];
    	    $month = $dateArr['0']['4'].$dateArr['0']['5'];
    	    $startTime = strtotime($year.'-'.$month);  // 开始时间
    	    if($month == '12'){
    	        $nextMonth = '1';
    	        $nextYear = ($year+1);
    	    }else{
    	        $nextMonth = ($month+1);
    	        $nextYear = $year;
    	    }
    	    $endTime = (strtotime($nextYear.'-'.$nextMonth)-1);  // 结束时间
    	    $searchWhere['companyid'] = $where['companyid'] = $this->companyid;
    	    $searchWhere['keyid'] = $where['keyid'] = $keyid;
    	    $searchWhere['createtime'] = array(array('egt',$startTime),array('elt',$endTime));
    	    $type = $this->_post('type');
    	    if($type == '2'){
    	        // 收入
    	        $searchWhere['billtype'] = $where['billtype'] = array('IN','1,5');
    	    }elseif($type == '3'){
    	        // 提现
    	        $searchWhere['billtype'] = $where['billtype'] = array('IN','2,3,4');
    	    }else{
    	        // 全部
    	    }
    	    $searchLists = M('dms_bill')->where($searchWhere)->field('searchtime')->order('searchtime DESC')->group('searchtime')->select();
    	    $list = '';
    	    foreach ($searchLists as $key=>$val){
	            $list[$key]['date'] = $val['searchtime'];
	            $where['searchtime'] = $val['searchtime'];
	            $list[$key]['count'] = M('dms_bill')->where($where)->field('id,billtype,borderid,money,mid,searchtime')->order('id DESC')->select();
	            foreach ($list[$key]['count'] as $cKey=>$cVal){
	                if($cVal['billtype'] == '1'){
	                    // 微信昵称/用户名
	                    $info = M()->table('tp_member_register_info AS register')->join('tp_member_wechat_info AS wechat ON register.id=wechat.mid')->where(array('register.companyid'=>$this->companyid,'register.id'=>$cVal['mid']))->field('name,nickname')->find();
	                    $list[$key]['count'][$cKey]['name'] = $info['name'];
	                    $list[$key]['count'][$cKey]['nickname'] = $info['nickname'];
	                }
	            }
    	        unset($where['searchtime'],$info);
    	    }
	    }
	    if($list){
	        $string = '';
	        foreach ($list as $lKey=>$lVal){
	            $string .= '<div class="tcc_billingxf_1">';
	            $string .= '<p><i>';
	            $string .= format_time($lVal['date'],'ymd');
	            $string .= '</i></p>';
	            $string .= '<table class="tcc_billing_tab" cellspacing="0" cellpadding="0">';
	            foreach ($lVal['count'] as $llKey=>$llVal){
	                $string .= '<tr>';
	                $string .= '<td class="tcc_billing_tab1"></td>';
	                if($llVal['billtype'] == '1'){
	                    $string .= '<td class="tcc_billing_tab2"><span class="tcc_sru">收入</span></td>';
	                }elseif($llVal['billtype'] == '2'){
	                    $string .= '<td class="tcc_billing_tab2"><span class="tcc_txsqing">提现<br/>申请</span></td>';
	                }elseif($llVal['billtype'] == '3'){
	                    $string .= '<td class="tcc_billing_tab2"><span class="tcc_zzhhan">转账</span></td>';
	                }elseif($llVal['billtype'] == '4'){
	                    $string .= '<td class="tcc_billing_tab2"><span class="tcc_qxiao">取消</span></td>';
	                }elseif($llVal['billtype'] == '5'){
	                    $string .= '<td class="tcc_billing_tab2"><span class="tcc_jsuan">结算</span></td>';
	                }
	                $string .= '<td class="tcc_billing_tab3">';
	                if($llVal['billtype'] == '1'){
	                    $string .= $llVal['nickname']?$llVal['nickname']:$llVal['name'];
	                    $string .= '消费了<i>';
	                    $string .= $llVal['money']?$llVal['money']:'0.00';
	                    $string .= '</i>元';
	                }elseif($llVal['billtype'] == '2'){
	                    $string .= '提交了<i>';
	                    $string .= $llVal['money']?$llVal['money']:'0.00';
	                    $string .= '元提现申请,相应金额临时冻结';
	                }elseif($llVal['billtype'] == '3'){
	                    $string .= '<i>';
	                    $string .= $llVal['money']?$llVal['money']:'0.00';
	                    $string .= '</i>元提现成功';
	                }elseif($llVal['billtype'] == '4'){
	                    $string .= '<i>';
	                    $string .= $llVal['money']?$llVal['money']:'0.00';
	                    $string .= '</i>元提现失败';
	                }elseif($llVal['billtype'] == '5'){
	                    $string .= '上月收入<i>';
	                    $string .= $llVal['money']?$llVal['money']:'0.00';
	                    $string .= '</i>元已结算成功';
	                }
	                $string .= '</td>';
	                $string .= '<td class="tcc_billing_tab4">';
	                if($llVal['billtype'] == '1'){
	                    $string .= '<i>';
	                    $string .= $llVal['money']?$llVal['money']:'0.00';
	                    $string .= '</i>';
	                }elseif($llVal['billtype'] == '2'){
	                    $string .= '<i>';
	                    $string .= $llVal['money']?$llVal['money']:'0.00';
	                    $string .= '</i>';
	                }elseif($llVal['billtype'] == '3'){
	                    $string .= '<i>-';
	                    $string .= $llVal['money']?$llVal['money']:'0.00';
	                    $string .= '</i>';
	                }elseif($llVal['billtype'] == '4'){
	                    $string .= '<i>+';
	                    $string .= $llVal['money']?$llVal['money']:'0.00';
	                    $string .= '</i>';
	                }elseif($llVal['billtype'] == '5'){
	                    $string .= '<i>+';
	                    $string .= $llVal['money']?$llVal['money']:'0.00';
	                    $string .= '</i>';
	                }
	                $string .= '</tr>';
	            }
	            $string .= '</table>';
	            $string .= '</div>';
	        }
	        if($string){
	            $returnData['code'] = 200;
	            $returnData['tips'] = '恭喜，加载成功';
	            $returnData['string'] = $string;
	        }
	    }
	    echo json_encode($returnData);    
	}
	/**
	 * 
	 * 查看DMS详情
	 * 
	 * @author Leo<1251868177@qq.com>
	 * @since  2016-7-27
	 */
	public function DiscouSelect(){
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->MANAGE_WELCOME,array('name'=>'DMS优惠口令','url'=>'','rel'=>'','target'=>''),array('name'=>'优惠口令管理','url'=>U('Dms/Discou'),'rel'=>'','target'=>''),array('name'=>'优惠口令详情','url'=>'','rel'=>'','target'=>'')));
	    $id = $this->_get('id');
	    if($id){
	        $info = M('dms_discoukey')->where(array('companyid'=>$this->companyid,'id'=>$id))->find();
	        $this->assign('info',$info);
       	    $this->display();
	    }
	}
	/**
	 *订单列表
	 */
	public function OrderList1(){
	   /*  $mid = $this->_get('mid');
		$this->makeTopUrl = $this->makeTopUrl_User(array($this->SCENE_WELCOME,array('name'=>'DMS优惠口令','url'=>'','rel'=>'','target'=>''),array('name'=>'优惠口令管理','url'=>U('Dms/Discou'),'rel'=>'','target'=>''),array('name'=>'客户数','url'=>U('Dms/MonthCustomerList',array('keyid'=>$keyid)),'rel'=>'','target'=>''),array('name'=>'消费流水','url'=>'','rel'=>'','target'=>'')));
		$where['customer.companyid'] = $this->companyid;
		$where['customer.mid'] = $mid?$mid:'0';
		$info = M()->table('tp_dms_customer AS customer')->join(array('LEFT JOIN tp_member_register_info AS register ON customer.mid = register.id','LEFT JOIN tp_member_wechat_info AS wechat ON register.id = wechat.mid'))->where($where)->order('customer.id DESC')->field('customer.id,customer.mid,ordernum,totalprice,name,moblie,nickname')->find();
		$this->assign('info',$info);
		$orderWhere['dorder.companyid'] = $this->companyid;
		$orderWhere['dorder.keyid'] = $keyid;
		$orderWhere['dorder.mid'] = $mid?$mid:'0';
		$orderWhere['dorder.orderstatus'] = array('IN','4,5');
		$orderWhere['dorder.ordertype'] = '1';
		$list = M()->table('tp_mall_dms_order as dorder')->join(array('tp_dms_discoukey AS ddiscoukey ON ddiscoukey.id = dorder.keyid'))->where($orderWhere)->field('dorder.id,dorder.companyid,dorder.keyid,dorder.wagesmoney,dorder.ordermoney,dorder.orderid,dorder.orderstatus,dorder.ordertype,dorder.discoukey,dorder.confirmtime,dorder.createtime,dorder.discoutype,dorder.startdiscoumoney,dorder.discoumoney,dorder.discouratio,ddiscoukey.startdiscoumoney8,ddiscoukey.discoumoney8,dorder.giftname')->order('createtime DESC')->select();
		if($list){
			foreach($list as $key=>$val){
				$list[$key]['mall'] = M()->table('tp_mall_order_goods as goods')->join(array('tp_mall_goods_sku AS sku ON sku.id = goods.goodskuid'))->where(array('goods.companyid'=>$this->companyid,'goods.orderid'=>$val['orderid']))->field('goods.goodtype,goods.goodid,goods.goodname as chinagoodname,goods.goodpic,goods.goodprice,goods.goodnum,sku.name as skuname')->select();
			}
		}
		$this->assign('list',$list);
		$this->assign('type',$type);
		$this->assign('mid',$mid);
		$this->assign('keyid',$keyid); */
		$this->display();
	}
}
?>